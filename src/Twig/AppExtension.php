<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('pluralize', [$this, 'pluralize']),
            new TwigFunction('isAdmin', [$this, 'isAdmin'])
        ];
    }

    public function pluralize($number, $singular, $plural) : string
    {
        return $number == 1 ? $number.' '.$singular : $number.' '.$plural;
    }

    public function isAdmin($user) : bool
    {
        return in_array('ROLE_ADMIN', $user->getRoles(), true);
    }

}
