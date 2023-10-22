<?php

declare(strict_types=1);

namespace Component\Template\Enum;

enum ContentType: string
{
    case TEXT_PLAIN = 'text/plain';
    case TEXT_HTML = 'text/html';
}
