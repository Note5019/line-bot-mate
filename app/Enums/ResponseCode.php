<?php

namespace App\Enums;

enum ResponseCode: int
{
    case OK_NO_RESPONSE = -1;
    case OK = 0;
    case ERROR = 1;
}