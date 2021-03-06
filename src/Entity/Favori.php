<?php

namespace App\Entity;

use App\Repository\FavoriRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FavoriRepository::class)
 */
class Favori
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=0, nullable=true)
     */
    private $nbr_annee_experience;

    /**
     * @ORM\ManyToOne(targetEntity=Demandeur::class, inversedBy="favoris")
     * @ORM\JoinColumn(nullable=false)
     */
    private $demandeur;

    /**
     * @ORM\ManyToOne(targetEntity=Domaine::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $domaine;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNbrAnneeExperience(): ?string
    {
        return $this->nbr_annee_experience;
    }

    public function setNbrAnneeExperience(?string $nbr_annee_experience): self
    {
        $this->nbr_annee_experience = $nbr_annee_experience;

        return $this;
    }

    public function getDemandeur(): ?Demandeur
    {
        return $this->demandeur;
    }

    public function setDemandeur(?Demandeur $demandeur): self
    {
        $this->demandeur = $demandeur;

        return $this;
    }

    public function getDomaine(): ?Domaine
    {
        return $this->domaine;
    }

    public function setDomaine(?Domaine $domaine): self
    {
        $this->domaine = $domaine;

        return $this;
    }
}
