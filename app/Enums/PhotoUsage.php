<?php

namespace App\Enums;

enum PhotoUsage: string
{
    case PRODUCT = 'product';
    case PACKAGE = 'package';
    case GALLERY = 'gallery';
}
