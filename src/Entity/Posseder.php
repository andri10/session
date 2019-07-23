<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PossederRepository")
 */
class Posseder
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantiteUse;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Salle", inversedBy="posseders")
     */
    private $salle;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Ressource", inversedBy="posseders")
     */
    private $ressource;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantiteUse(): ?int
    {
        return $this->quantiteUse;
    }

    public function setQuantiteUse(int $quantiteUse): self
    {
        $this->quantiteUse = $quantiteUse;

        return $this;
    }

    public function getSalle(): ?Salle
    {
        return $this->salle;
    }

    public function setSalle(?Salle $salle): self
    {
        $this->salle = $salle;

        return $this;
    }

    public function getRessource(): ?Ressource
    {
        return $this->ressource;
    }

    public function setRessource(?Ressource $ressource): self
    {
        $this->ressource = $ressource;

        return $this;
    }
}
