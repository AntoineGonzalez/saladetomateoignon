<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\IngredientsRepository")
 */
class Ingredients
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"menuGroup"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"purshase", "menuGroup"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $pic_path;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Product", mappedBy="ingredients")
     */
    private $products;

    /**
     * @ORM\Column(type="integer")
     */
    private $stockQty;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    public function __construct()
    {
        $this->products = new ArrayCollection();
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

    public function getPicPath(): ?string
    {
        return $this->pic_path;
    }

    public function setPicPath(string $pic_path): self
    {
        $this->pic_path = $pic_path;

        return $this;
    }

    /**
     * @return Collection|Product[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->addIngredient($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->contains($product)) {
            $this->products->removeElement($product);
            $product->removeIngredient($this);
        }

        return $this;
    }

    public function getStockQty(): ?int
    {
        return $this->stockQty;
    }

    public function setStockQty(int $stockQty): self
    {
        $this->stockQty = $stockQty;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }
}
