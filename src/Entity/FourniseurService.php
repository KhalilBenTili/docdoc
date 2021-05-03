<?php

namespace App\Entity;

use App\Repository\FourniseurServiceRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=FourniseurServiceRepository::class)
 */
class FourniseurService
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=70)
     *  @Assert\Length(
     *      min = 10,
     *      max = 30,
     *      minMessage = "the fournisseur must be at least {{ limit }} characters long",
     *      maxMessage = "the fournisseur cannot be longer than {{ limit }} characters",
     *      allowEmptyString = false
     * )
     */
    private $fourniseur;

    /**
     * @ORM\Column(type="string", length=255)
     *    @Assert\Email(
     *     message = "The contact '{{ value }}' is not a valid email."
     * )
     */
    private $contact;

    /**
     * @ORM\Column(type="string", length=20)
     * @Assert\Regex(
     *     pattern="/^\(0\)[0-9]*$/",
     *     match=false,
     *     message="Your number cannot contain a letters"
     * )
     * * @Assert\Length(
     *      min = 8,
     *      max = 20,
     *      minMessage = "phone number must be at least {{ limit }} number long",
     *      maxMessage = "phone number cannot be longer than {{ limit }} numbers",
     *      allowEmptyString = false
     * )
     */
    private $numero;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $gouvernorat;




    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $maplocation;

    /**
     * @ORM\ManyToOne(targetEntity=Service::class, inversedBy="fournisseurs")
     */
    private $service;




    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFourniseur(): ?string
    {
        return $this->fourniseur;
    }

    public function setFourniseur(string $fourniseur): self
    {
        $this->fourniseur = $fourniseur;

        return $this;
    }

    public function getContact(): ?string
    {
        return $this->contact;
    }

    public function setContact(string $contact): self
    {
        $this->contact = $contact;

        return $this;
    }

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(string $numero): self
    {
        $this->numero = $numero;

        return $this;
    }

    public function getGouvernorat(): ?string
    {
        return $this->gouvernorat;
    }

    public function setGouvernorat(string $gouvernorat): self
    {
        $this->gouvernorat = $gouvernorat;

        return $this;
    }

    public function getDelegation(): ?string
    {
        return $this->delegation;
    }

    public function setDelegation(string $delegation): self
    {
        $this->delegation = $delegation;

        return $this;
    }


    public function getMaplocation(): ?string
    {
        return $this->maplocation;
    }

    public function setMaplocation(?string $maplocation): self
    {
        $this->maplocation = $maplocation;

        return $this;
    }

    public function getService(): ?Service
    {
        return $this->service;
    }

    public function setService(?Service $service): self
    {
        $this->service = $service;

        return $this;
    }




}
