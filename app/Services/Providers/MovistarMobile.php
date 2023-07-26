<?php

namespace App\Services\Providers;

use App\Contact;
use App\Interfaces\CarrierInterface;

class MovistarMobile implements CarrierInterface
{
    public function dialContact(Contact $contact)
    {
        //dial contact for MovistarMobile
    }

    public function makeCall()
    {
        //make a call for MovistarMobile
    }

    public function sendSMS(string $number, string $body)
    {
        //send sms for MovistarMobile
    }
}