<?php

namespace Ojs\AdminBundle\Events;

use Ojs\CoreBundle\Events\EventDetail;
use Ojs\CoreBundle\Events\MailEventsInterface;

final class AdminEvents implements MailEventsInterface
{
    const ADMIN_USER_CHANGE = 'ojs.admin.user.change';
    const ADMIN_CONTACT_CHANGE = 'ojs.admin.journal.contact.change';
    const JOURNAL_APPLICATION_HAPPEN = 'ojs.admin.journal.application.happen';
    const JOURNAL_APPLICATION_RESPONSE = 'ojs.admin.journal.application.response';
    const ADMIN_JOURNAL_CHANGE = 'ojs.admin.journal.change';
    const PUBLISHER_APPLICATION_HAPPEN = 'ojs.admin.publisher.application.happen';
    const PUBLISHER_MANAGER_CHANGE = 'ojs.admin.publisher.manager.change';
    const PUBLISHER_CHANGE = 'ojs.admin.publisher.change';
    const ADMIN_SUBJECT_CHANGE = 'ojs.admin.subject.change';
    const SETTINGS_CHANGE = 'ojs.admin.settings.change';
    const ADMIN_LEFT_MENU_INITIALIZED = 'ojs.admin.left.menu.initialized';
    const ADMIN_RIGHT_MENU_INITIALIZED = 'ojs.admin.right.menu.initialized';
    const ADMIN_APPLICATION_MENU_INITIALIZED = 'ojs.admin.application.menu.initialized';

    public function getMailEventsOptions()
    {
        return [
            new EventDetail(self::ADMIN_USER_CHANGE, 'admin', []),
            new EventDetail(self::ADMIN_CONTACT_CHANGE, 'admin', []),
            new EventDetail(self::JOURNAL_APPLICATION_HAPPEN, 'admin', []),
            new EventDetail(self::ADMIN_JOURNAL_CHANGE, 'admin', []),
            new EventDetail(self::PUBLISHER_APPLICATION_HAPPEN, 'admin', []),
            new EventDetail(self::PUBLISHER_CHANGE, 'admin', []),
            new EventDetail(self::ADMIN_SUBJECT_CHANGE, 'admin', []),
            new EventDetail(self::SETTINGS_CHANGE, 'admin', []),
        ];
    }
}
