<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\SessionRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Controller\Api\TwoFactorLogin\FindMySessionController;
use App\Controller\Api\Visualisation\Graphs\CurrentActiveSessionsController;

/**
 * @ApiResource(
 *     collectionOperations={
 *          "get",
 *          "post",
 *          "find_my_session"={
 *              "method"="GET",
 *              "path"="/sessions/find_my_session",
 *              "controller"=FindMySessionController::class,
 *              "security"="is_granted('ROLE_ADMIN') or is_granted('ROLE_USER')",
 *         },
 *         "currently_active_sessions"={
 *              "method"="GET",
 *              "path"="/sessions/currently_active_sessions",
 *              "controller"=CurrentActiveSessionsController::class,
 *              "security"="is_granted('ROLE_ADMIN')",
 *         },
 *     },
 * )
 * @ORM\Entity(repositoryClass=SessionRepository::class)
 */
class Session
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
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="sessions")
     */
    private Collection $users;

    /**
     * @ORM\ManyToOne(targetEntity=Workout::class, inversedBy="sessions")
     * @ORM\JoinColumn()
     */
    private Workout $workout;

    /**
     * @ORM\Column(type="boolean")
     */
    private ?bool $isActive;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWorkout(): Workout
    {
        return $this->workout;
    }

    public function setWorkout($workout): self
    {
        $this->workout = $workout;

        return $this;
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

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        $this->users->removeElement($user);

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }
}
