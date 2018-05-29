<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Entity
* @ORM\Table(name="song")
*/
class Song
{
    /**
     * Song constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @var integer
     */
    protected $id;

    /**
     * @var integer
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $idDiscogs;

    /**
     * @var integer
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $idArtist;

    /**
     * @var integer
     * @ORM\OneToMany(targetEntity="App\Entity\User")
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $user;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $title;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=false)
     */
    protected $createdAt;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $updatedAt;


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
     * @return int
     */
    public function getIdDiscogs(): int
    {
        return $this->idDiscogs;
    }

    /**
     * @param int $idDiscogs
     * @return Song
     */
    public function setIdDiscogs(int $idDiscogs): Song
    {
        $this->idDiscogs = $idDiscogs;

        return $this;
    }

    /**
     * @return int
     */
    public function getIdArtist(): int
    {
        return $this->idArtist;
    }

    /**
     * @param int $id
     * @return Song
     */
    public function setIdArtist(int $id): Song
    {
        $this->id = $id;

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
     * @return User
     */
    public function setUser(User $user): User
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
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     * @return \DateTime
     */
    public function setCreatedAt(\DateTime $createdAt): \DateTime
    {
        $this->createdAt = $createdAt;

        return $this;
    }

}