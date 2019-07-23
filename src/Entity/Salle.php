<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SalleRepository")
 */
class Salle
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=25)
     */
    private $libelle;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbPlaceMax;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Formation", mappedBy="salle")
     */
    private $formations;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Posseder", mappedBy="salle")
     */
    private $posseders;

    public function __construct()
    {
        $this->formations = new ArrayCollection();
        $this->posseders = new ArrayCollection();
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

    public function getNbPlaceMax(): ?int
    {
        return $this->nbPlaceMax;
    }

    public function setNbPlaceMax(int $nbPlaceMax): self
    {
        $this->nbPlaceMax = $nbPlaceMax;

        return $this;
    }

    /**
     * @return Collection|Formation[]
     */
    public function getFormations(): Collection
    {
        return $this->formations;
    }

    public function addFormation(Formation $formation): self
    {
        if (!$this->formations->contains($formation)) {
            $this->formations[] = $formation;
            $formation->setSalle($this);
        }

        return $this;
    }

    public function removeFormation(Formation $formation): self
    {
        if ($this->formations->contains($formation)) {
            $this->formations->removeElement($formation);
            // set the owning side to null (unless already changed)
            if ($formation->getSalle() === $this) {
                $formation->setSalle(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Posseder[]
     */
    public function getPosseders(): Collection
    {
        return $this->posseders;
    }

    public function addPosseder(Posseder $posseder): self
    {
        if (!$this->posseders->contains($posseder)) {
            $this->posseders[] = $posseder;
            $posseder->setSalle($this);
        }

        return $this;
    }

    public function removePosseder(Posseder $posseder): self
    {
        if ($this->posseders->contains($posseder)) {
            $this->posseders->removeElement($posseder);
            // set the owning side to null (unless already changed)
            if ($posseder->getSalle() === $this) {
                $posseder->setSalle(null);
            }
        }

        return $this;
    }
}
