<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\NotBlank;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"question","reponse","consultation","post:read"})
     */
    private $id;


    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\Email(message = "L'e-mail '{{ value }}'
    N'est pas valide.")
     *     @Groups({"question","reponse","consultation","post:read"})
     */

    private $email;

    /**
     * @ORM\Column(type="json")
     * @Groups("post:read")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Groups("post:read")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=30)
     *@Assert\NotBlank(message="ce champ est requis")
     * @Groups({"question","reponse","consultation","post:read"})
     */

    private $nom;

    /**
     * @ORM\Column(type="string", length=30)
     *@Assert\NotBlank(message="ce champ est requis")
     * @Groups({"question","reponse","consultation","post:read"})
     */

    private $prenom;

    /**
     * @ORM\Column(type="date")
     * @Groups("post:read")
     */
    private $dnaissance;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"question","reponse","consultation","post:read"})
     */

    private $type;


    /**
     * @ORM\Column(type="string", length=30)
     *@Assert\NotBlank(message="ce champ est requis")
     *@Assert\Length(min = "8",max = "8",minMessage="Votre Numéro doit contenir 8 chiffres ."))
     * @Assert\Regex(pattern="/^[0-9]*$/", message="Doit contenir des chiffres")
     * @Groups({"question","reponse","consultation","post:read"})
     */

    private $numtel;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *@Assert\Length(min = "8",max = "8",minMessage="Votre CIN doit contenir 8 chiffres."))
     * @Assert\Regex(pattern="/^[0-9]*$/", message="Doit contenir des chiffres")
     * @Groups({"question","reponse","consultation","post:read"})
     */
    private $cin;


    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("post:read")
     */
    private $societe;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     * @Groups("post:read")
     */
    private $matricule;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     * @Groups("post:read")
     */
    private $cnam;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     * @Groups("post:read")
     */
    private $cnss;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     * @Groups("post:read")
     */
    private $specialite;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Groups("post:read")
     */
    private $disponabilite;

    /**
     * @ORM\Column(type="string", nullable=true , length=30)
     * @Groups("post:read")
     */
    private $pharmacie;



    /**
     * @ORM\Column(type="string", length=30)
     * @Groups("post:read")
     */
    private $adresse;


    /**
     * @ORM\OneToMany(targetEntity=Reclamation::class, mappedBy="user")
     *
     */
    private $reclamations;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)

     * @Assert\File(mimeTypes={"image/jpeg"})
     * @Groups("post:read")
     */
    private $image;
    /**
     * @ORM\OneToMany(targetEntity=Question::class, mappedBy="user" , cascade={"all"}, orphanRemoval=true )
     */
    private $questions;

    /**
     * @ORM\OneToMany(targetEntity=Reponse::class, mappedBy="user" , cascade={"all"}, orphanRemoval=true )
     */
    private $reponses;


    /**
     * @ORM\OneToMany(targetEntity=Consultation::class, mappedBy="user" , cascade={"all"}, orphanRemoval=true )
     */
    private $consultations;

    /**
     * @ORM\OneToMany(targetEntity=Consultation::class, mappedBy="userM" , cascade={"all"}, orphanRemoval=true)
     */
    private $consultationsM;
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isBlocked;

    /**

     * @ORM\OneToMany(targetEntity=Commentaires::class, mappedBy="User")
     */
    private $commentaires;
    /**
     * @ORM\OneToMany(targetEntity=Rate::class, mappedBy="user")
     */
    private $rates;



    public function __construct()
    {
        $this->reclamations = new ArrayCollection();
        $this->questions = new ArrayCollection();
        $this->reponses = new ArrayCollection();
        $this->consultations = new ArrayCollection();
        $this->consultationsM = new ArrayCollection();
        $this->commentaires = new ArrayCollection();
        $this->rates = new ArrayCollection();
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
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
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

    public function getDnaissance(): ?\DateTimeInterface
    {
        return $this->dnaissance;
    }

    public function setDnaissance(\DateTimeInterface $dnaissance): self
    {
        $this->dnaissance = $dnaissance;

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

    public function getNumtel(): ?string
    {
        return $this->numtel;
    }

    public function setNumtel(string $numtel): self
    {
        $this->numtel = $numtel;

        return $this;
    }

    public function getCin(): ?string
    {
        return $this->cin;
    }

    public function setCin(?string $cin): self
    {
        $this->cin = $cin;

        return $this;
    }




    public function getSociete(): ?string
    {
        return $this->societe;
    }

    public function setSociete(?string $societe): self
    {
        $this->societe = $societe;

        return $this;
    }

    public function getMatricule(): ?string
    {
        return $this->matricule;
    }

    public function setMatricule(?string $matricule): self
    {
        $this->matricule = $matricule;

        return $this;
    }

    public function getCnam(): ?string
    {
        return $this->cnam;
    }

    public function setCnam(?string $cnam): self
    {
        $this->cnam = $cnam;

        return $this;
    }

    public function getCnss(): ?string
    {
        return $this->cnss;
    }

    public function setCnss(?string $cnss): self
    {
        $this->cnss = $cnss;

        return $this;
    }

    public function getSpecialite(): ?string
    {
        return $this->specialite;
    }

    public function setSpecialite(?string $specialite): self
    {
        $this->specialite = $specialite;

        return $this;
    }

    public function getDisponabilite(): ?\DateTimeInterface
    {
        return $this->disponabilite;
    }

    public function setDisponabilite(\DateTimeInterface $disponabilite): self
    {
        $this->disponabilite = $disponabilite;

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



    /**
     * @return Collection|Reclamation[]
     */
    public function getReclamations(): Collection
    {
        return $this->reclamations;
    }

    public function addReclamation(Reclamation $reclamation): self
    {
        if (!$this->reclamations->contains($reclamation)) {
            $this->reclamations[] = $reclamation;
            $reclamation->setUser($this);
        }

        return $this;
    }

    public function removeReclamation(Reclamation $reclamation): self
    {
        if ($this->reclamations->removeElement($reclamation)) {
            // set the owning side to null (unless already changed)
            if ($reclamation->getUser() === $this) {
                $reclamation->setUser(null);
            }
        }

        return $this;
    }
    /**
     * @return Collection|Question[]
     */
    public function getQuestions(): Collection
    {
        return $this->questions;
    }

    public function addQuestion(Question $question): self
    {
        if (!$this->questions->contains($question)) {
            $this->questions[] = $question;
            $question->setUser($this);
        }

        return $this;
    }

    public function removeQuestion(Question $question): self
    {
        if ($this->questions->removeElement($question)) {
            // set the owning side to null (unless already changed)
            if ($question->getUser() === $this) {
                $question->setUser(null);
            }
        }

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
            $reponse->setUser($this);
        }

        return $this;
    }

    public function removeReponse(Reponse $reponse): self
    {
        if ($this->reponses->removeElement($reponse)) {
            // set the owning side to null (unless already changed)
            if ($reponse->getUser() === $this) {
                $reponse->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Consultation[]
     */
    public function getConsultation(): Collection
    {
        return $this->consultation;
    }

    public function addConsultation(Consultation $consultation): self
    {
        if (!$this->consultation->contains($consultation)) {
            $this->consultation[] = $consultation;
        }

        return $this;
    }

    public function removeConsultation(Consultation $consultation): self
    {
        $this->consultation->removeElement($consultation);

        return $this;
    }

    /**
     * @return Collection|Consultation[]
     */
    public function getConsultations(): Collection
    {
        return $this->consultations;
    }

    /**
     * @return Collection|Consultation[]
     */
    public function getConsultationsM(): Collection
    {
        return $this->consultationsM;
    }

    public function addConsultationsM(Consultation $consultationsM): self
    {
        if (!$this->consultationsM->contains($consultationsM)) {
            $this->consultationsM[] = $consultationsM;
            $consultationsM->setUserM($this);
        }

        return $this;
    }

    public function removeConsultationsM(Consultation $consultationsM): self
    {
        if ($this->consultationsM->removeElement($consultationsM)) {
            // set the owning side to null (unless already changed)
            if ($consultationsM->getUserM() === $this) {
                $consultationsM->setUserM(null);
            }
        }

        return $this;
    }

    public function getImage()
    {
        return $this->image;
    }


    public function setImage($image)
    {
        $this->image = $image;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPharmacie()
    {
        return $this->pharmacie;
    }

    /**
     * @param mixed $pharmacie
     */
    public function setPharmacie($pharmacie): void
    {
        $this->pharmacie = $pharmacie;
    }
    public function getIsBlocked(): ?bool
    {
        return $this->isBlocked;
    }

    public function setIsBlocked(?bool $isBlocked): self
    {
        $this->isBlocked = $isBlocked;

        return $this;
    }

    /**
     * @return Collection|Commentaires[]
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaires $commentaire): self
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires[] = $commentaire;
            $commentaire->setUser($this);
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
            $rate->setUser($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaires $commentaire): self
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getUser() === $this) {
                $commentaire->setUser(null);
            }
        }

        return $this;
    }

    public function removeRate(Rate $rate): self
    {
        if ($this->rates->removeElement($rate)) {
            // set the owning side to null (unless already changed)
            if ($rate->getUser() === $this) {
                $rate->setUser(null);
            }
        }

        return $this;
    }


}
