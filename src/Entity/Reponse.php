<?php

namespace App\Entity;

use App\Repository\ReponseRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Entity(repositoryClass=ReponseRepository::class)
 */
class Reponse
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"reponse","user"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"reponse","user"})
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=Question::class, inversedBy="reponses")
     * @ORM\JoinColumn(nullable=false)
     *  @Groups({"reponse","user"})
     */
    private $question;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="reponses")
     * @ORM\JoinColumn(nullable=false)
     * @Groups("reponse")
     */
    private $user;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"reponse","user"})
     */
    private $isBad;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getQuestion(): ?Question
    {
        return $this->question;
    }

    public function setQuestion(?Question $question): self
    {
        $this->question = $question;

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

    public function getIsBad(): ?bool
    {
        return $this->isBad;
    }

    public function setIsBad(bool $isBad): self
    {
        $this->isBad = $isBad;

        return $this;
    }
}
