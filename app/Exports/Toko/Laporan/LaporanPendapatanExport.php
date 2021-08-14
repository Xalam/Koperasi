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
    protected $tanggal_awal, $tanggal_akhir;

    function __construct($tanggal_awal, $tanggal_akhir) {
        $this->tanggal_awal = $tanggal_awal;
        $this->tanggal_akhir = $tanggal_akhir;
    }

    public function collection() {
        if ($this->tanggal_awal && $this->tanggal_akhir) {
            return JurnalModel::join('akun', 'akun.id', '=', 'jurnal.id_akun')
                                                ->select('akun.kode AS kode_akun',  'akun.nama AS nama_akun', 
                                                        DB::raw('SUM(jurnal.debit) AS debit'), DB::raw('SUM(jurnal.kredit) AS kredit'))
                                                ->whereBetween('jurnal.tanggal', [$this->tanggal_awal, $this->tanggal_akhir])
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
