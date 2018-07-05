<?php

namespace App\Security\Core\User;

use App\Entity\User;
use App\Repository\UserRepository;
use Cocur\Slugify\Slugify;
use FOS\UserBundle\Model\UserManagerInterface;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\FOSUBUserProvider;
use Symfony\Component\Security\Core\User\UserInterface;

class UserProvider extends FOSUBUserProvider
{
    /** @var UserRepository $userRepository */
    private $userRepository;

    /**
     * UserProvider constructor.
     * @param UserManagerInterface $userManager
     * @param array $properties
     * @param UserRepository $userRepository
     */
    public function __construct(UserManagerInterface $userManager, Array $properties, UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
        parent::__construct($userManager, $properties);
    }

    /**
     * @param UserResponseInterface $response
     * @return mixed
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        /** @var $user User */
        $user = $this->userManager->findUserByUsername($response->getUsername());

        if(is_null($user)) {
            $user = $this->userManager->findUserByEmail($response->getEmail());
        }

        if(!is_null($user)) {
            $user->setFacebookId('')->setFacebookAccessToken('');
            $user->setVkontakteId('')->setVkontakteAccessToken('');
            $user->setTwitterId('')->setTwitterAccessToken('');
        }

        $serviceName = $response->getResourceOwner()->getName();

        $setter = 'set'.ucfirst($serviceName);
        $setter_id = $setter.'Id';
        $setter_token = $setter.'AccessToken';

        $getter = 'get'.$serviceName;
        $getter_id = $getter.'Id';

        // Registrating
        if (is_null($user)) {
            // create new user here
            $user = $this->userManager->createUser();

            $user->$setter_id($response->getUsername());
            $user->$setter_token($response->getAccessToken());

            //I have set requested data
            $user->setUsername($response->getUsername());
            $user->setName($response->getLastName()??'');
            $user->setFirstname($response->getFirstName()??'');
            $user->setEmail($response->getEmail());
            $user->setPassword($response->getUsername()); //@TODO : Generate password
            $user->addRole('ROLE_USER');
            //$user->addRole('ROLE_ADMIN');
            $user->setEnabled(true);

            //Nickname
            $nickname = $response->getNickname()?:$response->getUsername();
            if(empty($nickname) || is_numeric($nickname)) {
                $nickname = $response->getFirstName().' '.$response->getLastName();
            }

            $nickname = $this->userRepository->getNextNickname($nickname);

            $user->setNickname($nickname);
            $user->setNicknameCanonical((new Slugify())->slugify($nickname));

            $user->setProviderNickname($nickname);
            $user->setProviderNicknameCanonical((new Slugify())->slugify($nickname));

            if(isset($response->getData()['country'])) {
                $user->setCountry($response->getData()['country']);
            }

            $this->userManager->updateUser($user);

            //return $user;
        }

        if($user->$getter_id() == ''){
            $user->$setter_id($response->getUsername());
        }

        // Update avatar url in all cases
        if(!empty($response->getProfilePicture())) {
            $user->setAvatarUrl($response->getProfilePicture());
        } elseif($serviceName === 'vkontakte') {
            $data = $response->getData()['response'][0];
            if(isset($data['photo_medium'])) {
                $user->setAvatarUrl($data['photo_medium']);
            } elseif(isset($data['photo'])) {
                $user->setAvatarUrl($data['photo']);
            } elseif(isset($data['photo_rec'])) {
                $user->setAvatarUrl($data['photo_rec']);
            } elseif(isset($data['photo_big'])) {
                $user->setAvatarUrl($data['photo_big']);
            }
        }

        // Update access token
        $user->$setter_token($response->getAccessToken());
        $this->userManager->updateUser($user);

        // If user exists - go with the HWIOAuth way
        $user = parent::loadUserByOAuthUserResponse($response);

        return $user;
    }

    /**
     * @param UserInterface $user
     * @param UserResponseInterface $response
     *
    public function connect(UserInterface $user, UserResponseInterface $response)
    {
        $username = $response->getUsername();

        //on connect - get the access token and the user ID
        $service = $response->getResourceOwner()->getName();

        $setter = 'set'.ucfirst($service);
        $setter_id = $setter.'Id';
        $setter_token = $setter.'AccessToken';

        //we "disconnect" previously connected users
        dump($username); exit();
        if (null !== $previousUser = $this->userManager->findUserByUsername($username)) {
            dump($previousUser); exit();
            $previousUser->SetFaceboolId(null)->SetFacebookAccessToken(null);
            $previousUser->SetVkontaktId(null)->SetVkontaktAccessToken(null);
            $previousUser->SetTwitterId(null)->SetTwitterAccessToken(null);

            $this->userManager->updateUser($previousUser);
        }

        //we connect current user
        $user->$setter_id($username);
        $user->$setter_token($response->getAccessToken());
        $this->userManager->updateUser($user);
    }*/
}