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
use App\Controller\JoinSession;
use App\Controller\LeaveSession;
use App\Controller\InitSession;
use App\Controller\StartSession;
use App\Controller\EndSession;
use App\Controller\CreateSession;

/**
 * @ApiResource(
 *     collectionOperations={
 *          "get",
 *          "post"={
 *              "controller"=CreateSession::class,
 *              "security"="is_granted('ROLE_ADMIN') or is_granted('ROLE_USER')",
 *          },
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
 *     itemOperations={
 *         "get",
 *         "join_session"={
 *              "method"="PATCH",
 *              "path"="/sessions/{id}/join",
 *              "controller"=JoinSession::class,
 *              "security"="is_granted('ROLE_ADMIN') or is_granted('ROLE_USER')",
 *          },
 *          "leave_session"={
 *              "method"="PATCH",
 *              "path"="/sessions/{id}/leave",
 *              "controller"=LeaveSession::class,
 *              "security"="is_granted('ROLE_ADMIN') or is_granted('ROLE_USER')",
 *          },
 *          "start_session"={
 *              "method"="PATCH",
 *              "path"="/sessions/{id}/start",
 *              "controller"=StartSession::class,
 *              "security"="is_granted('ROLE_ADMIN') or is_granted('ROLE_USER')",
 *          },
 *          "end_session"={
 *              "method"="PATCH",
 *              "path"="/sessions/{id}/end",
 *              "controller"=EndSession::class,
 *              "security"="is_granted('ROLE_ADMIN') or is_granted('ROLE_USER')",
 *          },
 *          "init_session"={
 *              "method"="PATCH",
 *              "path"="/sessions/{id}/init",
 *              "controller"=InitSession::class,
 *              "security"="is_granted('ROLE_ADMIN') or is_granted('ROLE_USER')",
 *          },
 *          "delete",
 *     },
 * )
 * @ORM\Entity(repositoryClass=SessionRepository::class)
 */
class Session
{
    const STATUS_SESSION_CREATED = 'session_created';
    const STATUS_SESSION_WAITING_FOR_PARTICIPANTS = "session_waiting_for_participants";
    const STATUS_SESSION_STARTED = "session_started";
    const STATUS_SESSION_FINISHED = "session_finished";

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?DateTimeInterface $startTime = null;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?DateTimeInterface $endTime = null;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="sessions")
     */
    private Collection $users;

    /**
     * @ORM\ManyToOne(targetEntity=Workout::class, inversedBy="sessions")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?Workout $workout = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private String $status = '';
    
    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWorkout(): ?Workout
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

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status, $context = [])
    {
        $this->status = $status;
    }
}
