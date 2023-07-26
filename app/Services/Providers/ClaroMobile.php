<?php

namespace App\Services\Providers;

use App\Interfaces\CarrierInterface;

class ClaroMobile implements CarrierInterface
{
    public function dialContact(Contact $contact)
    {
        //dial contact for ClaroMobile
    }

    public function makeCall()
    {
        //make a call for ClaroMobile
    }

    public function sendSMS(string $number, string $body)
    {
        //send sms for ClaroMobile
    }
}