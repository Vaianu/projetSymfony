<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AchatRepository")
 */
class Achat
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_achat;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Utilisateur", inversedBy="achats")
     * @ORM\JoinColumn(nullable=false)
     */
    private $utilisateur;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\PackJetons", inversedBy="achats")
     * @ORM\JoinColumn(nullable=false)
     */
    private $packJetons;
	
	public function __construct()
	{
		date_default_timezone_set('Europe/Paris');
		$this->date_achat = new \DateTime('now');
	}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateAchat(): ?\DateTimeInterface
    {
        return $this->date_achat;
    }

    public function setDateAchat(\DateTimeInterface $date_achat): self
    {
        $this->date_achat = $date_achat;

        return $this;
    }

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateur $utilisateur): self
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    public function getPackJetons(): ?PackJetons
    {
        return $this->packJetons;
    }

    public function setPackJetons(?PackJetons $packJetons): self
    {
        $this->packJetons = $packJetons;

        return $this;
    }
	
	public function __toString()
	{
		return $this->date_achat->format('d-m-Y H:i:s');
	}
}
