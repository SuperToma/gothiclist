<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Entity
* @ORM\Table(name="artist_version")
*/
class ArtistVersion
{
    /**
     * ArtistVersion constructor.
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @var integer
     */
    private $id;

    /**
     * @var integer
     * @ORM\ManyToOne(targetEntity="App\Entity\Artist")
     * @ORM\JoinColumn(nullable=true)
     */
    private $artist;

    /**
     * @var integer
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return ArtistVersion
     */
    public function setUser(User $user): ArtistVersion
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return ArtistVersion
     */
    public function getArtist(): ArtistVersion
    {
        return $this->artist;
    }

    /**
     * @param Artist $artist
     * @return ArtistVersion
     */
    public function setArtist(Artist $artist): ArtistVersion
    {
        $this->artist = $artist;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return (string)$this->description;
    }

    /**
     * @param string $description
     * @return ArtistVersion
     */
    public function setDescription(string $description): ArtistVersion
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     * @return ArtistVersion
     */
    public function setCreatedAt(\DateTime $createdAt): ArtistVersion
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}