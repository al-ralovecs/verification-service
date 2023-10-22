<?php

declare(strict_types=1);

namespace Component\Gotify\Enum;

enum SendMessageStep: string
{
    case GET_RECIPIENT_FROM_LIST = 'get_recipient_from_list';
    case CREATE_NEW_RECIPIENT = 'create_new_recipient';
    case GET_APPLICATION_FROM_LIST = 'get_application_from_list';
    case CREATE_NEW_APPLICATION = 'create_new_application';
    case PUBLISH_MESSAGE = 'publish_message';
}
