<?php

namespace Ojs\JournalBundle\Event\JournalSubmissionFile;

use Ojs\CoreBundle\Events\EventDetail;
use Ojs\CoreBundle\Events\MailEventsInterface;

final class JournalSubmissionFileEvents implements MailEventsInterface
{
    const LISTED = 'ojs.journal_submission_file.list';

    const PRE_CREATE = 'ojs.journal_submission_file.pre_create';

    const POST_CREATE = 'ojs.journal_submission_file.post_create';

    const PRE_UPDATE = 'ojs.journal_submission_file.pre_update';

    const POST_UPDATE = 'ojs.journal_submission_file.post_update';

    const PRE_DELETE = 'ojs.journal_submission_file.pre_delete';

    const POST_DELETE = 'ojs.journal_submission_file.post_delete';

    public function getMailEventsOptions()
    {
        return [
            new EventDetail(self::POST_CREATE, 'journal', []),
            new EventDetail(self::POST_UPDATE, 'journal', []),
            new EventDetail(self::POST_DELETE, 'journal', []),
        ];
    }
}
