<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Entity
* @ORM\Table(name="song", indexes={@ORM\Index(name="title_idx", columns={"title"})})
*/
class Song
{
    /**
     * Song constructor.
     */
    public function __construct()
    {
        $this->validated = false;
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
     * @ORM\OneToOne(targetEntity="App\Entity\Master")
     * @ORM\Column(type="integer", nullable=false)
     */
    private $idMaster;

    /**
     * @var Release
     * @ORM\ManyToOne(targetEntity="App\Entity\Release")
     */
    private $release;

    /**
     * @var Artist
     * @ORM\ManyToOne(targetEntity="App\Entity\Artist")
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @var string
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $validated;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;


    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getIdMaster(): int
    {
        return $this->idMaster;
    }

    /**
     * @param int $idMaster
     * @return Song
     */
    public function setIdMaster(int $idMaster): Song
    {
        $this->idMaster = $idMaster;

        return $this;
    }

    /**
     * @return Release
     */
    public function getRelease(): Release
    {
        return $this->release;
    }

    /**
     * @param Release $release
     * @return Song
     */
    public function setRelease(Release $release): Song
    {
        $this->release = $release;

        return $this;
    }

    /**
     * @return Artist
     */
    public function getArtist(): Artist
    {
        return $this->artist;
    }

    /**
     * @param Artist $artist
     * @return Song
     */
    public function setArtist(Artist $artist): Song
    {
        $this->artist = $artist;

        return $this;
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
     * @return Song
     */
    public function setUser(User $user): Song
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return Song
     */
    public function setTitle(string $title): Song
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return Song
     */
    public function setDescription(string $description): Song
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getValidated()
    {
        return $this->validated;
    }

    /**
     * @param bool $validated
     * @return $this
     */
    public function setValidated(bool $validated)
    {
        $this->validated = $validated;

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
     * @return Song
     */
    public function setCreatedAt(\DateTime $createdAt): Song
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     * @return Song
     */
    public function setUpdatedAt(\DateTime $updatedAt): Song
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}