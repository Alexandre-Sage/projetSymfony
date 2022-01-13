<?php

namespace App\Entity;

use App\Repository\TaskRepository;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass=TaskRepository::class)
 * @ORM\Table(name="tasks")
 */
class Task
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=150)
     * @Assert\NotBlank
     * @Assert\Length(
     * max= 150,
     * maxMessage= "Maximum atteint"
     *)
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank
     * @Assert\Length(
     * max= 2500,
     * maxMessage= "Maximum atteint"
     *)
     */
    private $description;

    /**
     * @Assert\NotBlank
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
     * @Assert\NotBlank
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
     * @ORM\ManyToOne(targetEntity=Project::class, inversedBy="tasks")
     * @Assert\NotBlank
     */
    private $project;

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

    public function setEndDateStr(string $end_date_str): self{
        $this->end_date_str = $end_date_str;
        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->end_date;
    }

    public function setEndDate(\DateTimeInterface $end_date): self
    {
        $this->end_date = $end_date;

        return $this;
    }

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): self
    {
        $this->project = $project;

        return $this;
    }
}
