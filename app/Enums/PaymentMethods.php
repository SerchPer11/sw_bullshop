<?php

namespace App\Enums;

enum PaymentMethods: int
{
    case CASH = 1;
    case STRIPE = 2;
}
