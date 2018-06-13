<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

  /**
  * @ORM\Entity
  * @ORM\Table(
  *     name="vote_artist",
  *     indexes={@ORM\Index(name="artist_id_user_id_idx", columns={"artist_id", "user_id"})},
  *     uniqueConstraints={
  *        @ORM\UniqueConstraint(name="artist_id_user_id_unique", columns={"artist_id", "user_id"})
  *     }
  * )
  */
class VoteArtist
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Artist", inversedBy="votes", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="artist_id", referencedColumnName="id", nullable=false)
     */
    private $artist;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="votesArtist")
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
    public function getArtist()
    {
        return $this->artist;
    }

    /**
     * @param $artist
     * @return VoteArtist
     */
    public function setArtist($artist): VoteArtist
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
     * @return VoteArtist
     */
    public function setUser(User $user): VoteArtist
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
     * @return VoteArtist
     */
    public function setCreatedAt(\DateTime $createdAt): VoteArtist
    {
        $this->createdAt = $createdAt;

        return $this;
    }

}