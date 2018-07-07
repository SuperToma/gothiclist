<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Entity
* @ORM\Table(name="`release`")
*/
class Release
{
    /**
     * Song constructor.
     */
    public function __construct()
    {
        $this->styles = new ArrayCollection();
        $this->genres = new ArrayCollection();
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
     * @ORM\Column(type="integer", nullable=false)
     */
    private $idDiscogs;


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
     * @ORM\Column(type="text", nullable=true)
     */
    private $discogsNotes;

    /**
     * @var Collection
     * @ORM\ManyToMany(targetEntity="Genre")
     * @ORM\JoinTable(
     *      name="release_genre",
     *      joinColumns={@ORM\JoinColumn(name="release_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="genre_id", referencedColumnName="id")}
     * )
     */
    private $genres;

    /**
     * @var Collection
     * @ORM\ManyToMany(targetEntity="Style")
     * @ORM\JoinTable(
     *      name="release_style",
     *      joinColumns={@ORM\JoinColumn(name="release_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="style_id", referencedColumnName="id")}
     * )
     */
    private $styles;

    /**
     * @var string
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $country;

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
    public function getIdDiscogs(): int
    {
        return $this->idDiscogs;
    }

    /**
     * @param int $idDiscogs
     * @return Release
     */
    public function setIdDiscogs(int $idDiscogs): Release
    {
        $this->idDiscogs = $idDiscogs;

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
     * @return Release
     */
    public function setUser(User $user): Release
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
     * @return Release
     */
    public function setTitle(string $title): Release
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
     * @return Release
     */
    public function setDescription(string $description): Release
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getDiscogsNotes(): string
    {
        return $this->discogsNotes;
    }

    /**
     * @param string $discogsNotes
     * @return Release
     */
    public function setDiscogsNotes(?string $discogsNotes): Release
    {
        $this->discogsNotes = $discogsNotes;

        return $this;
    }

    /**
     * @return array
     */
    public function getGenres(): array
    {
        return $this->genres->toArray();
    }

    /**
     * @param string $genre
     * @return Release
     */
    public function addGenre(string $genre): Release
    {
        $this->styles->add($genre);

        return $this;
    }

    /**
     * @param ArrayCollection $genres
     * @return Release
     */
    public function setGenres(ArrayCollection $genres): Release
    {
        $this->genres = $genres;

        return $this;
    }

    /**
     * @return array
     */
    public function getStyles(): array
    {
        return $this->styles->toArray();
    }

    public function hasStyle(int $idStyle): bool
    {
        $styles = $this->getStyles();
        foreach ($styles as $style) {
            if($style['id'] === $idStyle) {
                return true;
            }
        }

        return true;
    }

    /**
     * @param string $style
     * @return Release
     */
    public function addStyle(string $style): Release
    {
        $this->styles->add($style);

        return $this;
    }

    /**
     * @param ArrayCollection $styles
     * @return Release
     */
    public function setStyles(ArrayCollection $styles): Release
    {
        $this->styles = $styles;

        return $this;
    }

    /**
     * @return string
     */
    public function getCountry(): string
    {
        return $this->country;
    }

    /**
     * @param string $country
     * @return Release
     */
    public function setCountry(string $country): Release
    {
        $this->country = $country;

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
     * @return Release
     */
    public function setCreatedAt(\DateTime $createdAt): Release
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
     * @return Release
     */
    public function setUpdatedAt(\DateTime $updatedAt): Release
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}