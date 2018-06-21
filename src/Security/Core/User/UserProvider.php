<?php

namespace App\Security\Core\User;

use App\Entity\User;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\FOSUBUserProvider;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\User\UserInterface;

class UserProvider extends FOSUBUserProvider
{
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

        $serviceName = $response->getResourceOwner()->getName();

        $setter = 'set'.ucfirst($serviceName);
        $setter_id = $setter.'Id';
        $setter_token = $setter.'AccessToken';

        $getter = 'get'.$serviceName;
        $getter_id = $getter.'Id';
        $getter_token = $getter.'AccessToken';

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
            $user->setNickname($response->getNickname());
            $user->setNicknameCanonical((new Slugify())->slugify($response->getNickname()));
            $user->setEmail($response->getEmail());
            $user->setPassword($response->getUsername()); //@TODO : Generate password
            $user->addRole('ROLE_USER');
            $user->setEnabled(true);

            if(isset($response->getData()['country'])) {
                $user->setCountry($response->getData()['country']);
            }

            $this->userManager->updateUser($user);

            return $user;
        }

        if($user->$getter_id() == ''){
            $user->$setter_id($response->getUsername());
        }

        // Update access token
        $user->$setter_token($response->getAccessToken());
        $this->userManager->updateUser($user);

        // If user exists - go with the HWIOAuth way
        $user = parent::loadUserByOAuthUserResponse($response);

        return $user;
    }

    public function connect(UserInterface $user, UserResponseInterface $response)
    {
        $username = $response->getUsername();

        //on connect - get the access token and the user ID
        $service = $response->getResourceOwner()->getName();

        $setter = 'set'.ucfirst($service);
        $setter_id = $setter.'Id';
        $setter_token = $setter.'AccessToken';

        //we "disconnect" previously connected users
        if (null !== $previousUser = $this->userManager->findUserByUsername($username)) {
            $previousUser->$setter_id(null);
            $previousUser->$setter_token(null);
            $this->userManager->updateUser($previousUser);
        }

        //we connect current user
        $user->$setter_id($username);
        $user->$setter_token($response->getAccessToken());
        $this->userManager->updateUser($user);
    }
}