<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

  /**
  * @ORM\Entity
  * @ORM\Table(
  *     name="vote_song",
  *     indexes={@ORM\Index(name="song_id_user_id_idx", columns={"song_id", "user_id"})},
  *     uniqueConstraints={
  *        @ORM\UniqueConstraint(name="song_id_user_id_unique", columns={"song_id", "user_id"})
  *     }
  * )
  */
class VoteSong
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Song", inversedBy="votes", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="song_id", referencedColumnName="id", nullable=false)
     */
    private $song;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="votesSong")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    private $user;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @return int
     */
    public function getid(): int
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
     * @return VoteSong
     */
    public function setSong($song): VoteSong
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
     * @return VoteSong
     */
    public function setUser(User $user): VoteSong
    {
        $this->user = $user;

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
     * @return VoteSong
     */
    public function setCreatedAt(\DateTime $createdAt): VoteSong
    {
        $this->createdAt = $createdAt;

        return $this;
    }

}