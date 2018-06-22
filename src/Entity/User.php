<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Entity
* @ORM\Table(name="user")
*/
class User extends BaseUser
{
    const PHOTOS_FORMATS = [
        'very_small' => ['width' => 10, 'height' => 10]
    ];

    const PROVIDERS_BASE_PATH = [
        'facebook' => 'https://graph.facebook.com/'
    ];

    /**
     * User constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->votesSong = new ArrayCollection();
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
     * @var string
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $name;

    /**
     * @var string
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $firstname;

    /**
     * @var string
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $nickname;

    /**
     * @var string
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $nicknameCanonical;

    /**
     * @var string
     * @ORM\Column(type="string", length=2, nullable=true)
     */
    protected $country;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\VoteSong", mappedBy="user")
     */
    protected $votesSong;

    /**
     * @var string
     * @ORM\Column(name="facebook_id", type="string", length=255, nullable=true)
     */
    protected $facebookId;

    /**
     * @var string
     * @ORM\Column(name="facebook_access_token", type="string", length=255, nullable=true)
     */
    protected $facebookAccessToken;

    /**
     * @var string
     * @ORM\Column(name="twitter_id", type="string", length=255, nullable=true)
     */
    protected $twitterId;

    /**
     * @var string
     * @ORM\Column(name="twitter_access_token", type="string", length=255, nullable=true)
     */
    protected $twitterAccessToken;

    /**
     * @var string
     * @ORM\Column(name="vkontakte_id", type="string", length=255, nullable=true)
     */
    protected $vkontakteId;

    /**
     * @var string
     * @ORM\Column(name="vkontakte_access_token", type="string", length=255, nullable=true)
     */
    protected $vkontakteAccessToken;


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
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return User
     */
    public function setName(string $name): User
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getFirstname(): string
    {
        return $this->firstname;
    }

    /**
     * @param string $firstname
     * @return User
     */
    public function setFirstname(string $firstname): User
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * @return string
     */
    public function getNickname(): string
    {
        return $this->nickname;
    }

    /**
     * @param string $nickname
     * @return User
     */
    public function setNickname(string $nickname): User
    {
        $this->nickname = $nickname;

        return $this;
    }

    /**
     * @return string
     */
    public function getNicknameCanonical(): string
    {
        return (string)$this->nicknameCanonical;
    }

    /**
     * @param string $nicknameCanonical
     * @return User
     */
    public function setNicknameCanonical(string $nicknameCanonical): User
    {
        $this->nicknameCanonical = $nicknameCanonical;

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
     * @return User
     */
    public function setCountry(string $country): User
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getVotesSong(): Collection
    {
        return $this->votesSong;
    }

    /**
     * @param array $votesSong
     * @return User
     */
    public function setVotesSong(array $votesSong): User
    {
        $this->votesSong = $votesSong;

        return $this;
    }

    /**
     * @return array
     */
    public function getVotesSongIds()
    {
        $idsCollection = $this->votesSong->map( function( $obj ) { return $obj->getSong()->getId(); } );

        return $idsCollection->toArray();
    }

    /**
     * @return array
     *
    public function getVotesSongIds()
    {
        $votesSong = [];

        /** @var VoteSong $voteSong *
        foreach($this->votesSong as $voteSong) {
            $votesSong[$this->getId()] = $voteSong->getSong()->getId();
        }

        return $votesSong;
    }*/

    /**
     * @return string
     */
    public function getFacebookId(): string
    {
        return $this->facebookId ? : '';
    }

    /**
     * @param string $facebookId
     * @return User
     */
    public function setFacebookId(string $facebookId): User
    {
        $this->facebookId = $facebookId;

        return $this;
    }

    /**
     * @return string
     */
    public function getFacebookAccessToken(): string
    {
        return $this->facebookAccessToken;
    }

    /**
     * @param string $facebookAccessToken
     * @return User
     */
    public function setFacebookAccessToken(string $facebookAccessToken): User
    {
        $this->facebookAccessToken = $facebookAccessToken;

        return $this;
    }

    /**
     * @return string
     */
    public function getTwitterId(): string
    {
        return $this->twitterId ? : '';
    }

    /**
     * @param string $twitterId
     * @return User
     */
    public function setTwitterId(string $twitterId): User
    {
        $this->twitterId = $twitterId;

        return $this;
    }

    /**
     * @return string
     */
    public function getTwitterAccessToken(): string
    {
        return $this->twitterAccessToken;
    }

    /**
     * @param string $twitterAccessToken
     * @return User
     */
    public function setTwitterAccessToken(string $twitterAccessToken): User
    {
        $this->twitterAccessToken = $twitterAccessToken;

        return $this;
    }

    /**
     * @return string
     */
    public function getVkontakteId(): string
    {
        return $this->vkontakteId ? : '';
    }

    /**
     * @param string $vkontakteId
     * @return User
     */
    public function setVkontakteId(string $vkontakteId): User
    {
        $this->vkontakteId = $vkontakteId;

        return $this;
    }

    /**
     * @return string
     */
    public function getVkontakteAccessToken(): string
    {
        return $this->vkontakteAccessToken;
    }

    /**
     * @param string $vkontakteAccessToken
     * @return $this
     */
    public function setVkontakteAccessToken(string $vkontakteAccessToken): User
    {
        $this->vkontakteAccessToken = $vkontakteAccessToken;

        return $this;
    }

    public function getAvatarUrl($format = 'very_small')
    {
        if(!isset(self::PHOTOS_FORMATS[$format])) {
            $format = self::PHOTOS_FORMATS['very_small'];
        }

        if($this->getUsername() === $this->getFacebookId()) {
            $params = [
                'width' => self::PHOTOS_FORMATS[$format]['width'],
                'height' => self::PHOTOS_FORMATS[$format]['height'],
                'type' => 'square',
            ];

            $url = self::PROVIDERS_BASE_PATH['facebook'].$this->getFacebookId().'/picture?'.http_build_query($params);

            return $url;
        }

    }

}