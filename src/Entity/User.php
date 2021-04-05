<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /*
     *  /**
     * @ORM\Column(type="json")
     *
    private $roles = [];
     * */


    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\OneToOne(targetEntity=Entreprise::class, mappedBy="user", cascade={"persist", "remove"})
     */
    private $entreprise;

    /**
     * @ORM\OneToOne(targetEntity=Demandeur::class, mappedBy="user", cascade={"persist", "remove"})
     */
    private $demandeur;

    /**
     * @ORM\ManyToMany(targetEntity=Role::class, inversedBy="users")
     */
    private $roles;

    public function __construct()
    {
        $this->roles = new ArrayCollection();
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

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $roles =Array();// $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';
        foreach ($this->roles as $r)
        {
            $roles[]=$r->getLibelle();
        }

        return array_unique($roles);
    }
    /*
     *  public function setRoles(array $roles): self
        {
            $this->roles = $roles;

            return $this;
        }
     * */

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
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getEntreprise(): ?Entreprise
    {
        return $this->entreprise;
    }

    public function setEntreprise(?Entreprise $entreprise): self
    {
        // unset the owning side of the relation if necessary
        if ($entreprise === null && $this->entreprise !== null) {
            $this->entreprise->setUser(null);
        }

        // set the owning side of the relation if necessary
        if ($entreprise !== null && $entreprise->getUser() !== $this) {
            $entreprise->setUser($this);
        }

        $this->entreprise = $entreprise;

        return $this;
    }

    public function getDemandeur(): ?Demandeur
    {
        return $this->demandeur;
    }

    public function setDemandeur(?Demandeur $demandeur): self
    {
        // unset the owning side of the relation if necessary
        if ($demandeur === null && $this->demandeur !== null) {
            $this->demandeur->setUser(null);
        }

        // set the owning side of the relation if necessary
        if ($demandeur !== null && $demandeur->getUser() !== $this) {
            $demandeur->setUser($this);
        }

        $this->demandeur = $demandeur;

        return $this;
    }

    public function addRole(Role $role): self
    {
        if (!$this->roles->contains($role)) {
            $this->roles[] = $role;
        }

        return $this;
    }

    public function removeRole(Role $role): self
    {
        $this->roles->removeElement($role);

        return $this;
    }
}
