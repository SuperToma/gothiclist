<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SongRepository")
 * @ORM\Table(name="song", indexes={@ORM\Index(name="title_idx", columns={"title"})})
 */
class Song
{
    const MP3_DIR = '../mp3/';

    /**
     * Song constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->validated = false;
        $this->createdAt = new \DateTime();
    }

    /**
     * @return string
     */
    protected function getCoversDirectory()
    {
        return $this->coversDirectory;
    }

    /**
     * @return string
     */
    protected function getMp3Directory()
    {
        return $this->mp3Directory;
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
     * @ORM\OneToMany(targetEntity="App\Entity\VoteSong", mappedBy="song")
     */
    private $votes;

    /**
     * @var string
     * @ORM\Column(type="string", length=25, nullable=true)
     */
    private $youtubeId;

    /**
     * @var string
     * @ORM\Column(type="string", length=25, nullable=true)
     */
    private $spotifyId;

    /**
     * @var string
     * @ORM\Column(type="string", length=25, nullable=true)
     */
    private $dailymotionId;

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
     * @param int $id
     * @return Song
     */
    public function setId(int $id): Song
    {
        $this->id = $id;

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
     * @return Collection
     */
    public function getVotes(): Collection
    {
        return $this->votes;
    }

    /**
     * @param string $votes
     * @return Song
     */
    public function setVotes(string $votes): Song
    {
        $this->votes = $votes;

        return $this;
    }

    /**
     * @return string
     */
    public function getYoutubeId()
    {
        return $this->youtubeId;
    }

    /**
     * @param string $youtubeId
     * @return $this
     */
    public function setYoutubeId(string $youtubeId)
    {
        $this->youtubeId = $youtubeId;

        return $this;
    }

    /**
     * @return string
     */
    public function getSpotifyId()
    {
        return $this->spotifyId;
    }

    /**
     * @param string $spotifyId
     * @return $this
     */
    public function setSpotifyId(string $spotifyId)
    {
        $this->spotifyId = $spotifyId;

        return $this;
    }

    /**
     * @return string
     */
    public function getDailymotionId()
    {
        return $this->dailymotionId;
    }

    /**
     * @param string $dailymotionId
     * @return $this
     */
    public function setDailymotionId(string $dailymotionId)
    {
        $this->dailymotionId = $dailymotionId;

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

    /**
     * @return string
     */
    public function getSlug()
    {
        $slugify = new Slugify();

        return $slugify->slugify($this->getTitle());
    }

    /**
     * @return string
     */
    public function getMp3FileName()
    {
        return $this->getId().'-'.$this->getArtist()->getSlug().'--'.$this->getSlug().'.mp3';
    }

    /**
     * @return string
     */
    public function getMp3Path()
    {
        return self::MP3_DIR.$this->getMp3FileName();
    }

    /**
     * @return bool
     */
    public function hasMp3()
    {
        return file_exists($this->getMp3Path());
    }
}