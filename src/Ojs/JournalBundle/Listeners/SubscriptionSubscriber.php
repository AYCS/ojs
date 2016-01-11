<?php

namespace Ojs\JournalBundle\Listeners;

use Doctrine\ORM\EntityManager;
use Ojs\CoreBundle\Service\OjsMailer;
use Ojs\JournalBundle\Entity\JournalAnnouncement;
use Ojs\JournalBundle\Event\JournalAnnouncement\JournalAnnouncementEvents;
use Ojs\JournalBundle\Event\JournalItemEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SubscriptionSubscriber implements EventSubscriberInterface
{
    /** @var EntityManager */
    private $em;

    /** @var OjsMailer */
    private $ojsMailer;

    /**
     * @param EntityManager $em
     * @param OjsMailer $ojsMailer
     */
    public function __construct(EntityManager $em, OjsMailer $ojsMailer)
    {
        $this->em = $em;
        $this->ojsMailer = $ojsMailer;
    }


    public static function getSubscribedEvents()
    {
        return array(
            JournalAnnouncementEvents::POST_CREATE => 'sendAnnouncement'
        );
    }

    public function sendAnnouncement(JournalItemEvent $itemEvent)
    {
        /** @var JournalAnnouncement $announcement */
        $announcement = $itemEvent->getItem();

        $mailList = $this->em
            ->getRepository('OjsJournalBundle:SubscribeMailList')
            ->findBy(['journal' => $announcement->getJournal()]);

        foreach ($mailList as $mail) {
            $this->ojsMailer->send(
                $announcement->getTitle(),
                $announcement->getContent(),
                $mail->getMail(),
                $mail->getMail()
            );
        }
    }
}
