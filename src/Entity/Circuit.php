<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CircuitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;

/**
 * @ApiResource(
 *     normalizationContext={"groups" = {"circuit:read"}},
 *     denormalizationContext={"groups" = {"workout:write"}, "enable_max_depth" = true },
 * )
 * @ORM\Entity(repositoryClass=CircuitRepository::class)
 */
class Circuit
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"workout:read", "circuit:read"})
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"workout:read", "workout:write", "circuit:read"})
     */
    private ?string $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"workout:read", "workout:write", "circuit:read"})
     */
    private ?string $description;

    /**
     * @ORM\OneToMany(targetEntity=CircuitLog::class, mappedBy="circuit", orphanRemoval=true)
     */
    private Collection $circuitLogs;

    /**
     * @ORM\ManyToOne(targetEntity=Workout::class, inversedBy="circuits")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"workout:read", "circuit:read"})
     */
    private ?Workout $workout;

    /**
     * @ORM\OneToMany(targetEntity=Exercise::class, mappedBy="circuit", orphanRemoval=true, cascade={"persist"})
     * @Groups({"workout:read", "workout:write", "circuit:read"})
     * @MaxDepth (1)
     */
    private Collection $exercises;

    public function __construct()
    {
        $this->circuitLogs = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|CircuitLog[]
     */
    public function getCircuitLogs(): Collection
    {
        return $this->circuitLogs;
    }

    public function addCircuitLog(CircuitLog $circuitLog): self
    {
        if (!$this->circuitLogs->contains($circuitLog)) {
            $this->circuitLogs[] = $circuitLog;
            $circuitLog->setCircuit($this);
        }

        return $this;
    }

    public function removeCircuitLog(CircuitLog $circuitLog): self
    {
        if ($this->circuitLogs->removeElement($circuitLog)) {
            // set the owning side to null (unless already changed)
            if ($circuitLog->getCircuit() === $this) {
                $circuitLog->setCircuit(null);
            }
        }

        return $this;
    }

    public function getWorkout(): ?Workout
    {
        return $this->workout;
    }

    public function setWorkout(?Workout $workout): self
    {
        $this->workout = $workout;

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
            $exercise->setCircuit($this);
        }

        return $this;
    }

    public function removeExercise(Exercise $exercise): self
    {
        if ($this->exercises->removeElement($exercise)) {
            // set the owning side to null (unless already changed)
            if ($exercise->getCircuit() === $this) {
                $exercise->setCircuit(null);
            }
        }

        return $this;
    }
}
