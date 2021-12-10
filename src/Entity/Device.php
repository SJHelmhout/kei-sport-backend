<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\DeviceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     normalizationContext={"groups" = {"device:read"}, "enable_max_depth" = true },
 *     denormalizationContext={"groups" = {"workout:write"}, "enable_max_depth" = true },
 * )
 * @ORM\Entity(repositoryClass=DeviceRepository::class)
 */
class Device
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"device:read"})
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"device:read", "workout:write"})
     */
    private ?string $name;

    /**
     * @ORM\Column(type="string", length=511, nullable=true)
     * @Groups({"device:read", "workout:write"})
     */
    private ?string $imageSrc = null;

    /**
     * @ORM\OneToMany(targetEntity=Exercise::class, mappedBy="device", orphanRemoval=true)
     */
    private Collection $exercises;

    public function __construct()
    {
        $this->exercises = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getImageSrc(): ?string
    {
        return $this->imageSrc;
    }

    public function setImageSrc(?string $imageSrc): self
    {
        $this->imageSrc = $imageSrc;

        return $this;
    }

    /**
     * @return Collection|Exercise[]
     */
    public function getExercises(): Collection
    {
        return $this->exercises;
    }

    public function addExercise(Exercise $exercise): self
    {
        if (!$this->exercises->contains($exercise)) {
            $this->exercises[] = $exercise;
            $exercise->setDevice($this);
        }

        return $this;
    }

    public function removeExercise(Exercise $exercise): self
    {
        if ($this->exercises->removeElement($exercise)) {
            // set the owning side to null (unless already changed)
            if ($exercise->getDevice() === $this) {
                $exercise->setDevice(null);
            }
        }

        return $this;
    }
}
