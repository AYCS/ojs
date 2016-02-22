<?php

namespace Ojs\AdminBundle\EventListener;

use Doctrine\ORM\EntityManager;
use Ojs\AdminBundle\Events\AdminEvent;
use Ojs\AdminBundle\Events\AdminEvents;
use Ojs\CoreBundle\Service\OjsMailer;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\RouterInterface;

class AdminEventListener implements EventSubscriberInterface
{
    /** @var RouterInterface */
    private $router;

    /** @var EntityManager */
    private $em;

    /** @var OjsMailer */
    private $ojsMailer;

    /**
     * @param RouterInterface $router
     * @param EntityManager $em
     * @param OjsMailer $ojsMailer
     *
     */
    public function __construct(
        RouterInterface $router,
        EntityManager $em,
        OjsMailer $ojsMailer
    ) {
        $this->router = $router;
        $this->em = $em;
        $this->ojsMailer = $ojsMailer;
    }

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            AdminEvents::ADMIN_USER_CHANGE => 'onUserChange',
            AdminEvents::ADMIN_CONTACT_CHANGE => 'onJournalContactChange',
            AdminEvents::JOURNAL_APPLICATION_HAPPEN => 'onJournalApplicationHappen',
            AdminEvents::ADMIN_JOURNAL_CHANGE => 'onJournalChange',
            AdminEvents::PUBLISHER_APPLICATION_HAPPEN => 'onPublisherApplicationHappen',
            AdminEvents::PUBLISHER_MANAGER_CHANGE => 'onPublisherManagerChange',
            AdminEvents::PUBLISHER_CHANGE => 'onPublisherChange',
            AdminEvents::ADMIN_SUBJECT_CHANGE => 'onAdminSubjectChange',
            AdminEvents::SETTINGS_CHANGE => 'onSettingsChange',
        );
    }

    /**
     * @param AdminEvent $event
     */
    public function onUserChange(AdminEvent $event)
    {
        $adminUsers = $this->ojsMailer->getAdminUsers();

        foreach ($adminUsers as $user) {
            $this->ojsMailer->sendToUser(
                $user,
                'Admin Event : Admin User Change -> '.$event->getEventType(),
                'Admin Event : Admin User Change -> '.$event->getEventType().' -> by '.$event->getUser()->getUsername()
            );
        }
    }

    /**
     * @param AdminEvent $event
     */
    public function onJournalContactChange(AdminEvent $event)
    {
        $adminUsers = $this->ojsMailer->getAdminUsers();

        foreach ($adminUsers as $user) {
            $this->ojsMailer->sendToUser(
                $user,
                'Admin Event : Admin Contact Change -> '.$event->getEventType(),
                'Admin Event : Admin Contact Change -> '.$event->getEventType().' -> by '.$event->getUser(
                )->getUsername()
            );
        }
    }

    /**
     * @param AdminEvent $event
     */
    public function onJournalApplicationHappen(AdminEvent $event)
    {
        $adminUsers = $this->ojsMailer->getAdminUsers();

        foreach ($adminUsers as $user) {
            $this->ojsMailer->sendToUser(
                $user,
                'Admin Event : Journal Application Happen',
                'Admin Event : Journal Application Happen'
            );
        }
    }

    /**
     * @param AdminEvent $event
     */
    public function onJournalChange(AdminEvent $event)
    {
        $adminUsers = $this->ojsMailer->getAdminUsers();

        foreach ($adminUsers as $user) {
            $this->ojsMailer->sendToUser(
                $user,
                'Admin Event : Admin Journal Change -> '.$event->getEventType(),
                'Admin Event : Admin Journal Change -> '.$event->getEventType().' -> by '.$event->getUser(
                )->getUsername()
            );
        }
    }

    /**
     * @param AdminEvent $event
     */
    public function onPublisherApplicationHappen(AdminEvent $event)
    {
        $adminUsers = $this->ojsMailer->getAdminUsers();

        foreach ($adminUsers as $user) {
            $this->ojsMailer->sendToUser(
                $user,
                'Admin Event : Publisher Application Happen',
                'Admin Event : Publisher Application Happen'
            );
        }
    }

    /**
     * @param AdminEvent $event
     */
    public function onPublisherManagerChange(AdminEvent $event)
    {
        $adminUsers = $this->ojsMailer->getAdminUsers();

        foreach ($adminUsers as $user) {
            $this->ojsMailer->sendToUser(
                $user,
                'Admin Event : Admin Publisher Manager Change -> '.$event->getEventType(),
                'Admin Event : Admin Publisher Manager Change -> '.$event->getEventType().' -> by '.$event->getUser(
                )->getUsername()
            );
        }
    }

    /**
     * @param AdminEvent $event
     */
    public function onPublisherChange(AdminEvent $event)
    {
        $adminUsers = $this->ojsMailer->getAdminUsers();

        foreach ($adminUsers as $user) {
            $this->ojsMailer->sendToUser(
                $user,
                'Admin Event : Admin Publisher Change -> '.$event->getEventType(),
                'Admin Event : Admin Publisher Change -> '.$event->getEventType().' -> by '.$event->getUser(
                )->getUsername()
            );
        }
    }

    /**
     * @param AdminEvent $event
     */
    public function onAdminSubjectChange(AdminEvent $event)
    {
        $adminUsers = $this->ojsMailer->getAdminUsers();

        foreach ($adminUsers as $user) {
            $this->ojsMailer->sendToUser(
                $user,
                'Admin Event : Admin Subject Change -> '.$event->getEventType(),
                'Admin Event : Admin Subject Change -> '.$event->getEventType().' -> by '.$event->getUser(
                )->getUsername()
            );
        }
    }

    /**
     * @param AdminEvent $event
     */
    public function onSettingsChange(AdminEvent $event)
    {
        $adminUsers = $this->ojsMailer->getAdminUsers();

        foreach ($adminUsers as $user) {
            $this->ojsMailer->sendToUser(
                $user,
                'Admin Event : Admin System Settings Change -> '.$event->getEventType(),
                'Admin Event : Admin System Settings Change -> '.$event->getEventType().' -> by '.$event->getUser(
                )->getUsername()
            );
        }
    }
}
