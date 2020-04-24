<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EnchereRepository")
 */
class Enchere
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
    private $date_debut;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_fin;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Produit", inversedBy="encheres")
     * @ORM\JoinColumn(nullable=false)
     */
    private $produit;
	
	/**
     * @ORM\OneToMany(targetEntity="App\Entity\HistoriqueEncheres", mappedBy="enchere", orphanRemoval=true)
     */
    private $historiqueEncheres;

    public function __construct()
    {
        $this->historiqueEncheres = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->date_debut;
    }

    public function setDateDebut(\DateTimeInterface $date_debut): self
    {
        $this->date_debut = $date_debut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->date_fin;
    }

    public function setDateFin(\DateTimeInterface $date_fin): self
    {
        $this->date_fin = $date_fin;

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
            $historiqueEnchere->setEnchere($this);
        }

        return $this;
    }

    public function removeHistoriqueEnchere(HistoriqueEncheres $historiqueEnchere): self
    {
        if ($this->historiqueEncheres->contains($historiqueEnchere)) {
            $this->historiqueEncheres->removeElement($historiqueEnchere);
            // set the owning side to null (unless already changed)
            if ($historiqueEnchere->getEnchere() === $this) {
                $historiqueEnchere->setEnchere(null);
            }
        }

        return $this;
    }

    public function getProduit(): ?Produit
    {
        return $this->produit;
    }

    public function setProduit(?Produit $produit): self
    {
        $this->produit = $produit;

        return $this;
    }
	
	public function __toString()
	{
		return $this->produit->getDescriptif()." (".strval($this->produit->getPrix())."€) 
			du ".$this->date_debut->format('d-m-Y H:i:s')." à ".$this->date_fin->format('d-m-Y H:i:s');
	}
}
