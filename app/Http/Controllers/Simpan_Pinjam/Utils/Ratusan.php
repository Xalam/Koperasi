<?php

namespace App\Http\Controllers\Simpan_Pinjam\Utils;

class Ratusan
{
    public static function edit_ratusan($nominal)
    {
        $intNumber = (int) $nominal;

        $ratusan = substr($intNumber, -3);

        $bulatPen = $intNumber - $ratusan;
        $newRatusan = 0;

        if ($ratusan > 0 && $ratusan <= 100) {
            $newRatusan = 100;
        } else if ($ratusan > 100 && $ratusan <= 200) {
            $newRatusan = 200;
        } else if ($ratusan > 200 && $ratusan <= 300) {
            $newRatusan = 300;
        } else if ($ratusan > 300 && $ratusan <= 400) {
            $newRatusan = 400;
        } else if ($ratusan > 400 && $ratusan <= 500) {
            $newRatusan = 500;
        } else if ($ratusan > 500 && $ratusan <= 600) {
            $newRatusan = 600;
        } else if ($ratusan > 600 && $ratusan <= 700) {
            $newRatusan = 700;
        } else if ($ratusan > 700 && $ratusan <= 800) {
            $newRatusan = 800;
        } else if ($ratusan > 800 && $ratusan <= 900) {
            $newRatusan = 900;
        } else if ($ratusan > 900 && $ratusan <= 999) {
            $newRatusan = 1000;
        } else {
            $newRatusan = $ratusan;
        }

        $newNominal = $bulatPen + $newRatusan;

        return $newNominal;
    }
}
