<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\WorkoutRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;

/**
 * @ApiResource(
 *     normalizationContext={"groups" = {"workout:read"}, "enable_max_depth" = true },
 *
 * )
 * @ORM\Entity(repositoryClass=WorkoutRepository::class)
 */
class Workout
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"workout:read"})
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"workout:read"})
     */
    private ?string $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"workout:read"})
     */
    private ?string $description;

    /**
     * @ORM\OneToMany(targetEntity=WorkoutLog::class, mappedBy="workout", orphanRemoval=true)
     * @Groups({"workout:read"})
     */
    private Collection $workoutLogs;

    /**
     * @ORM\OneToMany(targetEntity=Circuit::class, mappedBy="workout", orphanRemoval=true)
     * @Groups({"workout:read"})
     * @MaxDepth (1)
     */
    private Collection $circuits;

    /**
     * @ORM\OneToMany(targetEntity=Session::class, mappedBy="workout", orphanRemoval=true)
     * @Groups({"workout:read"})
     */
    private Collection $sessions;

    public function __construct()
    {
        $this->workoutLogs = new ArrayCollection();
        $this->circuits = new ArrayCollection();
        $this->sessions = new ArrayCollection();
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
     * @return Collection|WorkoutLog[]
     */
    public function getWorkoutLogs(): Collection
    {
        return $this->workoutLogs;
    }

    public function addWorkoutLog(WorkoutLog $workoutLog): self
    {
        if (!$this->workoutLogs->contains($workoutLog)) {
            $this->workoutLogs[] = $workoutLog;
            $workoutLog->setWorkout($this);
        }

        return $this;
    }

    public function removeWorkoutLog(WorkoutLog $workoutLog): self
    {
        if ($this->workoutLogs->removeElement($workoutLog)) {
            // set the owning side to null (unless already changed)
            if ($workoutLog->getWorkout() === $this) {
                $workoutLog->setWorkout(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Circuit[]
     */
    public function getCircuits(): Collection
    {
        return $this->circuits;
    }

    public function addCircuit(Circuit $circuit): self
    {
        if (!$this->circuits->contains($circuit)) {
            $this->circuits[] = $circuit;
            $circuit->setWorkout($this);
        }

        return $this;
    }

    public function removeCircuit(Circuit $circuit): self
    {
        if ($this->circuits->removeElement($circuit)) {
            // set the owning side to null (unless already changed)
            if ($circuit->getWorkout() === $this) {
                $circuit->setWorkout(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Session[]
     */
    public function getSessions(): Collection
    {
        return $this->sessions;
    }

    public function addSession(Session $session): self
    {
        if (!$this->sessions->contains($session)) {
            $this->sessions[] = $session;
            $session->setWorkout($this);
        }

        return $this;
    }

    public function removeSession(Session $session): self
    {
        if ($this->sessions->removeElement($session)) {
            // set the owning side to null (unless already changed)
            if ($session->getWorkout() === $this) {
                $session->setWorkout(null);
            }
        }

        return $this;
    }
}
