<?php

namespace App\Enums;
 
enum ProductStatusEnum: string
{
    case IN_STOCK = 'In Stock';
    case SOLD_OUT = 'Sold Out';
    case COMING_SOON = 'Coming Soon';
}