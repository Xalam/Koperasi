<?php

namespace App\Exports\Toko\Laporan;

use App\Models\Simpan_Pinjam\Master\Anggota\Anggota;
use App\Models\Toko\Transaksi\Jurnal\JurnalModel;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LaporanAnggotaExport implements FromCollection, WithHeadings {
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
            return Anggota::join('penjualan', 'penjualan.id_anggota', '=', 'tb_anggota.id')
                                            ->select('tb_anggota.kd_anggota AS kode_anggota', 'tb_anggota.nama_anggota AS nama_anggota', 
                                                    DB::raw('SUM(penjualan.jumlah_harga) AS total_belanja'))
                                            ->groupBy('penjualan.id_anggota')
                                            ->whereBetween('penjualan.tanggal', [$this->tanggal_awal, $this->tanggal_akhir])
                                            ->orderBy('tb_anggota.nama_anggota')
                                            ->get();             
        }
    }

    public function headings(): array {
        return ['Kode Anggota', 'Nama Anggota', 'Total Belanja Toko'];
    }
}
