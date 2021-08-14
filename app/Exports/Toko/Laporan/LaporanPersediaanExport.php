<?php

namespace App\Exports\Toko\Laporan;

use App\Models\Toko\Master\Barang\BarangModel;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LaporanPersediaanExport implements FromCollection, WithHeadings {
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $minimal_stok;

    function __construct($minimal_stok) {
        $this->minimal_stok = $minimal_stok;
    }

    public function collection() {
        return BarangModel::select('kode', 'nama', DB::raw('(stok_etalase + stok_gudang) AS stok'), 
                                    'hpp', 'harga_jual', DB::raw('((stok_etalase + stok_gudang) * hpp) AS jumlah_hpp'), 
                                    DB::raw('((stok_etalase + stok_gudang) * harga_jual) AS jumlah_harga_jual'))
                                ->groupBy('nama')
                                ->orderBy('nama')
                                ->having('stok', '<', $this->minimal_stok)
                                ->get();
    }

    public function headings(): array {
        return ['Kode Barang', 'Nama Barang', 'Stok', 'HPP', 'Harga Jual', 
        'Jumlah HPP', 'Jumlah Harga Jual'];
    }
}
