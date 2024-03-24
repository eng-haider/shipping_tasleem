<?php
namespace App\Enums;

Enum CDCStatusEnum : int
{
    const in_delivery = '83c3543c-4a82-4f10-94ef-09760c620c8d';
    const delivered = '473f48d2-a4ac-4e98-889f-c3b715908644';
    const returned = 7;
    const pending = 9;
}