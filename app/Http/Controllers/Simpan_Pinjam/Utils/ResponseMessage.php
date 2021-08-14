<?php

namespace App\Http\Controllers\Simpan_Pinjam\Utils;

use Illuminate\Support\Facades\Http;

class ResponseMessage
{
    public static function send($phoneNumber = null, $message = null)
    {
        if ($phoneNumber == null) {
            $number = $phoneNumber;
        } else {
            if ($phoneNumber[0] == '0') {
                $subPhone = substr($phoneNumber, 1);
                $number   = '62' . $subPhone;
            } else {
                $number = $phoneNumber;
            }
        }

        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/x-www-form-urlencoded'
        ])
            ->withToken('mP7z7tk5YFiTdcl7cv1sI3EMYiejPAxaUX3bG8mbs7yWElTDeB')
            ->asForm()
            ->post('https://app.whatspie.com/api/messages', [
                'receiver' => $number,
                'device' => '6283857560363',
                'message' => $message,
                'type' => 'chat'
            ]);

        return $response;
    }
}
