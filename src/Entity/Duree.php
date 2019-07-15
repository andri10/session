<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DureeRepository")
 */
class Duree
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
    private $NbJour;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Module", inversedBy="duree")
     */
    private $module;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Formation", inversedBy="duree")
     */
    private $formation;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNbJour(): ?int
    {
        return $this->NbJour;
    }

    public function setNbJour(int $NbJour): self
    {
        $this->NbJour = $NbJour;

        return $this;
    }

    public function getModule(): ?Module
    {
        return $this->module;
    }

    public function setModule(?Module $module): self
    {
        $this->module = $module;

        return $this;
    }

    public function getFormation(): ?Formation
    {
        return $this->formation;
    }

    public function setFormation(?Formation $formation): self
    {
        $this->formation = $formation;

        return $this;
    }
}
