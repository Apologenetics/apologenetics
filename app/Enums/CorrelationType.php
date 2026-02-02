<?php

namespace App\Enums;

enum CorrelationType: int
{
    case Support = 0;
    case Refute = 1;
    case Nugget = 2;
    case Reference = 3;
}
