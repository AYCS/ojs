<?php

namespace Ojs\UserBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * UserJournalRoleRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserJournalRoleRepository extends EntityRepository
{
    /**
     * get user list of users a journal with journal_id
     * @param $journal_id
     * @param  bool       $grouppedByRole
     * @return array|bool
     */
    public function getUsers($journal_id, $grouppedByRole = false)
    {
        $user_journal_roles = $this->getEntityManager()->getRepository('OjsUserBundle:UserJournalRole')->findByJournalId($journal_id);
        $entites = array();
        if (!is_array($user_journal_roles)) {
            return false;
        }
        foreach ($user_journal_roles as $item) {
            $entites[] = $item->getUser();
        }

        if (!$grouppedByRole) {
            return $entites;
        }

        $users = [];
        foreach ($entites as $user) {
            /** @var User $user */
            foreach ($user->getRoles() as $role) {
                /** @var Role $role */
                if ($role->getIsSystemRole()) {
                    continue;
                }
                $users[$role->getName()][] = $user;
            }
        }

        return $users;
    }

    /**
     * @param $user_id
     * @param  bool  $onlyJournalIds
     * @return array
     */
    public function userJournalsWithRoles($user_id, $onlyJournalIds = false)
    {
        $data = $this->getEntityManager()->createQuery(
            'SELECT u FROM OjsUserBundle:UserJournalRole u WHERE u.userId = :user_id '
        )->setParameter('user_id', $user_id)->getResult();
        $entities = array();
        if ($data) {
            foreach ($data as $item) {
                $entities[$item->getJournalId()]['journal'] = $onlyJournalIds ? $item->getJournal()->getId() : $item->getJournal();
                $entities[$item->getJournalId()]['roles'][] = $item->getRole();
            }
        }

        return $entities;
    }
}
