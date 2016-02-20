<?php

namespace Ojs\JournalBundle\Event\Article;

use Ojs\CoreBundle\Events\MailEventsInterface;

final class ArticleEvents implements MailEventsInterface
{
    const LISTED = 'ojs.article.list';

    const PRE_CREATE = 'ojs.article.pre_create';

    const POST_CREATE = 'ojs.article.post_create';

    const PRE_UPDATE = 'ojs.article.pre_update';

    const POST_UPDATE = 'ojs.article.post_update';

    const PRE_DELETE = 'ojs.article.pre_delete';

    const POST_DELETE = 'ojs.article.post_delete';

    const PRE_SUBMIT = 'ojs.article.pre_submit';

    const POST_SUBMIT = 'ojs.article.post_submit';

    const INIT_SUBMIT_FORM = 'ojs.article.init_submit_form';

    public function getMailEventsOptions()
    {
        return [];
    }
}
