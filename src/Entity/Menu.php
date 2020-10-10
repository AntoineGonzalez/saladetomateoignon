<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(normalizationContext={"groups"={"menuGroup"}})
 * @ORM\Entity(repositoryClass="App\Repository\MenuRepository")
 */
class Menu
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"purshasesMenus", "purshase"})
     */
    private $id;

    /**
     * @Groups({"purshase", "menuGroup"})
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="float")
     * @Groups({"menuGroup"})
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=255)
      * @Groups({"menuGroup"})
     */
    private $picture;

    /**
     * @ORM\Column(type="string", length=255)
      * @Groups({"menuGroup"})
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Product")
      * @Groups({"menuGroup"})
     */
    private $sandwich;

    /**
     * @ORM\Column(type="string", length=1000, nullable=true)
      * @Groups({"menuGroup"})
     */
    private $description;



    public function __construct()
    {
        $this->Products = new ArrayCollection();
        $this->purshases = new ArrayCollection();
        $this->product = new ArrayCollection();

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }



    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getSandwich(): ?Product
    {
        return $this->sandwich;
    }

    public function setSandwich(?Product $sandwich): self
    {
        $this->sandwich = $sandwich;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }



}
