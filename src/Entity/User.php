<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"purshase"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Groups({"purshase"})
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Assert\Length(min=8, minMessage="Le mot de passe est trop court")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"purshase"})
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"purshase"})
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=5)
     * @Assert\Length(max=5, min=5, exactMessage="Le code postal est incorrect")
     */
    private $cp;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $city;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comments", mappedBy="user")
     */
    private $comments;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Purshase", mappedBy="user")
     * @Groups({"purshase"})
     */
    private $purshases;

    /**
     * @ORM\Column(type="string", length=10)
     * @Groups({"purshase"})
     */
    private $phone;

    /**
     * @ORM\Column(type="integer")
     */
    private $fidelityPoint;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"purshase"})
     */
    private $DiscordID;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->purshases = new ArrayCollection();
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

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getCp(): ?string
    {
        return $this->cp;
    }

    public function setCp(string $cp): self
    {
        $this->cp = $cp;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return Collection|Comments[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comments $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setUser($this);
        }

        return $this;
    }

    public function removeComment(Comments $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getUser() === $this) {
                $comment->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Purshase[]
     */
    public function getPurshases(): Collection
    {
        return $this->purshases;
    }

    public function addPurshase(Purshase $purshase): self
    {
        if (!$this->purshases->contains($purshase)) {
            $this->purshases[] = $purshase;
            $purshase->setUser($this);
        }

        return $this;
    }

    public function removePurshase(Purshase $purshase): self
    {
        if ($this->purshases->contains($purshase)) {
            $this->purshases->removeElement($purshase);
            // set the owning side to null (unless already changed)
            if ($purshase->getUser() === $this) {
                $purshase->setUser(null);
            }
        }

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getFidelityPoint(): ?int
    {
        return $this->fidelityPoint;
    }

    public function setFidelityPoint(int $fidelityPoint): self
    {
        $this->fidelityPoint = $fidelityPoint;

        return $this;
    }

    public function getDiscordID(): ?string
    {
        return $this->DiscordID;
    }

    public function setDiscordID(?string $DiscordID): self
    {
        $this->DiscordID = $DiscordID;

        return $this;
    }
}
