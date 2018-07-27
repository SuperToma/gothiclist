<?php

namespace App\Twig;

use App\Entity\User;
use Doctrine\ORM\EntityNotFoundException;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Class AppExtension
 * @package App\Twig
 */
class AppExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('is_valid', [$this, 'is_valid']),
        ];
    }

    public function is_valid($user)
    {
        // if not connected $user == null (ex : for check voting)
        if(!$user instanceof User) {
            return false;
        }

        try {
            if(!empty($user->getEmail())) {
                return true;
            }
        } catch (EntityNotFoundException $e) {
            return false;
        }
    }
}