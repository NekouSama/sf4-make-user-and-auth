<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
    * @Assert\EqualTo(propertyPath="password", message="Your confirm password isn't equal to password")
    */
    public $confirm_password;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

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
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @var string le token qui servira lors de l'oubli de mot de passe
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $resetToken;
 
    /**
     * @return string
     */
    public function getResetToken(): string
    {
        return $this->resetToken;
    }
 
    /**
     * @param string $resetToken
     */
    public function setResetToken(?string $resetToken): void
    {
        $this->resetToken = $resetToken;
    }

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $email;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Messenger", mappedBy="author", orphanRemoval=true)
     */
    private $messengers;

    public function __construct()
    {
        $this->messengers = new ArrayCollection();
    }
 
    /**
     * @return string
     */
    public function getEmail(): string
    {
        return (string) $this->email;
    }
 
    /**
     * @param string $email
     */
    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return Collection|Messenger[]
     */
    public function getMessengers(): Collection
    {
        return $this->messengers;
    }

    public function addMessenger(Messenger $messenger): self
    {
        if (!$this->messengers->contains($messenger)) {
            $this->messengers[] = $messenger;
            $messenger->setAuthor($this);
        }

        return $this;
    }

    public function removeMessenger(Messenger $messenger): self
    {
        if ($this->messengers->contains($messenger)) {
            $this->messengers->removeElement($messenger);
            // set the owning side to null (unless already changed)
            if ($messenger->getAuthor() === $this) {
                $messenger->setAuthor(null);
            }
        }

        return $this;
    }
}
