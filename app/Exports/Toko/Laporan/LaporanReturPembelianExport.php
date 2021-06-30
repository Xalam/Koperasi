<?php

namespace App\Exports\Toko\Laporan;

use App\Models\Toko\Transaksi\Retur\ReturPembelianModel;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LaporanReturPembelianExport implements FromCollection, WithHeadings {
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
            return ReturPembelianModel::whereBetween('retur.tanggal', [$this->tanggal_awal, $this->tanggal_akhir])
                                                ->join('detail_retur', 'detail_retur.nomor', '=', 'retur.nomor')
                                                ->join('pembelian', 'pembelian.id', '=', 'retur.id_beli')
                                                ->join('barang', 'barang.id', '=', 'detail_retur.id_barang')
                                                ->select('retur.nomor AS nomor', 'retur.tanggal', 'pembelian.nomor AS nomor_beli',
                                                        'barang.kode AS kode_barang', 'barang.nama AS nama_barang', 
                                                        'barang.hpp AS hpp', 'detail_retur.jumlah AS jumlah', 
                                                        'detail_retur.total_harga AS total_harga')
                                                ->get();
        }
    }

    public function headings(): array {
        return ['Nomor Retur', 'Tanggal Retur', 'Nomor Beli', 'Kode Barang', 
        'Nama Barang', 'HPP', 'Jumlah Retur', 'Total Harga'];
    }
}
