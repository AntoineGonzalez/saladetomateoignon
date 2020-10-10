<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(denormalizationContext={"groups"={"read"}}, normalizationContext={"groups"={"purshasesMenus"}})
 * @ORM\Entity(repositoryClass="App\Repository\PurshaseMenusRepository")
 */
class PurshaseMenus
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"purshasesMenus", "purshase"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Purshase", inversedBy="purshaseMenuses")
     * @Groups({"purshasesMenus"})
     */
    private $purshase;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Menu")
     * @Groups({"read", "purshasesMenus", "purshase"})
     */
    private $formule;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Product")
     * @Groups({"read", "purshasesMenus", "purshase"})
     */
    private $content;

    /**
     * @ORM\Column(type="string", length=1000, nullable=true)
     * @Groups({"read", "purshasesMenus", "purshase"})
     */
    private $customerComment;

    /**
     * @ORM\Column(type="string", length=1000, nullable=true)
     * @Groups({"read", "purshasesMenus", "purshase"})
     */
    private $ingredients;

    public function __construct()
    {
        $this->content = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

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

    public function getFormule(): ?Menu
    {
        return $this->formule;
    }

    public function setFormule(?Menu $formule): self
    {
        $this->formule = $formule;

        return $this;
    }

    /**
     * @return Collection|Product[]
     */
    public function getContent(): Collection
    {
        return $this->content;
    }

    public function addContent(Product $content): self
    {
        if (!$this->content->contains($content)) {
            $this->content[] = $content;
        }

        return $this;
    }

    public function removeContent(Product $content): self
    {
        if ($this->content->contains($content)) {
            $this->content->removeElement($content);
        }

        return $this;
    }


    public function getCustomerComment(): ?string
    {
        return $this->customerComment;
    }

    public function setCustomerComment(?string $customerComment): self
    {
        $this->customerComment = $customerComment;

        return $this;
    }

    public function getIngredients(): ?string
    {
        return $this->ingredients;
    }

    public function setIngredients(?string $ingredients): self
    {
        $this->ingredients = $ingredients;

        return $this;
    }

}
