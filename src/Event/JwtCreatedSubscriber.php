<?php


namespace App\Event;


use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;

class JwtCreatedSubscriber
{
    public function updateJwtData(JWTCreatedEvent $event)
    {
        // On récupère l'utilisateur
        $user = $event->getUser();

        // On enrichit le data du Token
        $data = $event->getData();

        $data['archived'] = $user->getArchiver();
         $data['id'] = $user->getId();

        $event->setData($data);
    }
}
