<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Entity
* @ORM\Table(name="vote", indexes={@ORM\Index(name="type_object_id_idx", columns={"type", "object_id"})})
*/
class Vote
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @var integer
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=10, nullable=false)
     */
    private $type;

    /**
     * @var int
     * @ORM\Column(type="integer", length=10, nullable=false)
     */
    private $objectId;

    /**
     * @var string
     * @ORM\Column(type="smallint", nullable=false)
     */
    private $value;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    private $user;

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


    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param $type
     * @return Vote
     */
    public function setType($type): Vote
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return int
     */
    public function getObjectId()
    {
        return $this->objectId;
    }

    /**
     * @param $objectId
     * @return Vote
     */
    public function setObjectId($objectId): Vote
    {
        $this->objectId = $objectId;

        return $this;
    }

    /**
     * @return int
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param $value
     * @return Vote
     */
    public function setValue($value): Vote
    {
        $this->value = $value;

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
     * @return Vote
     */
    public function setUser(User $user): Vote
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
     * @return Vote
     */
    public function setCreatedAt(\DateTime $createdAt): Vote
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
     * @return Vote
     */
    public function setUpdatedAt(\DateTime $updatedAt): Vote
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

}