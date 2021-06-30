<?php

namespace App\Exports\Toko\Laporan;

use App\Models\Toko\Transaksi\Hutang\HutangModel;
use App\Models\Toko\Transaksi\Pembelian\PembelianModel;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LaporanKasKeluarExport implements FromCollection, WithHeadings {
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $jenis_pemasukan, $tanggal_awal, $tanggal_akhir;

    function __construct($jenis_pemasukan, $tanggal_awal, $tanggal_akhir) {
        $this->jenis_pemasukan = $jenis_pemasukan;
        $this->tanggal_awal = $tanggal_awal;
        $this->tanggal_akhir = $tanggal_akhir;
    }

    public function collection() {
        if ($this->tanggal_awal && $this->tanggal_akhir) {
            if ($this->jenis_pemasukan == 2) {
                return PembelianModel::join('detail_beli', 'detail_beli.nomor', '=', 'pembelian.nomor')
                                                    ->join('jurnal', 'jurnal.nomor', '=', 'pembelian.nomor_jurnal')
                                                    ->leftJoin('supplier', 'supplier.id', '=', 'pembelian.id_supplier')
                                                    ->select('detail_beli.nomor AS nomor', 'pembelian.tanggal AS tanggal', 
                                                            'supplier.kode AS kode_supplier','supplier.nama AS nama_supplier', 
                                                            'jurnal.keterangan AS keterangan', 'detail_beli.total_harga AS jumlah_transaksi')
                                                    ->whereBetween('pembelian.tanggal', [$this->tanggal_awal, $this->tanggal_akhir])
                                                    ->where('pembayaran', '=', 2)
                                                    ->distinct()
                                                    ->get();
            } else if ($this->jenis_pemasukan == 1) {
                return HutangModel::join('detail_hutang', 'detail_hutang.id_hutang', '=', 'hutang.id')
                                                    ->join('jurnal', 'jurnal.nomor', '=', 'detail_hutang.nomor_jurnal')
                                                    ->leftJoin('supplier', 'supplier.id', '=', 'hutang.id_supplier')
                                                    ->select('detail_hutang.nomor AS nomor', 'detail_hutang.tanggal AS tanggal', 
                                                            'supplier.kode AS kode_supplier', 'supplier.nama AS nama_supplier', 
                                                            'jurnal.keterangan AS keterangan', 'detail_hutang.angsuran AS jumlah_transaksi')
                                                    ->whereBetween('detail_hutang.tanggal', [$this->tanggal_awal, $this->tanggal_akhir])
                                                    ->distinct()
                                                    ->get();
            } else {
                $laporan_kas_keluar_pembelian = PembelianModel::join('detail_beli', 'detail_beli.nomor', '=', 'pembelian.nomor')
                                                    ->join('jurnal', 'jurnal.nomor', '=', 'pembelian.nomor_jurnal')
                                                    ->leftJoin('supplier', 'supplier.id', '=', 'pembelian.id_supplier')
                                                    ->select('detail_beli.nomor AS nomor', 'pembelian.tanggal AS tanggal', 
                                                            'supplier.kode AS kode_supplier','supplier.nama AS nama_supplier', 
                                                            'jurnal.keterangan AS keterangan', 'detail_beli.total_harga AS jumlah_transaksi')
                                                    ->whereBetween('pembelian.tanggal', [$this->tanggal_awal, $this->tanggal_akhir])
                                                    ->where('pembayaran', '=', 2)
                                                    ->distinct();

                                                    
                return HutangModel::join('detail_hutang', 'detail_hutang.id_hutang', '=', 'hutang.id')
                                                    ->join('jurnal', 'jurnal.nomor', '=', 'detail_hutang.nomor_jurnal')
                                                    ->leftJoin('supplier', 'supplier.id', '=', 'hutang.id_supplier')
                                                    ->select('detail_hutang.nomor AS nomor', 'detail_hutang.tanggal AS tanggal', 
                                                            'supplier.kode AS kode_supplier', 'supplier.nama AS nama_supplier', 
                                                            'jurnal.keterangan AS keterangan', 'detail_hutang.angsuran AS jumlah_transaksi')
                                                    ->whereBetween('detail_hutang.tanggal', [$this->tanggal_awal, $this->tanggal_akhir])
                                                    ->distinct()
                                                    ->union($laporan_kas_keluar_pembelian)
                                                    ->get();
            }
        }
    }

    public function headings(): array {
        return ['Nomor Transaksi', 'Tanggal Transaksi', 'Kode Supplier', 
        'Nama Supplier', 'Keterangan', 'Jumlah Transaksi'];
    }
}