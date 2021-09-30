<?php


namespace App\Serializer;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Api\IriConverterInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Metadata\Resource\Factory\ResourceMetadataFactoryInterface;
use ApiPlatform\Core\Metadata\Resource\ResourceNameCollection;
use App\Exception\Hydra\HydraResourceMismatchException;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Inflector\Inflector;
use Doctrine\ORM\Mapping\Column;
use Doctrine\Common\Persistence\ManagerRegistry;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;

class DocsLdNormalizer implements NormalizerInterface
{
    const ANNOTATIONS = [
        Length::class => ['min', 'max'],
        Email::class => [],
        Choice::class => ['choices'],
    ];

    private $decorated;
    /**
     * @var IriConverterInterface
     */
    private $iriConverter;
    /**
     * @var RouterInterface
     */
    private $router;
    /**
     * @var ResourceMetadataFactoryInterface
     */
    private $resourceMetadataFactory;
    /**
     * @var ManagerRegistry
     */
    private $managerRegistry;
    /**
     * @var AnnotationReader
     */
    private $annotationReader;

    /**
     * DocsLdNormalizer constructor.
     * @param NormalizerInterface $decorated
     * @param IriConverterInterface $iriConverter
     * @param RouterInterface $router
     * @param ResourceMetadataFactoryInterface $resourceMetadataFactory
     * @param ManagerRegistry $managerRegistry
     */
    public function __construct(
        NormalizerInterface $decorated,
        IriConverterInterface $iriConverter,
        RouterInterface $router,
        ResourceMetadataFactoryInterface $resourceMetadataFactory,
        ManagerRegistry $managerRegistry
    ) {
        $this->decorated = $decorated;
        $this->iriConverter = $iriConverter;
        $this->router = $router;
        $this->resourceMetadataFactory = $resourceMetadataFactory;
        $this->managerRegistry = $managerRegistry;
        $this->annotationReader = new AnnotationReader();
    }

    /**
     * @inheritDoc
     * @throws ReflectionException
     * @throws HydraResourceMismatchException
     */
    public function normalize($object, string $format = null, array $context = [])
    {
        $docs = $this->decorated->normalize($object, $format, $context);
        $resources = $docs['hydra:supportedClass'];
        /** @var ResourceNameCollection $resourceCollection */
        $resourceCollection = $object->getResourceNameCollection();
        $resourceClasses = [];
        foreach ($resourceCollection as $resourceName) {
            $resourceClasses[] = $resourceName;
        }
        foreach ($resources as $resourceKey => $resource) {
            //skips non entities/models
            if (empty($resource['rdfs:label'])) {
                continue;
            }
            $resourceName = $resource['rdfs:label'];
            $resourceClass = $resourceClasses[$resourceKey];
            $reflectionClass = new ReflectionClass($resourceClass);
            if ($reflectionClass->getShortName() !== $resourceName) {
                throw new HydraResourceMismatchException(sprintf('Resource mismatch for resource "%s". Tried class "%s"',
                    $resourceName, $resourceClass));
            }
            $docs['hydra:supportedClass'][$resourceKey]['@filters'] = $this->getFilters($reflectionClass);
            $docs['hydra:supportedClass'][$resourceKey]['@endpoint'] = $this->getRouteName($resourceName);
            //add constraints to docs
            $properties = $docs['hydra:supportedClass'][$resourceKey]['hydra:supportedProperty'];
            foreach ($properties as $propertyKey => $property) {
                $propertyName = $property['hydra:title'];
                $constraints = $this->getConstraints($reflectionClass, $propertyName);
                if (!empty($constraints)) {
                    $docs['hydra:supportedClass'][$resourceKey]['hydra:supportedProperty'][$propertyKey]['hydra:property']['constraints'] = $constraints;
                }
                //add or modify type if needed
                $column = $this->getPropertyAnnotation($reflectionClass, $propertyName, Column::class);
                if ($column) {
                    $newType = null;
                    switch($column->type) {
                        case 'array':
                            $newType = 'kei:array';
                            break;
                        case 'text':
                            $newType = 'kei:text';
                            break;
                        case 'date':
                            $newType = 'kei:date';
                    }
                    if ($newType) {
                        $docs['hydra:supportedClass'][$resourceKey]['hydra:supportedProperty'][$propertyKey]['hydra:property']['range'] = $newType;
                    }
                }
            }
        }
        return $docs;
    }

