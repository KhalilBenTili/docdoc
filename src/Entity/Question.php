<?php

namespace App\Entity;

use App\Repository\QuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;


/**

 * @ORM\Entity(repositoryClass=QuestionRepository::class)
 * @Vich\Uploadable
 */
class Question
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=60)
     * @Assert\NotBlank
     *  @Assert\Length(min=3)
     */
    private $titre;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Length(min=10)
     */

    private $symptomes;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\Positive
     * @Assert\Length(max=8)
     */
    private $taille;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\Positive
     * @Assert\Length(max=8)
     */
    private $poids;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isTreated;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isAntMed;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isNameShown;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isAnswered;

    /**
     * @ORM\OneToMany(targetEntity=Reponse::class, mappedBy="question", cascade={"all"}, orphanRemoval=true )
     */
    private $reponses;

    /**
     * @ORM\ManyToOne(targetEntity=CategorieMedicale::class, inversedBy="questions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $categorieMedicale;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="questions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;






    public function __construct()
    {
        $this->reponses = new ArrayCollection();
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getSymptomes(): ?string
    {
        return $this->symptomes;
    }

    public function setSymptomes(string $symptomes): self
    {
        $this->symptomes = $symptomes;

        return $this;
    }

    public function getTaille(): ?int
    {
        return $this->taille;
    }

    public function setTaille(?int $taille): self
    {
        $this->taille = $taille;

        return $this;
    }

    public function getPoids(): ?int
    {
        return $this->poids;
    }

    public function setPoids(?int $poids): self
    {
        $this->poids = $poids;

        return $this;
    }

    public function getIsTreated(): ?bool
    {
        return $this->isTreated;
    }

    public function setIsTreated(bool $isTreated): self
    {
        $this->isTreated = $isTreated;

        return $this;
    }

    public function getIsAntMed(): ?bool
    {
        return $this->isAntMed;
    }

    public function setIsAntMed(bool $isAntMed): self
    {
        $this->isAntMed = $isAntMed;

        return $this;
    }

    public function getIsNameShown(): ?bool
    {
        return $this->isNameShown;
    }

    public function setIsNameShown(bool $isNameShown): self
    {
        $this->isNameShown = $isNameShown;

        return $this;
    }

    public function getIsAnswered(): ?bool
    {
        return $this->isAnswered;
    }

    public function setIsAnswered(bool $isAnswered): self
    {
        $this->isAnswered = $isAnswered;

        return $this;
    }

    /**
     * @return Collection|Reponse[]
     */
    public function getReponses(): Collection
    {
        return $this->reponses;
    }

    public function addReponse(Reponse $reponse): self
    {
        if (!$this->reponses->contains($reponse)) {
            $this->reponses[] = $reponse;
            $reponse->setQuestion($this);
        }

        return $this;
    }

    public function removeReponse(Reponse $reponse): self
    {
        if ($this->reponses->removeElement($reponse)) {
            // set the owning side to null (unless already changed)
            if ($reponse->getQuestion() === $this) {
                $reponse->setQuestion(null);
            }
        }

        return $this;
    }

    public function getCategorieMedicale(): ?CategorieMedicale
    {
        return $this->categorieMedicale;
    }

    public function setCategorieMedicale(?CategorieMedicale $categorieMedicale): self
    {
        $this->categorieMedicale = $categorieMedicale;

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




}
