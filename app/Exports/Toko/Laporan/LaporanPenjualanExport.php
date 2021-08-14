<?php

namespace App\Exports\Toko\Laporan;

use App\Models\Toko\Transaksi\Penjualan\PenjualanModel;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LaporanPenjualanExport implements FromCollection, WithHeadings {
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $tanggal_awal, $tanggal_akhir, $type_pembayaran;

    function __construct($tanggal_awal, $tanggal_akhir, $type_pembayaran) {
        $this->tanggal_awal = $tanggal_awal;
        $this->tanggal_akhir = $tanggal_akhir;
        $this->type_pembayaran = $type_pembayaran;
    }

    public function collection() {
        if ($this->tanggal_awal && !$this->tanggal_akhir) {
            if ($this->type_pembayaran > 0) {
                return PenjualanModel::where('penjualan.tanggal', '>=', $this->tanggal_awal)
                                                    ->where('pembayaran', '=', $this->type_pembayaran)
                                                    ->join('detail_jual', 'detail_jual.nomor', '=', 'penjualan.nomor')
                                                    ->join('barang', 'barang.id', '=', 'detail_jual.id_barang')
                                                    ->join('tb_anggota', 'tb_anggota.id', '=', 'penjualan.id_anggota')
                                                    ->select('detail_jual.nomor AS nomor', 'penjualan.tanggal AS tanggal', 
                                                            'tb_anggota.kd_anggota AS kode_anggota', 'tb_anggota.nama_anggota AS nama_anggota', 
                                                            'barang.kode AS kode', 'barang.nama AS nama',
                                                            'barang.harga_jual AS harga_jual', 'detail_jual.jumlah AS jumlah', 
                                                            'detail_jual.total_harga AS total_harga')
                                                    ->get();
            } else {
                return PenjualanModel::where('penjualan.tanggal', '>=', $this->tanggal_awal)
                                    ->join('detail_jual', 'detail_jual.nomor', '=', 'penjualan.nomor')
                                    ->join('barang', 'barang.id', '=', 'detail_jual.id_barang')
                                    ->join('tb_anggota', 'tb_anggota.id', '=', 'penjualan.id_anggota')
                                    ->select('detail_jual.nomor AS nomor', 'penjualan.tanggal AS tanggal', 
                                            'tb_anggota.kd_anggota AS kode_anggota', 'tb_anggota.nama_anggota AS nama_anggota', 
                                            'barang.kode AS kode', 'barang.nama AS nama',
                                            'barang.harga_jual AS harga_jual', 'detail_jual.jumlah AS jumlah', 
                                            'detail_jual.total_harga AS total_harga')
                                    ->get();
            }
        } else if (!$this->tanggal_awal && $this->tanggal_akhir) {
            if ($this->type_pembayaran > 0) {
                return PenjualanModel::where('penjualan.tanggal', '<=', $this->tanggal_akhir)
                                                    ->where('pembayaran', '=', $this->type_pembayaran)
                                                    ->join('detail_jual', 'detail_jual.nomor', '=', 'penjualan.nomor')
                                                    ->join('barang', 'barang.id', '=', 'detail_jual.id_barang')
                                                    ->join('tb_anggota', 'tb_anggota.id', '=', 'penjualan.id_anggota')
                                                    ->select('detail_jual.nomor AS nomor', 'penjualan.tanggal AS tanggal', 
                                                            'tb_anggota.kd_anggota AS kode_anggota', 'tb_anggota.nama_anggota AS nama_anggota', 
                                                            'barang.kode AS kode', 'barang.nama AS nama',
                                                            'barang.harga_jual AS harga_jual', 'detail_jual.jumlah AS jumlah', 
                                                            'detail_jual.total_harga AS total_harga')
                                                    ->get();
            } else {
                return PenjualanModel::where('penjualan.tanggal', '<=', $this->tanggal_akhir)
                                    ->join('detail_jual', 'detail_jual.nomor', '=', 'penjualan.nomor')
                                    ->join('barang', 'barang.id', '=', 'detail_jual.id_barang')
                                    ->join('tb_anggota', 'tb_anggota.id', '=', 'penjualan.id_anggota')
                                    ->select('detail_jual.nomor AS nomor', 'penjualan.tanggal AS tanggal', 
                                            'tb_anggota.kd_anggota AS kode_anggota', 'tb_anggota.nama_anggota AS nama_anggota', 
                                            'barang.kode AS kode', 'barang.nama AS nama',
                                            'barang.harga_jual AS harga_jual', 'detail_jual.jumlah AS jumlah', 
                                            'detail_jual.total_harga AS total_harga')
                                    ->get();
            }
        } else if ($this->tanggal_awal && $this->tanggal_akhir) {
            if ($this->type_pembayaran > 0) {
                return PenjualanModel::whereBetween('penjualan.tanggal', [$this->tanggal_awal, $this->tanggal_akhir])
                                                    ->where('pembayaran', '=', $this->type_pembayaran)
                                                    ->join('detail_jual', 'detail_jual.nomor', '=', 'penjualan.nomor')
                                                    ->join('barang', 'barang.id', '=', 'detail_jual.id_barang')
                                                    ->join('tb_anggota', 'tb_anggota.id', '=', 'penjualan.id_anggota')
                                                    ->select('detail_jual.nomor AS nomor', 'penjualan.tanggal AS tanggal', 
                                                            'tb_anggota.kd_anggota AS kode_anggota', 'tb_anggota.nama_anggota AS nama_anggota', 
                                                            'barang.kode AS kode', 'barang.nama AS nama',
                                                            'barang.harga_jual AS harga_jual', 'detail_jual.jumlah AS jumlah', 
                                                            'detail_jual.total_harga AS total_harga')
                                                    ->get();
            } else {
                return PenjualanModel::whereBetween('penjualan.tanggal', [$this->tanggal_awal, $this->tanggal_akhir])
                                                    ->join('detail_jual', 'detail_jual.nomor', '=', 'penjualan.nomor')
                                                    ->join('barang', 'barang.id', '=', 'detail_jual.id_barang')
                                                    ->join('tb_anggota', 'tb_anggota.id', '=', 'penjualan.id_anggota')
                                                    ->select('detail_jual.nomor AS nomor', 'penjualan.tanggal AS tanggal', 
                                                            'tb_anggota.kd_anggota AS kode_anggota', 'tb_anggota.nama_anggota AS nama_anggota', 
                                                            'barang.kode AS kode', 'barang.nama AS nama',
                                                            'barang.harga_jual AS harga_jual', 'detail_jual.jumlah AS jumlah', 
                                                            'detail_jual.total_harga AS total_harga')
                                                    ->get();
            }
        }
    }

    public function headings(): array {
        return ['Nomor Transaksi', 'Tanggal Transaksi', 'Kode Anggota', 'Nama Anggota'
        , 'Kode Barang', 'Nama Barang', 'Harga Jual', 'Jumlah Jual', 'Total Harga'];
    }
}