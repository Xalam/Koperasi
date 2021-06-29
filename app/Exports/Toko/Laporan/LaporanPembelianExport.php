<?php

namespace App\Exports\Toko\Laporan;

use App\Models\Toko\Transaksi\Pembelian\PembelianModel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LaporanPembelianExport implements FromCollection, WithHeadings {
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
                return PembelianModel::where('pembelian.tanggal', '>=', $this->tanggal_awal)
                                                    ->where('pembayaran', '=', $this->type_pembayaran)
                                                    ->join('detail_beli', 'detail_beli.nomor', '=', 'pembelian.nomor')
                                                    ->join('barang', 'barang.id', '=', 'detail_beli.id_barang')
                                                    ->join('supplier', 'supplier.id', '=', 'pembelian.id_supplier')
                                                    ->select('detail_beli.nomor AS nomor', 'pembelian.tanggal AS tanggal', 
                                                            'supplier.nama AS supplier', 'barang.kode AS kode', 'barang.nama AS nama',
                                                            'barang.harga_jual AS harga_jual', 'detail_beli.harga_satuan AS harga_satuan', 
                                                            'detail_beli.jumlah AS jumlah', 'detail_beli.total_harga AS total_harga')
                                                    ->get();
            } else {
                return PembelianModel::where('pembelian.tanggal', '>=', $this->tanggal_awal)
                                    ->join('detail_beli', 'detail_beli.nomor', '=', 'pembelian.nomor')
                                    ->join('barang', 'barang.id', '=', 'detail_beli.id_barang')
                                    ->join('supplier', 'supplier.id', '=', 'pembelian.id_supplier')
                                    ->select('detail_beli.nomor AS nomor', 'pembelian.tanggal AS tanggal', 
                                            'supplier.nama AS supplier', 'barang.kode AS kode', 'barang.nama AS nama',
                                            'barang.harga_jual AS harga_jual', 'detail_beli.harga_satuan AS harga_satuan', 
                                            'detail_beli.jumlah AS jumlah', 'detail_beli.total_harga AS total_harga')
                                    ->get();
            }
        } else if (!$this->tanggal_awal && $this->tanggal_akhir) {
            if ($this->type_pembayaran > 0) {
                return PembelianModel::where('pembelian.tanggal', '<=', $this->tanggal_akhir)
                                                    ->where('pembayaran', '=', $this->type_pembayaran)
                                                    ->join('detail_beli', 'detail_beli.nomor', '=', 'pembelian.nomor')
                                                    ->join('barang', 'barang.id', '=', 'detail_beli.id_barang')
                                                    ->join('supplier', 'supplier.id', '=', 'pembelian.id_supplier')
                                                    ->select('detail_beli.nomor AS nomor', 'pembelian.tanggal AS tanggal', 
                                                            'supplier.nama AS supplier', 'barang.kode AS kode', 'barang.nama AS nama',
                                                            'barang.harga_jual AS harga_jual', 'detail_beli.harga_satuan AS harga_satuan', 
                                                            'detail_beli.jumlah AS jumlah', 'detail_beli.total_harga AS total_harga')
                                                    ->get();
            } else {
                return PembelianModel::where('pembelian.tanggal', '<=', $this->tanggal_akhir)
                                    ->join('detail_beli', 'detail_beli.nomor', '=', 'pembelian.nomor')
                                    ->join('barang', 'barang.id', '=', 'detail_beli.id_barang')
                                    ->join('supplier', 'supplier.id', '=', 'pembelian.id_supplier')
                                    ->select('detail_beli.nomor AS nomor', 'pembelian.tanggal AS tanggal', 
                                            'supplier.nama AS supplier', 'barang.kode AS kode', 'barang.nama AS nama',
                                            'barang.harga_jual AS harga_jual', 'detail_beli.harga_satuan AS harga_satuan', 
                                            'detail_beli.jumlah AS jumlah', 'detail_beli.total_harga AS total_harga')
                                    ->get();
            }
        } else if ($this->tanggal_awal && $this->tanggal_akhir) {
            if ($this->type_pembayaran > 0) {
                return PembelianModel::whereBetween('pembelian.tanggal', [$this->tanggal_awal, $this->tanggal_akhir])
                                                    ->where('pembayaran', '=', $this->type_pembayaran)
                                                    ->join('detail_beli', 'detail_beli.nomor', '=', 'pembelian.nomor')
                                                    ->join('barang', 'barang.id', '=', 'detail_beli.id_barang')
                                                    ->join('supplier', 'supplier.id', '=', 'pembelian.id_supplier')
                                                    ->select('detail_beli.nomor AS nomor', 'pembelian.tanggal AS tanggal', 
                                                            'supplier.nama AS supplier', 'barang.kode AS kode', 'barang.nama AS nama',
                                                            'barang.harga_jual AS harga_jual', 'detail_beli.harga_satuan AS harga_satuan', 
                                                            'detail_beli.jumlah AS jumlah', 'detail_beli.total_harga AS total_harga')
                                                    ->get();
            } else {
                return PembelianModel::whereBetween('pembelian.tanggal', [$this->tanggal_awal, $this->tanggal_akhir])
                                                    ->join('detail_beli', 'detail_beli.nomor', '=', 'pembelian.nomor')
                                                    ->join('barang', 'barang.id', '=', 'detail_beli.id_barang')
                                                    ->join('supplier', 'supplier.id', '=', 'pembelian.id_supplier')
                                                    ->select('detail_beli.nomor AS nomor', 'pembelian.tanggal AS tanggal', 
                                                            'supplier.nama AS supplier', 'barang.kode AS kode', 'barang.nama AS nama',
                                                            'barang.harga_jual AS harga_jual', 'detail_beli.harga_satuan AS harga_satuan', 
                                                            'detail_beli.jumlah AS jumlah', 'detail_beli.total_harga AS total_harga')
                                                    ->get();
            }
        }
    }

    public function headings(): array {
        return ['Nomor Transaksi', 'Tanggal Transaksi', 'Nama Supplier', 'Kode Barang', 
        'Nama Barang', 'Harga Jual', 'Harga Beli', 'Jumlah Jual', 'Total Harga'];
    }
}
