<?php

namespace App\Services;

use App\Contact;
use App\SMS;


class InfobipService
{
	
	public static function sendAndTrackSMS($number, $message)
	{
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://xrnd4l.api.infobip.com/sms/2/text/advanced',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{"messages":[{"destinations":[{"to":"'.$number.'"}],"from":"InfoSMS","text":"'.$message.'"}]}',
            CURLOPT_HTTPHEADER => array(
                'Authorization: {authorization}',
                'Content-Type: application/json',
                'Accept: application/json'
            ),
        ));

        $response = curl_exec($curl);
        $sms = new SMS();
        $sms->code = $response['code'];
        $sms->details = $response['msg'];

        return $sms;
	}

}