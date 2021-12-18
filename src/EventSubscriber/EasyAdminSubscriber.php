<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class EasyAdminSubscriber implements EventSubscriberInterface
{
    public function onEasyAdminSubscriber($event)
    {
        // ...
    }

    public static function getSubscribedEvents()
    {
        return [
            'EasyAdminSubscriber' => 'onEasyAdminSubscriber',
        ];
    }
}
