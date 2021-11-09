<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CircuitLogRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource(
 * )
 * @ORM\Entity(repositoryClass=CircuitLogRepository::class)
 */
class CircuitLog
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTimeInterface $startTime;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTimeInterface $endTime;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="circuitLogs")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?User $user;

    /**
     * @ORM\ManyToOne(targetEntity=Circuit::class, inversedBy="circuitLogs")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Circuit $circuit;

    /**
     * @ORM\ManyToOne(targetEntity=WorkoutLog::class, inversedBy="circuitLogs")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?WorkoutLog $workoutLog;

    /**
     * @ORM\OneToMany(targetEntity=ExerciseLog::class, mappedBy="circuitLog", orphanRemoval=true)
     */
    private Collection $exerciseLogs;

    public function __construct()
    {
        $this->exerciseLogs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartTime(): DateTimeInterface
    {
        return $this->startTime;
    }

    public function setStartTime(DateTimeInterface $startTime): self
    {
        $this->startTime = $startTime;

        return $this;
    }

    public function getEndTime(): DateTimeInterface
    {
        return $this->endTime;
    }

    public function setEndTime(DateTimeInterface $endTime): self
    {
        $this->endTime = $endTime;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

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

    public function getWorkoutLog(): ?WorkoutLog
    {
        return $this->workoutLog;
    }

    public function setWorkoutLog(?WorkoutLog $workoutLog): self
    {
        $this->workoutLog = $workoutLog;

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
            $exerciseLog->setCircuitLog($this);
        }

        return $this;
    }

    public function removeExerciseLog(ExerciseLog $exerciseLog): self
    {
        if ($this->exerciseLogs->removeElement($exerciseLog)) {
            // set the owning side to null (unless already changed)
            if ($exerciseLog->getCircuitLog() === $this) {
                $exerciseLog->setCircuitLog(null);
            }
        }

        return $this;
    }
}
