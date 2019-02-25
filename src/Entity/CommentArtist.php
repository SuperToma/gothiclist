<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

  /**
  * @ORM\Entity
  * @ORM\Table(name="comment_artist")
  */
class CommentArtist
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @var integer
     */
    protected $id;

    /**
     * VoteArtist constructor.
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Artist", inversedBy="comments", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="artist_id", referencedColumnName="id", nullable=false)
     */
    private $artist;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="commentsArtist")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    private $user;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $text;

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
     * @return int
     */
    public function getArtist()
    {
        return $this->artist;
    }

    /**
     * @param $artist
     * @return CommentArtist
     */
    public function setArtist($artist): CommentArtist
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
     * @return CommentArtist
     */
    public function setUser(User $user): CommentArtist
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @param $text
     * @return CommentArtist
     */
    public function setText(string $text): CommentArtist
    {
        $this->text = $text;

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
     * @return CommentArtist
     */
    public function setCreatedAt(\DateTime $createdAt): CommentArtist
    {
        $this->createdAt = $createdAt;

        return $this;
    }

}