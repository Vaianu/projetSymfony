<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UtilisateurRepository")
 * @UniqueEntity(fields={"email"}, message="Adresse email déja utilisée")
 * @UniqueEntity(fields={"pseudo"}, message="Pseudo déjà utilisé")
 */
class Utilisateur implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
	 * @Assert\NotBlank()
	 * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];
	
	/**
     * @ORM\Column(type="string", length=20, unique=true)
     */
    private $pseudo;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Achat", mappedBy="utilisateur", orphanRemoval=true)
     */
    private $achats;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Role")
     * @ORM\JoinColumn(nullable=false)
     */
    private $role;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\HistoriqueEncheres", mappedBy="utilisateur", orphanRemoval=true)
     */
    private $historiqueEncheres;

    public function __construct()
    {
        $this->achats = new ArrayCollection();
        $this->historiqueEncheres = new ArrayCollection();
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
	
	public function getRole(): ?Role
    {
        return $this->role;
    }

    public function setRole(?Role $role): self
    {
        $this->role = $role;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        //$roles[] = 'ROLE_USER';
		$roles[] = $this->role->getDescription();

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
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    /**
     * @return Collection|Achat[]
     */
    public function getAchats(): Collection
    {
        return $this->achats;
    }

    public function addAchat(Achat $achat): self
    {
        if (!$this->achats->contains($achat)) {
            $this->achats[] = $achat;
            $achat->setUtilisateur($this);
        }

        return $this;
    }

    public function removeAchat(Achat $achat): self
    {
        if ($this->achats->contains($achat)) {
            $this->achats->removeElement($achat);
            // set the owning side to null (unless already changed)
            if ($achat->getUtilisateur() === $this) {
                $achat->setUtilisateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|HistoriqueEncheres[]
     */
    public function getHistoriqueEncheres(): Collection
    {
        return $this->historiqueEncheres;
    }

    public function addHistoriqueEnchere(HistoriqueEncheres $historiqueEnchere): self
    {
        if (!$this->historiqueEncheres->contains($historiqueEnchere)) {
            $this->historiqueEncheres[] = $historiqueEnchere;
            $historiqueEnchere->setUtilisateur($this);
        }

        return $this;
    }

    public function removeHistoriqueEnchere(HistoriqueEncheres $historiqueEnchere): self
    {
        if ($this->historiqueEncheres->contains($historiqueEnchere)) {
            $this->historiqueEncheres->removeElement($historiqueEnchere);
            // set the owning side to null (unless already changed)
            if ($historiqueEnchere->getUtilisateur() === $this) {
                $historiqueEnchere->setUtilisateur(null);
            }
        }

        return $this;
    }
	
	public function __toString()
	{
		return $this->getEmail();
	}
}
