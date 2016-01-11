<?php

namespace Ojs\JournalBundle\Event\JournalIndex;

final class JournalIndexEvents
{
    const LISTED = 'ojs.journal_index.list';

    const PRE_CREATE = 'ojs.journal_index.pre_create';

    const POST_CREATE = 'ojs.journal_index.post_create';

    const PRE_UPDATE = 'ojs.journal_index.pre_update';

    const POST_UPDATE = 'ojs.journal_index.post_update';

    const PRE_DELETE = 'ojs.journal_index.pre_delete';

    const POST_DELETE = 'ojs.journal_index.post_delete';
}
