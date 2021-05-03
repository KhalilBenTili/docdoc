<?php

namespace App\Entity;

use App\Repository\ServiceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ServiceRepository::class)
 */
class Service
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=60)*
     * @Assert\Length(
     *      min = 10,
     *      max = 60,
     *      minMessage = " llibelle ne peut pas comporter mois de {{limit}} caractéres ",
     *      maxMessage = "libelle ne peut pas comporter plus de {{limit}} caractères",
     *      allowEmptyString = false
     * )
     */
    private $libelle;

    /**
     * @ORM\ManyToOne(targetEntity=CategorieService::class, inversedBy="services")
     * @ORM\JoinColumn(nullable=false)
     */
    private $categorie;



    /**
     * @ORM\Column(type="float", nullable=true)
     * @Assert\PositiveOrZero(
     *     message="prix doit étre superieur ou égale 0"
     * )
     *
     */


    private $prix;

    /**
     * @ORM\Column(type="string", length=2000)
     *  @Assert\Length(
     *      min = 20,
     *      max = 2000,
     *      minMessage = " déscription ne peut pas comporter mois de {{limit}} caractéres ",
     *      maxMessage = "déscription ne peut pas comporter plus de {{limit}} caractères",
     *      allowEmptyString = false
     * )
     */
    private $description;

    /**
     * @ORM\Column(type="boolean")
     */
    private $disponibilite;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $stars = [];

    /**
     * @ORM\OneToMany(targetEntity=FourniseurService::class, mappedBy="service")
     */
    private $fournisseurs;

    /**
     * @ORM\OneToMany(targetEntity=Rate::class, mappedBy="service")
     */
    private $rates;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $avgrating;

    public function __construct()
    {
        $this->fournisseurs = new ArrayCollection();
        $this->stars= array(0,0,0,0,0);
        $this->rates = new ArrayCollection();

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getCategorie(): ?CategorieService
    {
        return $this->categorie;
    }

    public function setCategorie(?CategorieService $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }



    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(?float $prix): self
    {
        $this->prix = $prix;

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

    public function getDisponibilite(): ?bool
    {
        return $this->disponibilite;
    }

    public function setDisponibilite(bool $disponibilite): self
    {
        $this->disponibilite = $disponibilite;

        return $this;
    }

    public function getStars(): ?array
    {
        return $this->stars;
    }

    public function setStars(?array $stars): self
    {
        $this->stars = $stars;

        return $this;
    }

    /**
     * @return Collection|fourniseurservice[]
     */
    public function getFournisseurs(): Collection
    {
        return $this->fournisseurs;
    }

    public function addFournisseur(fourniseurservice $fournisseur): self
    {
        if (!$this->fournisseurs->contains($fournisseur)) {
            $this->fournisseurs[] = $fournisseur;
            $fournisseur->setService($this);
        }

        return $this;
    }

    public function removeFournisseur(fourniseurservice $fournisseur): self
    {
        if ($this->fournisseurs->removeElement($fournisseur)) {
            // set the owning side to null (unless already changed)
            if ($fournisseur->getService() === $this) {
                $fournisseur->setService(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Rate[]
     */
    public function getRates(): Collection
    {
        return $this->rates;
    }

    public function addRate(Rate $rate): self
    {
        if (!$this->rates->contains($rate)) {
            $this->rates[] = $rate;
            $rate->setService($this);
        }

        return $this;
    }

    public function removeRate(Rate $rate): self
    {
        if ($this->rates->removeElement($rate)) {
            // set the owning side to null (unless already changed)
            if ($rate->getService() === $this) {
                $rate->setService(null);
            }
        }

        return $this;
    }

    public function getAvgrating(): ?float
    {
        return $this->avgrating;
    }

    public function setAvgrating(?float $avgrating): self
    {
        $this->avgrating = $avgrating;

        return $this;
    }
}
