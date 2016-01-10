<?php

namespace Ojs\JournalBundle\Listeners;

use Doctrine\ORM\EntityManager;
use FOS\UserBundle\Model\UserInterface;
use Ojs\CoreBundle\Service\OjsMailer;
use Ojs\JournalBundle\Event\JournalItemEvent;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

abstract class AbstractJournalItemMailer implements EventSubscriberInterface
{
    /** @var OjsMailer */
    protected $ojsMailer;

    /** @var EntityManager */
    protected $em;

    /** @var UserInterface */
    protected $user;

    /**
     * JournalPageMailer constructor.
     * @param OjsMailer $ojsMailer
     * @param RegistryInterface $registry
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(OjsMailer $ojsMailer, RegistryInterface $registry, TokenStorageInterface $tokenStorage)
    {
        $this->ojsMailer = $ojsMailer;
        $this->em = $registry->getManager();
        $this->user = $tokenStorage->getToken()->getUser();
    }

    protected function sendMail(JournalItemEvent $itemEvent, $item, $action)
    {
        $mailUsers = $this->em->getRepository('OjsUserBundle:User')->findUsersByJournalRole(
            ['ROLE_JOURNAL_MANAGER', 'ROLE_EDITOR']
        );

        $journalItem = $itemEvent->getItem();
        foreach ($mailUsers as $user) {
            $this->ojsMailer->sendToUser(
                $user,
                'A '.$item.' '.$action.' -> '.$journalItem->getJournal()->getTitle(),
                'A '.$item.' '.$action.' -> '.$journalItem->getJournal()->getTitle()
                .' -> by '.$this->user->getUsername()
            );
        }
    }
}
