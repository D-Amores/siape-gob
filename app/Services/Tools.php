<?php

namespace App\Services;

class Tools
{
    public static function formatPhoneNumber(string $phone): string
    {
        // Format the phone number as needed
        return preg_replace('/\D/', '', $phone);
    }
}
