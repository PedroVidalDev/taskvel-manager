<?php

namespace App\Enums;

enum StatusEnum: string
{
    case FINISHED = 'FINISHED';
    case WORKING = 'WORKING';
    case IN_PROGRESS = 'IN_PROGRESS';
}
