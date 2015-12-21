<?php

namespace Ojs\CoreBundle\Params;

class ArticleStatuses
{
    const STATUS_WITHDRAWN = -4;
    const STATUS_REJECTED = -3;
    const STATUS_UNPUBLISHED = -2;
    const STATUS_NOT_SUBMITTED = -1;
    const STATUS_INREVIEW = 0;
    const STATUS_PUBLISHED = 1;
}
