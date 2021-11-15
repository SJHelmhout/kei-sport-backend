<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ExerciseLogRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     normalizationContext={"groups" = {"exerciseLog:read"}, "enable_max_depth" = true },
 *     denormalizationContext={"groups" = {"workoutLog:write"}, "enable_max_depth" = true },
 * )
 * @ORM\Entity(repositoryClass=ExerciseLogRepository::class)
 */
class ExerciseLog
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"workoutLog:read", "exerciseLog:read"})
     */
    private ?int $id;


    /**
     * @ORM\Column(type="datetime")
     * @Groups({"workoutLog:read", "workoutLog:write", "exerciseLog:read"})
     */
    private DateTimeInterface $startTime;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"workoutLog:read", "workoutLog:write", "exerciseLog:read"})
     */
    private DateTimeInterface $endTime;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="exerciseLogs")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"workoutLog:read", "workoutLog:write", "exerciseLog:read"})
     */
    private ?User $user;

    /**
     * @ORM\ManyToOne(targetEntity=Exercise::class, inversedBy="exerciseLogs")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"workoutLog:read", "workoutLog:write", "exerciseLog:read"})
     */
    private ?Exercise $exercise;

    /**
     * @ORM\ManyToOne(targetEntity=CircuitLog::class, inversedBy="exerciseLogs")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?CircuitLog $circuitLog;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartTime(): ?DateTimeInterface
    {
        return $this->startTime;
    }

    public function setStartTime(DateTimeInterface $startTime): self
    {
        $this->startTime = $startTime;

        return $this;
    }

    public function getEndTime(): ?DateTimeInterface
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

    public function getExercise(): ?Exercise
    {
        return $this->exercise;
    }

    public function setExercise(?Exercise $exercise): self
    {
        $this->exercise = $exercise;

        return $this;
    }

    public function getCircuitLog(): ?CircuitLog
    {
        return $this->circuitLog;
    }

    public function setCircuitLog(?CircuitLog $circuitLog): self
    {
        $this->circuitLog = $circuitLog;

        return $this;
    }
}
