<?php

namespace App\Exports\Toko\Laporan;

use App\Models\Toko\Transaksi\Piutang\PiutangModel;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LaporanPiutangExport implements FromCollection, WithHeadings {
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
            return PiutangModel::whereBetween(DB::raw('date(piutang.created_at)'), [$this->tanggal_awal, $this->tanggal_akhir])
                                                ->join('tb_anggota', 'tb_anggota.id', '=', 'piutang.id_anggota')
                                                ->select('tb_anggota.kd_anggota AS kode_anggota', 'tb_anggota.nama_anggota AS nama_anggota',
                                                        'piutang.sisa_piutang')
                                                ->get();
        }
    }

    public function headings(): array {
        return ['Kode Anggota', 'Nama Anggota', 'Sisa Piutang'];
    }
}