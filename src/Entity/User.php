<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ApiResource(
 *     collectionOperations={
 *          "get"={"security"="is_granted('ROLE_ADMIN')"},
 *          "post"={"security"="is_granted('ROLE_ADMIN')"},
 *     },
 *     itemOperations={
 *          "get"={"security"="is_granted('get_item', object)"},
 *          "patch"={"security"="is_granted('patch', object)"},
 *     },
 * )
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private ?string $email;

    /**
     * @ORM\Column(type="json")
     */
    private array $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private string $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $color;

    /**
     * User's password before hashing
     */
    private ?string $plainPassword = '';

    /**
     * @ORM\ManyToMany(targetEntity=Session::class, mappedBy="users")
     * @param Collection
     */
//    public ArrayCollection $sessions;
    public Collection $sessions;

    /**
     * @ORM\OneToMany(targetEntity=WorkoutLog::class, mappedBy="user", orphanRemoval=true)
     */
    private Collection $workoutLogs;

    /**
     * @ORM\OneToMany(targetEntity=CircuitLog::class, mappedBy="user", orphanRemoval=true)
     */
    private Collection $circuitLogs;

    /**
     * @ORM\OneToMany(targetEntity=ExerciseLog::class, mappedBy="user", orphanRemoval=true)
     */
    private Collection $exerciseLogs;

    /**
     * @ORM\ManyToOne(targetEntity=Workout::class)
     */
    private ?Workout $selectedWorkout;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $twoFactorCode;

    public function __construct()
    {
        $this->sessions = new ArrayCollection();
        $this->workoutLogs = new ArrayCollection();
        $this->circuitLogs = new ArrayCollection();
        $this->exerciseLogs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->id;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    /**
     * @return Collection|Session[]
     */
    public function getSessions(): Collection
    {
        return $this->sessions;
    }

    /**
     * @param Session $session
     * @return $this
     */
    public function addSession(Session $session): self
    {
        if (!$this->sessions->contains($session)) {
            $this->sessions[] = $session;
            $session->addUser($this);
        }

        return $this;
    }

    public function removeSession(Session $session): self
    {
        if ($this->sessions->removeElement($session)) {
            $session->removeUser($this);
        }

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
            $workoutLog->setUser($this);
        }

        return $this;
    }

    public function removeWorkoutLog(WorkoutLog $workoutLog): self
    {
        if ($this->workoutLogs->removeElement($workoutLog)) {
            // set the owning side to null (unless already changed)
            if ($workoutLog->getUser() === $this) {
                $workoutLog->setUser(null);
            }
        }

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
            $circuitLog->setUser($this);
        }

        return $this;
    }

    public function removeCircuitLog(CircuitLog $circuitLog): self
    {
        if ($this->circuitLogs->removeElement($circuitLog)) {
            // set the owning side to null (unless already changed)
            if ($circuitLog->getUser() === $this) {
                $circuitLog->setUser(null);
            }
        }

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
            $exerciseLog->setUser($this);
        }

        return $this;
    }

    public function removeExerciseLog(ExerciseLog $exerciseLog): self
    {
        if ($this->exerciseLogs->removeElement($exerciseLog)) {
            // set the owning side to null (unless already changed)
            if ($exerciseLog->getUser() === $this) {
                $exerciseLog->setUser(null);
            }
        }

        return $this;
    }

    public function getSelectedWorkout(): ?Workout
    {
        return $this->selectedWorkout;
    }

    public function setSelectedWorkout(?Workout $selectedWorkout): self
    {
        $this->selectedWorkout = $selectedWorkout;

        return $this;
    }

    public function getTwoFactorCode(): ?string
    {
        return $this->twoFactorCode;
    }

    public function setTwoFactorCode(?string $twoFactorCode): self
    {
        $this->twoFactorCode = $twoFactorCode;

        return $this;
    }
}
