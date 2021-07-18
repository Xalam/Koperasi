<?php

namespace App\Http\Controllers\Simpan_Pinjam;

use Illuminate\Support\Facades\Http;

class ResponseMessage
{
    public static function send($number = null, $message = null)
    {
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/x-www-form-urlencoded'
        ])
            ->withToken('mP7z7tk5YFiTdcl7cv1sI3EMYiejPAxaUX3bG8mbs7yWElTDeB')
            ->asForm()
            ->post('https://app.whatspie.com/api/messages', [
                'receiver' => $number,
                'device' => '081369635623',
                'message' => $message,
                'type' => 'chat'
            ]);

        return $response;
    }
}
