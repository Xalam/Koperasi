<?php

namespace App\Exports\Toko\Laporan;

use App\Models\Toko\Transaksi\Jurnal\JurnalModel;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LaporanPendapatanExport implements FromCollection, WithHeadings {
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $tanggal;

    function __construct($tanggal) {
        $this->tanggal = $tanggal;
    }

    public function collection() {
        if ($this->tanggal) {
            return JurnalModel::join('akun', 'akun.id', '=', 'jurnal.id_akun')
                                                ->select('akun.kode AS kode_akun',  'akun.nama AS nama_akun', 
                                                        DB::raw('SUM(jurnal.debit) AS debit'), DB::raw('SUM(jurnal.kredit) AS kredit'))
                                                ->where('jurnal.tanggal', $this->tanggal)
                                                ->where(function($i) {
                                                    $i->where('akun.kode', 'like', '4%')
                                                        ->orWhere('akun.kode', 'like', '5%')
                                                        ->orWhere('akun.kode', 'like', '6%');
                                                })
                                                ->groupBy('akun.kode')
                                                ->get();               
        }
    }

    public function headings(): array {
        return ['Kode Akun', 'Nama Akun', 'Debit', 'Kredit'];
    }
}
