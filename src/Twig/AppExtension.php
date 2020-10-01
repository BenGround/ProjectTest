<?php

namespace App\Twig;

use App\Entity\Pin;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('pluralize', [$this, 'pluralize']),
            new TwigFunction('isAdmin', [$this, 'isAdmin']),
            new TwigFunction('getPinAudio', [$this, 'getPinAudio'])
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

    public function getPinAudio(Pin $pin) : string
    {
        return 'upload/pin/audio/' . $pin->getAudioName();
    }
}
