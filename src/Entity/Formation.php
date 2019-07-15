<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FormationRepository")
 */
class Formation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Intitule;

    /**
     * @ORM\Column(type="text")
     */
    private $Presentation;

    /**
     * @ORM\Column(type="integer")
     */
    private $NbPlace;

    /**
     * @ORM\Column(type="datetime")
     */
    private $DateDebut;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Ressource", mappedBy="ressources")
     */
    private $ressources;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Stagiaire", mappedBy="stagiaires")
     */
    private $stagiaires;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Duree", mappedBy="formation")
     */
    private $duree;

    public function __construct()
    {
        $this->ressources = new ArrayCollection();
        $this->stagiaires = new ArrayCollection();
        $this->duree = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIntitule(): ?string
    {
        return $this->Intitule;
    }

    public function setIntitule(string $Intitule): self
    {
        $this->Intitule = $Intitule;

        return $this;
    }

    public function getPresentation(): ?string
    {
        return $this->Presentation;
    }

    public function setPresentation(string $Presentation): self
    {
        $this->Presentation = $Presentation;

        return $this;
    }

    public function getNbPlace(): ?int
    {
        return $this->NbPlace;
    }

    public function setNbPlace(int $NbPlace): self
    {
        $this->NbPlace = $NbPlace;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->DateDebut;
    }

    public function setDateDebut(\DateTimeInterface $DateDebut): self
    {
        $this->DateDebut = $DateDebut;

        return $this;
    }

    /**
     * @return Collection|Ressource[]
     */
    public function getRessources(): Collection
    {
        return $this->ressources;
    }

    public function addRessource(Ressource $ressource): self
    {
        if (!$this->ressources->contains($ressource)) {
            $this->ressources[] = $ressource;
            $ressource->addRessource($this);
        }

        return $this;
    }

    public function removeRessource(Ressource $ressource): self
    {
        if ($this->ressources->contains($ressource)) {
            $this->ressources->removeElement($ressource);
            $ressource->removeRessource($this);
        }

        return $this;
    }

    /**
     * @return Collection|Stagiaire[]
     */
    public function getStagiaires(): Collection
    {
        return $this->stagiaires;
    }

    public function addStagiaire(Stagiaire $stagiaire): self
    {
        if (!$this->stagiaires->contains($stagiaire)) {
            $this->stagiaires[] = $stagiaire;
            $stagiaire->addStagiaire($this);
        }

        return $this;
    }

    public function removeStagiaire(Stagiaire $stagiaire): self
    {
        if ($this->stagiaires->contains($stagiaire)) {
            $this->stagiaires->removeElement($stagiaire);
            $stagiaire->removeStagiaire($this);
        }

        return $this;
    }

    /**
     * @return Collection|Duree[]
     */
    public function getDuree(): Collection
    {
        return $this->duree;
    }

    public function addDuree(Duree $duree): self
    {
        if (!$this->duree->contains($duree)) {
            $this->duree[] = $duree;
            $duree->setFormation($this);
        }

        return $this;
    }

    public function removeDuree(Duree $duree): self
    {
        if ($this->duree->contains($duree)) {
            $this->duree->removeElement($duree);
            // set the owning side to null (unless already changed)
            if ($duree->getFormation() === $this) {
                $duree->setFormation(null);
            }
        }

        return $this;
    }
}
