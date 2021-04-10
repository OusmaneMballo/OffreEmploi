<?php

namespace App\Entity;

use App\Repository\DomaineRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DomaineRepository::class)
 */
class Domaine
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $libelle;

    /**
     * @ORM\OneToMany(targetEntity=Demandeur::class, mappedBy="domaine")
     */
    private $demandeurs;

    /**
     * @ORM\ManyToMany(targetEntity=Profile::class, inversedBy="domaines")
     */
    private $profiles;

    /**
     * @ORM\OneToMany(targetEntity=Offre::class, mappedBy="domaines")
     */
    private $offres;

    public function __construct()
    {
        $this->demandeurs = new ArrayCollection();
        $this->profiles = new ArrayCollection();
        $this->offres = new ArrayCollection();
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

    /**
     * @return Collection|Demandeur[]
     */
    public function getDemandeurs(): Collection
    {
        return $this->demandeurs;
    }

    public function addDemandeur(Demandeur $demandeur): self
    {
        if (!$this->demandeurs->contains($demandeur)) {
            $this->demandeurs[] = $demandeur;
            $demandeur->setDomaine($this);
        }

        return $this;
    }

    public function removeDemandeur(Demandeur $demandeur): self
    {
        if ($this->demandeurs->removeElement($demandeur)) {
            // set the owning side to null (unless already changed)
            if ($demandeur->getDomaine() === $this) {
                $demandeur->setDomaine(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Profile[]
     */
    public function getProfiles(): Collection
    {
        return $this->profiles;
    }

    public function addProfile(Profile $profile): self
    {
        if (!$this->profiles->contains($profile)) {
            $this->profiles[] = $profile;
        }

        return $this;
    }

    public function removeProfile(Profile $profile): self
    {
        $this->profiles->removeElement($profile);

        return $this;
    }

    /**
     * @return Collection|Offre[]
     */
    public function getOffres(): Collection
    {
        return $this->offres;
    }

    public function addOffre(Offre $offre): self
    {
        if (!$this->offres->contains($offre)) {
            $this->offres[] = $offre;
            $offre->setDomaines($this);
        }

        return $this;
    }

    public function removeOffre(Offre $offre): self
    {
        if ($this->offres->removeElement($offre)) {
            // set the owning side to null (unless already changed)
            if ($offre->getDomaines() === $this) {
                $offre->setDomaines(null);
            }
        }

        return $this;
    }
}
