<?php
namespace App\Enums;

Enum CDCStatusEnum : int
{
    const in_delivery = 5;
    const delivered = 6;
    const returned = 7;
    const pending = 9;
}