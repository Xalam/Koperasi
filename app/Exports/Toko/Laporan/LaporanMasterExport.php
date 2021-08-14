<?php

namespace App\Exports\Toko\Laporan;

use App\Models\Simpan_Pinjam\Master\Anggota\Anggota;
use App\Models\Toko\Master\Admin\AdminModel;
use App\Models\Toko\Master\Anggota\AnggotaModel;
use App\Models\Toko\Master\Barang\BarangModel;
use App\Models\Toko\Master\Supplier\SupplierModel;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LaporanMasterExport implements FromCollection, WithHeadings {
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $bagian;

    function __construct($bagian) {
        $this->bagian = $bagian;
    }

    public function collection() {
        if ($this->bagian == "Admin") {
            return AdminModel::select('kode', 'nama', 'jabatan')->get();
        } else if ($this->bagian == "Barang") {
            return BarangModel::select('kode', 'nama', 'hpp', 'harga_jual', 'satuan', 'stok_etalase','stok_gudang')->get();
        } else if ($this->bagian == "Anggota") {
            return Anggota::select('kd_anggota', 'nama_anggota', 'jabatan', 'alamat', 'no_hp', 'no_wa', 'status')->get();
        } else {
            return SupplierModel::select('kode', 'nama', 'alamat', 'telepon', 'wa', 'tempo')->get();
        }
    }

    public function headings(): array {
        
        if ($this->bagian == "Admin") {
            return ['Kode Admin', 'Nama Admin', 'Jabatan'];
        } else if ($this->bagian == "Barang") {
            return ['Kode Barang', 'Nama Barang', 'HPP', 'Harga Jual', 'Satuan', 'Stok Etalase', 'Stok Gudang'];
        } else if ($this->bagian == "Anggota") {
            return ['Kode Anggota', 'Nama Anggota', 'Jabatan', 'Alamat', 'Telepon', 'WA', 'Status'];
        } else {
            return ['Kode Supplier', 'Nama Supplier', 'Alamat', 'Telepon', 'WA', 'Tempo'];
        }
    }
}
