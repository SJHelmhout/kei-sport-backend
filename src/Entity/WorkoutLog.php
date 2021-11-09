<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\WorkoutLogRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Controller\Api\Visualisation\Graphs\RecentWorkoutsChartController;
use App\Controller\Api\Visualisation\Graphs\MostPerformedWorkoutsController;

/**
 * @ApiResource(
 *     collectionOperations={
 *     "get",
 *     "recent_workouts"={
 *              "method"="GET",
 *              "path"="/workout_logs/recent_workouts",
 *             "controller"=RecentWorkoutsChartController::class,
 *              "security"="is_granted('ROLE_ADMIN') or is_granted('ROLE_USER')",
 *          },
 *     "most_performed_workouts"={
 *            "method"="GET",
 *            "path"="/workout_logs/most_performed",
 *            "controller"=MostPerformedWorkoutsController::class,
 *            "security"="is_granted('ROLE_ADMIN') or is_granted('ROLE_USER')",
 *           }
 *     }
 * )
 * @ORM\Entity(repositoryClass=WorkoutLogRepository::class)
 */
class WorkoutLog
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
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="workoutLogs")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?User $user;

    /**
     * @ORM\ManyToOne(targetEntity=Workout::class, inversedBy="workoutLogs")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Workout $workout;

    /**
     * @ORM\OneToMany(targetEntity=CircuitLog::class, mappedBy="workoutLog", orphanRemoval=true)
     */
    private Collection $circuitLogs;

    public function __construct()
    {
        $this->circuitLogs = new ArrayCollection();
    }

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
            $circuitLog->setWorkoutLog($this);
        }

        return $this;
    }

    public function removeCircuitLog(CircuitLog $circuitLog): self
    {
        if ($this->circuitLogs->removeElement($circuitLog)) {
            // set the owning side to null (unless already changed)
            if ($circuitLog->getWorkoutLog() === $this) {
                $circuitLog->setWorkoutLog(null);
            }
        }

        return $this;
    }
}
