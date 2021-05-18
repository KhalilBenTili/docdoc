<?php

namespace App\Entity;

use App\Repository\PaiementRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=PaiementRepository::class)
 */
class Paiement
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("paiement")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("paiement")
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("paiement")
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("paiement")
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("paiement")
     */
    private $email;

    /**
     * @ORM\Column(type="integer")
     * @Groups("paiement")
     */
    private $numero;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("paiement")
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("paiement")
     */
    private $status;

    /**
     * @ORM\Column(type="float")
     * @Groups("paiement")
     */
    private $prix;

    /**
     * @ORM\Column(type="integer")
     * @Groups("paiement")
     */
    private $userid;


    public function getPrix(): ?float
    {
        return $this->prix;
    }


    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }


    public function getId(): ?int
    {
        return $this->id;
    }


    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
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

    public function getNumero(): ?int
    {
        return $this->numero;
    }

    public function setNumero(int $numero): self
    {
        $this->numero = $numero;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }
    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function getUserid(): ?int
    {
        return $this->userid;
    }

    public function setUserid(int $userid): self
    {
        $this->userid = $userid;

        return $this;
    }
}
