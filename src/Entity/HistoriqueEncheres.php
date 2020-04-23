<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\HistoriqueEncheresRepository")
 */
class HistoriqueEncheres
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
    private $date_enchere;

    /**
     * @ORM\Column(type="integer")
     */
    private $prix;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Utilisateur", inversedBy="historiqueEncheres")
     * @ORM\JoinColumn(nullable=false)
     */
    private $utilisateur;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Enchere", inversedBy="historiqueEncheres")
     * @ORM\JoinColumn(nullable=false)
     */
    private $enchere;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateEnchere(): ?\DateTimeInterface
    {
        return $this->date_enchere;
    }

    public function setDateEnchere(\DateTimeInterface $date_enchere): self
    {
        $this->date_enchere = $date_enchere;

        return $this;
    }

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(int $prix): self
    {
        $this->prix = $prix;

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

    public function getEnchere(): ?Enchere
    {
        return $this->enchere;
    }

    public function setEnchere(?Enchere $enchere): self
    {
        $this->enchere = $enchere;

        return $this;
    }
}
