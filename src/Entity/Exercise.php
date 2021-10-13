<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ExerciseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=ExerciseRepository::class)
 */
class Exercise
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $equipment;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $reps;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $duration;

    /**
     * @ORM\OneToMany(targetEntity=ExerciseLog::class, mappedBy="exercise", orphanRemoval=true)
     */
    private Collection $exerciseLogs;

    /**
     * @ORM\ManyToOne(targetEntity=Circuit::class, inversedBy="exercises")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Circuit $circuit;

    /**
     * @ORM\ManyToOne(targetEntity=Device::class, inversedBy="exercises")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Device $device;

    public function __construct()
    {
        $this->exerciseLogs = new ArrayCollection();
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

    public function getEquipment(): ?string
    {
        return $this->equipment;
    }

    public function setEquipment(?string $equipment): self
    {
        $this->equipment = $equipment;

        return $this;
    }

    public function getReps(): ?int
    {
        return $this->reps;
    }

    public function setReps(?int $reps): self
    {
        $this->reps = $reps;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(?int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * @return Collection|ExerciseLog[]
     */
    public function getExerciseLogs(): Collection
    {
        return $this->exerciseLogs;
    }

    public function addExerciseLog(ExerciseLog $exerciseLog): self
    {
        if (!$this->exerciseLogs->contains($exerciseLog)) {
            $this->exerciseLogs[] = $exerciseLog;
            $exerciseLog->setExercise($this);
        }

        return $this;
    }

    public function removeExerciseLog(ExerciseLog $exerciseLog): self
    {
        if ($this->exerciseLogs->removeElement($exerciseLog)) {
            // set the owning side to null (unless already changed)
            if ($exerciseLog->getExercise() === $this) {
                $exerciseLog->setExercise(null);
            }
        }

        return $this;
    }

    public function getCircuit(): ?Circuit
    {
        return $this->circuit;
    }

    public function setCircuit(?Circuit $circuit): self
    {
        $this->circuit = $circuit;

        return $this;
    }

    public function getDevice(): ?Device
    {
        return $this->device;
    }

    public function setDevice(?Device $device): self
    {
        $this->device = $device;

        return $this;
    }
}
