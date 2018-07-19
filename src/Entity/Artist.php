<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Entity
* @ORM\Table(name="artist")
*/
class Artist
{
    /**
     * Artist constructor.
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
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var ArrayCollection
     * @ORM\Column(type="json_array", nullable=true)
     */
    private $nameVariations;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $realName;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    private $descriptionDiscogs;

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
     * @return Artist
     */
    public function setIdDiscogs(int $idDiscogs): Artist
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
     * @return Artist
     */
    public function setUser(User $user): Artist
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Artist
     */
    public function setName(string $name): Artist
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param string $name
     * @return Artist
     */
    public function setTitle(string $name): Artist
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return array
     */
    public function getNameVariations(): array
    {
        return $this->nameVariations;
    }

    /**
     * @param array $nameVariations
     * @return Artist
     */
    public function setNameVariations(array $nameVariations): Artist
    {
        $this->nameVariations = $nameVariations;

        return $this;
    }

    /**
     * @return string
     */
    public function getRealName(): string
    {
        return $this->realName;
    }

    /**
     * @param string $realName
     * @return Artist
     */
    public function setRealName(string $realName): Artist
    {
        $this->realName = $realName;

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
     * @return Artist
     */
    public function setDescription(string $description): Artist
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescriptionDiscogs(): string
    {
        return $this->descriptionDiscogs;
    }

    /**
     * @param string $descriptionDiscogs
     * @return Artist
     */
    public function setDescriptionDiscogs(?string $descriptionDiscogs): Artist
    {
        $this->descriptionDiscogs = $descriptionDiscogs;

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
     * @return Artist
     */
    public function setCreatedAt(\DateTime $createdAt): Artist
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
     * @return Artist
     */
    public function setUpdatedAt(\DateTime $updatedAt): Artist
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}