    /**
     * @param ReflectionClass $reflectionClass
     * @return array
     */
    public function getFilters(ReflectionClass $reflectionClass): array
    {
        $filters = [];
        foreach ($this->annotationReader->getClassAnnotations($reflectionClass) as $annotation) {
            if (!$annotation instanceof ApiFilter) {
                continue;
            }
            $filterClass = $annotation->filterClass;
            if ($annotation->properties) {
                if ($filterClass === OrderFilter::class) {
                    foreach ($annotation->properties as $property) {
                        $filters['sortable'][] = $property;
                    }
                }
                if ($filterClass === DateFilter::class || $filterClass === BooleanFilter::class) {
                    foreach ($annotation->properties as $property) {
                        $filters['searchable'][] = $property;
                    }
                } elseif ($filterClass === SearchFilter::class) {
                    foreach (array_keys($annotation->properties) as $property) {
                        $filters['searchable'][] = $property;
                    }
                }
                continue;
            }
            //for now: support of custom filters is dropped. Info: Jira SV-6.

            //assume custom filter, instantiate and get description
//            $instance = new $annotation->filterClass($this->managerRegistry);
//            foreach ($instance->getDescription($reflectionClass->getShortName()) as $property => $propertyData) {
//                $filters['searchable']['custom_properties'][] = [
//                    'property' => $property,
//                    'type' => $propertyData['type'],
//                ];
//            }
        }
        return $filters;
    }

    /**
     * @param ReflectionClass $reflectionClass
     * @param string $propertyName
     * @param string $annotationClass
     * @return object|null
     */
    public function getPropertyAnnotation(ReflectionClass $reflectionClass, string $propertyName, string $annotationClass): ?object
    {
        $className = $reflectionClass->getName();
        try {
            $property = new ReflectionProperty($className, $propertyName);
        } catch (ReflectionException $e) {
            return null;
        }
        return $this->annotationReader->getPropertyAnnotation($property, $annotationClass);
    }

    /**
     * @param ReflectionClass $reflectionClass
     * @param string $propertyName
     * @return array
     * @throws ReflectionException
     */
    public function getConstraints(ReflectionClass $reflectionClass, string $propertyName): array
    {
        $className = $reflectionClass->getName();
        try {
            $property = new ReflectionProperty($className, $propertyName);
        } catch (ReflectionException $e) {
            return [];
        }
        $annotations = [];
        foreach (self::ANNOTATIONS as $constraint => $fields) {
            $annotation = $this->annotationReader->getPropertyAnnotation($property, $constraint);
            if ($annotation) {
                $constraintReflectionClass = new ReflectionClass($constraint);
                $propertyData = [
                    'constraint' => $constraintReflectionClass->getShortName(),
                    'parameters' => [],
                ];
                foreach ($fields as $field) {
                    $propertyData['parameters'][$field] = $annotation->$field;
                }
                $annotations[] = $propertyData;
            }
        }
        return $annotations;
    }

    /**
     * @param string $resourceName
     * @return string
     */
    private function getRouteName(string $resourceName): string
    {
        $nameConverter = new CamelCaseToSnakeCaseNameConverter();
        $snakeCaseResourceName = $nameConverter->normalize($resourceName);
        $pluralizedResourceName = Inflector::pluralize($snakeCaseResourceName);
        return '/' . $pluralizedResourceName;
    }

    /**
     * @inheritDoc
     */
    public function supportsNormalization($data, string $format = null)
    {
        return $this->decorated->supportsNormalization($data, $format);
    }
}