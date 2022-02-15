<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="users")
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank(
     * message="Champ email Vide"
     *)
     * @Assert\Email(
     * message="Email incorrecte"
     *)
     * @Assert\Length(
     * max=50,
     * maxMessage="Maximum Atteint"
     *)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank(
     * message="Champ Prénom vide"
     *)
     * @Assert\Length(
     * max=50,
     * maxMessage="Maximum Atteint"
     *)
     */
    private $first_name;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank(
     * message="Champ nom de famille vide"
     *)
     * @Assert\Length(
     * max=50,
     * maxMessage="Maximum Atteint"
     *)
     */
    private $last_name;

    /**
     * @ORM\ManyToMany(targetEntity=Project::class, inversedBy="users")
     * @ORM\JoinTable(name="project_user")
     */
    private $projects;

    public function __construct()
    {
        $this->projects = new ArrayCollection();
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

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): self
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): self
    {
        $this->last_name = $last_name;

        return $this;
    }

    /**
     * @return Collection|Project[]
     */
    public function getProjects(): Collection
    {
        return $this->projects;
    }

    public function addProject(Project $project): self
    {
        if (!$this->projects->contains($project)) {
            $this->projects[] = $project;
        }

        return $this;
    }

    public function removeProject(Project $project): self
    {
        $this->projects->removeElement($project);

        return $this;
    }
}
