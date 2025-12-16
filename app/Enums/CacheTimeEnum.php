<?php

namespace App\Enums;

enum CacheTimeEnum: int
{
    case ONE_DAY = 86400;
    case ONE_HOUR = 3600;
    case THIRTY_MINUTES = 1800;
    case FIFTEEN_MINUTES = 900;
    case FIVE_MINUTES = 300;
    case ONE_MINUTE = 60;
}

