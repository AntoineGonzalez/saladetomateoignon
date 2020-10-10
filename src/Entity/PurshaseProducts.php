<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PurshaseProductsRepository")
 * @ApiResource(denormalizationContext={"groups"={"read"}}, normalizationContext={"groups"={"purshaseProduct"}})
 */
class PurshaseProducts
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"purshaseProduct", "read"})
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"read", "purshase"})
     */
    private $qty;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Product")
     * @Groups({"read", "purshaseProduct", "purshase"})
     */
    private $product;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Purshase", inversedBy="purshaseProducts")
     */
    private $purshase;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }


    public function getQty(): ?int
    {
        return $this->qty;
    }

    public function setQty(int $qty): self
    {
        $this->qty = $qty;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getPurshase(): ?Purshase
    {
        return $this->purshase;
    }

    public function setPurshase(?Purshase $purshase): self
    {
        $this->purshase = $purshase;

        return $this;
    }
}
