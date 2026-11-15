<?php

namespace App\Enums;

enum DeletableEnum: int
{
    case DELETED = 1;
    case NOT_DELETED = 2;
}
