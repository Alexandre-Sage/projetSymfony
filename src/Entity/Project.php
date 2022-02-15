<?php

namespace App\Entity;

use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ProjectRepository::class)
 * @ORM\Table(name="projects")
 */
class Project
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=150)
     * @Assert\NotBlank(
     * message="Champ nom vide"
     *)
     * @Assert\Length(
     * max= 50,
     * maxMessage= "Maximum atteint"
     *)
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(
     * message="Champ description vide"
     *)
     * @Assert\Length(
     * max=2500,
     * maxMessage= "Maximum atteint"
     *)
     */
    private $description;

    /**
     * @Assert\NotBlank(
     * message="Champ date de debut Vide"
     *)
     * @var string
     * @Assert\Date
     */
    private $start_date_str;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank
     */
    private $start_date;

    /**
     * @Assert\NotBlank(
     * message="Champ date de fin Vide"
     *)
     * @var string
     * @Assert\Date
     */
    private $end_date_str;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank
     */
    private $end_date;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, mappedBy="projects")
     * @ORM\JoinTable(name="project_user")
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity=Task::class, mappedBy="project")
     */
    private $tasks;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->tasks = new ArrayCollection();
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

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->start_date;

    }

    public function setStartDateStr(string $start_date_str): self
    {
        $this->start_date_str= $start_date_str;
        return $this;
        //Fonction ajouter pour la validation de la date
    }

    public function setStartDate(\DateTimeInterface $start_date): self
    {
        $this->start_date = $start_date;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->end_date;
    }

    public function setEndDateStr(string $end_date_str): self{
        $this->end_date_str = $end_date_str;
        return $this;
    }

    public function setEndDate(\DateTimeInterface $end_date): self
    {
        $this->end_date = $end_date;

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
            $user->addProject($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            $user->removeProject($this);
        }

        return $this;
    }

    /**
     * @return Collection|Task[]
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    public function addTask(Task $task): self
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks[] = $task;
            $task->setProject($this);
        }

        return $this;
    }

    public function removeTask(Task $task): self
    {
        if ($this->tasks->removeElement($task)) {
            // set the owning side to null (unless already changed)
            if ($task->getProject() === $this) {
                $task->setProject(null);
            }
        }

        return $this;
    }
}
