<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\PushTokensRepository")
 */
class PushTokens
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=500)
     */
    private $tokenStr;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTokenStr(): ?string
    {
        return $this->tokenStr;
    }

    public function setTokenStr(string $token): self
    {
        $this->tokenStr = $token;

        return $this;
    }
}
