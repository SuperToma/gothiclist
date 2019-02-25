<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

  /**
  * @ORM\Entity
  * @ORM\Table(name="comment_song")
  */
class CommentSong
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
     * VoteSong constructor.
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Song", inversedBy="comments", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="song_id", referencedColumnName="id", nullable=false)
     */
    private $song;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="commentsSong")
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
    public function getSong()
    {
        return $this->song;
    }

    /**
     * @param $song
     * @return CommentSong
     */
    public function setSong($song): CommentSong
    {
        $this->song = $song;

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
     * @return CommentSong
     */
    public function setUser(User $user): CommentSong
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
     * @return CommentSong
     */
    public function setText(string $text): CommentSong
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
     * @return CommentSong
     */
    public function setCreatedAt(\DateTime $createdAt): CommentSong
    {
        $this->createdAt = $createdAt;

        return $this;
    }

}