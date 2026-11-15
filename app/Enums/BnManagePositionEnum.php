<?php

namespace App\Enums;

enum BnManagePositionEnum: string
{
    case CATEGORY_HOME_POSITION = 'category_home';
    case CATEGORY_INNER_POSITION = 'category_inner';
    case LEAD_POSITION = 'lead'; // treated as special category position in content create/edit blade
    case TRENDING_TAG_POSITION = 'trending_tag';
    case SCROLL_POSITION = 'scroll'; // Breaking, General Scroll
    case SPECIAL_EVENT_POSITION = 'special_event';
    case SPECIAL = 'special'; // Election, etc
}

// usage example: BnManagePositionEnum::HOME_CATEGORY_POSITION->value;
