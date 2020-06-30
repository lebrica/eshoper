<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SocialUserRepository")
 */
class SocialUser
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $oauth_type;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $client_id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="social", cascade={"persist"})
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOauthType(): ?string
    {
        return $this->oauth_type;
    }

    public function setOauthType(string $oauth_type): self
    {
        $this->oauth_type = $oauth_type;

        return $this;
    }

    public function getClientId(): ?int
    {
        return $this->client_id;
    }

    public function setClientId(string $client_id): self
    {
        $this->client_id = $client_id;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser_id(User $user): self
    {
        $this->user = $user;

        return $this;
    }

}
