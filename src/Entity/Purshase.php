<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Core\Annotation\ApiFilter;

/**
 * @ApiResource(denormalizationContext={"groups"={"read"}}, normalizationContext={"groups"={"purshase"}, "enable_max_depth"=true}, attributes={"filters"={"purshase.date_filter"}})
 * @ORM\Entity(repositoryClass="App\Repository\PurshaseRepository")
 * @ApiFilter(DateFilter::class, properties={"deliveryHour"})
 */
class Purshase
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"read" , "purshase"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="purshases")
     * @Groups({"read" , "purshase"})
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read" , "purshase"})
     */
    private $date;


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PurshaseMenus", mappedBy="purshase", cascade={"persist"})
     *
     * @Groups({"read" , "purshase"})
     */
    private $purshaseMenuses;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read" , "purshase"})
     */
    private $status;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PurshaseProducts", mappedBy="purshase", cascade={"persist"})
     * @Groups({"read" , "purshase"})
     */
    private $purshaseProducts;

    /**
    * @Groups({"read" , "purshase"})
     * @ORM\Column(type="float")
     */
    private $total;

    /**
    * @Groups({"read" , "purshase"})
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $deliveryHour;

    /**
    * @Groups({"read" , "purshase"})
     * @ORM\Column(type="integer", nullable=true)
     */
    private $trustScore;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"read" , "purshase"})
     */
    private $paid;


    public function __construct()
    {
        $this->purshaseMenuses = new ArrayCollection();
        $this->purshaseProducts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getDate(): ?string
    {
        return $this->date;
    }

    public function setDate(string $date): self
    {
        $this->date = $date;

        return $this;
    }



    /**
     * @return Collection|PurshaseMenus[]
     */
    public function getPurshaseMenuses(): Collection
    {
        return $this->purshaseMenuses;
    }

    public function addPurshaseMenus(PurshaseMenus $purshaseMenus): self
    {
        if (!$this->purshaseMenuses->contains($purshaseMenus)) {
            $this->purshaseMenuses[] = $purshaseMenus;
            $purshaseMenus->setPurshase($this);
        }

        return $this;
    }

    public function removePurshaseMenus(PurshaseMenus $purshaseMenus): self
    {
        if ($this->purshaseMenuses->contains($purshaseMenus)) {
            $this->purshaseMenuses->removeElement($purshaseMenus);
            // set the owning side to null (unless already changed)
            if ($purshaseMenus->getPurshase() === $this) {
                $purshaseMenus->setPurshase(null);
            }
        }

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getTotalAmount(): ?float
    {
        $amount = 0;
        // 1st step add the price of additional product.

        foreach( $this->purshaseProducts as $purshasedProduct ){
            $amount += $purshasedProduct->getProduct()->getPrice();
        }

        foreach( $this->purshaseMenuses as $purshaseMenu){
            $amount += $purshaseMenu->getFormule()->getPrice();
        }

        return $amount;
    }

    /**
     * @return Collection|PurshaseProducts[]
     */
    public function getPurshaseProducts(): Collection
    {
        return $this->purshaseProducts;
    }

    public function addPurshaseProduct(PurshaseProducts $purshaseProduct): self
    {
        if (!$this->purshaseProducts->contains($purshaseProduct)) {
            $this->purshaseProducts[] = $purshaseProduct;
            $purshaseProduct->setPurshase($this);
        }

        return $this;
    }

    public function removePurshaseProduct(PurshaseProducts $purshaseProduct): self
    {
        if ($this->purshaseProducts->contains($purshaseProduct)) {
            $this->purshaseProducts->removeElement($purshaseProduct);
            // set the owning side to null (unless already changed)
            if ($purshaseProduct->getPurshase() === $this) {
                $purshaseProduct->setPurshase(null);
            }
        }

        return $this;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(float $total): self
    {
        $this->total = $total;

        return $this;
    }

    public function getDeliveryHour(): ?\DateTimeInterface
    {
        return $this->deliveryHour;
    }

    public function setDeliveryHour(?\DateTimeInterface $deliveryHour): self
    {
        $this->deliveryHour = $deliveryHour;

        return $this;
    }

    public function getTrustScore(): ?int
    {
        return $this->trustScore;
    }

    public function setTrustScore(?int $trustScore): self
    {
        $this->trustScore = $trustScore;

        return $this;
    }

    public function getPaid(): ?bool
    {
        return $this->paid;
    }

    public function setPaid(?bool $paid): self
    {
        $this->paid = $paid;

        return $this;
    }
}
