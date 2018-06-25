<?php

namespace App\Twig;

use App\Entity\User;
use Doctrine\ORM\EntityNotFoundException;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return array(
            new TwigFunction('is_valid', [$this, 'is_valid']),
        );
    }

    public function is_valid(User $user)
    {
        try {
            if(!empty($user->getEmail())) {
                return true;
            }
        } catch (EntityNotFoundException $e) {
            return false;
        }
    }
}