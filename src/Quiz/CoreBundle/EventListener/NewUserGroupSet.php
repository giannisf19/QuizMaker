<?php

namespace Quiz\CoreBundle\EventListener;

use Doctrine\ORM\EntityManager;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Model\UserManager;
use Quiz\CoreBundle\Entity\UserEntity;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class NewUserGroupSet implements EventSubscriberInterface {

    /** @var $um UserManager  */

    protected $um;

    /** @var EntityManager  */
    protected $en;


    public function __construct(UserManager $um, EntityManager $em)
    {
        $this->um = $um;
        $this->em = $em;
    }

    public static function getSubscribedEvents()
    {
        return array(
            FOSUserEvents::REGISTRATION_COMPLETED => "onRegistrationSuccess",
        );
    }

    public function onRegistrationSuccess(FilterUserResponseEvent $event)
    {
        $user = $event->getUser();

        /* @var $user UserEntity */


        switch ($user->getUserType()) {
            case 'k':
                $user->addRole('ROLE_TEACHER');
                break;

            case 'm':
                $user->addRole('ROLE_STUDENT');
                break;


        }
        $this->um->updateUser($user);
        $this->em->flush();
    }





}