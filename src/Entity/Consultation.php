<?php

namespace App\Entity;

use App\Repository\ConsultationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=ConsultationRepository::class)
 */
class Consultation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isAccepted;



    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="consultations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="consultationsM")
     */
    private $userM;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @var string A "Y-m-d H:i:s" formatted value
     */

    private $datehr;



    public function getId(): ?int
    {
        return $this->id;
    }






    public function getIsAccepted(): ?bool
    {
        return $this->isAccepted;
    }

    public function setIsAccepted(?bool $isAccepted): self
    {
        $this->isAccepted = $isAccepted;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
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

    public function getUserM(): ?User
    {
        return $this->userM;
    }

    public function setUserM(?User $userM): self
    {
        $this->userM = $userM;

        return $this;
    }

    public function getDatehr(): ?\DateTimeInterface
    {
        return $this->datehr;
    }

    public function setDatehr(?\DateTimeInterface $datehr): self
    {
        $this->datehr = $datehr;

        return $this;
    }
}
