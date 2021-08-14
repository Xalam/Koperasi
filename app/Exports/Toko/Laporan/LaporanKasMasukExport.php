<?php

namespace App\Exports\Toko\Laporan;

use App\Models\Toko\Transaksi\Penjualan\PenjualanModel;
use App\Models\Toko\Transaksi\Piutang\PiutangModel;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LaporanKasMasukExport implements FromCollection, WithHeadings {
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
                return PenjualanModel::join('detail_jual', 'detail_jual.nomor', '=', 'penjualan.nomor')
                                                    ->join('jurnal', 'jurnal.nomor', '=', 'penjualan.nomor_jurnal')
                                                    ->leftJoin('tb_anggota', 'tb_anggota.id', '=', 'penjualan.id_anggota')
                                                    ->select('detail_jual.nomor AS nomor', 'penjualan.tanggal AS tanggal', 
                                                            DB::raw('IFNULL(tb_anggota.kd_anggota, "Masyarakat Umum") AS kode_anggota'), 
                                                            DB::raw('IFNULL(tb_anggota.nama_anggota, "Masyarakat Umum") AS nama_anggota'), 
                                                            DB::raw('IFNULL(tb_anggota.jabatan, "-") AS status'), 
                                                            'jurnal.keterangan AS keterangan', 'detail_jual.total_harga AS jumlah_transaksi')
                                                    ->whereBetween('penjualan.tanggal', [$this->tanggal_awal, $this->tanggal_akhir])
                                                    ->where('pembayaran', '=', 2)
                                                    ->distinct()
                                                    ->get();
            } else if ($this->jenis_pemasukan == 1) {
                return PiutangModel::join('terima_piutang', 'terima_piutang.id_piutang', '=', 'piutang.id')
                                                    ->join('jurnal', 'jurnal.nomor', '=', 'terima_piutang.nomor_jurnal')
                                                    ->leftJoin('tb_anggota', 'tb_anggota.id', '=', 'piutang.id_anggota')
                                                    ->select('terima_piutang.nomor AS nomor', 'terima_piutang.tanggal AS tanggal', 
                                                            DB::raw('IFNULL(tb_anggota.kd_anggota, "Masyarakat Umum") AS kode_anggota'), 
                                                            DB::raw('IFNULL(tb_anggota.nama_anggota, "Masyarakat Umum") AS nama_anggota'), 
                                                            DB::raw('IFNULL(tb_anggota.jabatan, "-") AS status'), 
                                                            'jurnal.keterangan AS keterangan', 'terima_piutang.terima_piutang AS jumlah_transaksi')
                                                    ->whereBetween('terima_piutang.tanggal', [$this->tanggal_awal, $this->tanggal_akhir])
                                                    ->distinct()
                                                    ->get();
            } else {
                $laporan_kas_masuk_pembelian = PenjualanModel::join('detail_jual', 'detail_jual.nomor', '=', 'penjualan.nomor')
                                                    ->join('jurnal', 'jurnal.nomor', '=', 'penjualan.nomor_jurnal')
                                                    ->leftJoin('tb_anggota', 'tb_anggota.id', '=', 'penjualan.id_anggota')
                                                    ->select('detail_jual.nomor AS nomor', 'penjualan.tanggal AS tanggal', 
                                                            DB::raw('IFNULL(tb_anggota.kd_anggota, "Masyarakat Umum") AS kode_anggota'), 
                                                            DB::raw('IFNULL(tb_anggota.nama_anggota, "Masyarakat Umum") AS nama_anggota'), 
                                                            DB::raw('IFNULL(tb_anggota.jabatan, "-") AS status'), 
                                                            'jurnal.keterangan AS keterangan', 'detail_jual.total_harga AS jumlah_transaksi')
                                                    ->whereBetween('penjualan.tanggal', [$this->tanggal_awal, $this->tanggal_akhir])
                                                    ->where('pembayaran', '=', 2)
                                                    ->distinct();

                                                    
                return PiutangModel::join('terima_piutang', 'terima_piutang.id_piutang', '=', 'piutang.id')
                                                    ->join('jurnal', 'jurnal.nomor', '=', 'terima_piutang.nomor_jurnal')
                                                    ->leftJoin('tb_anggota', 'tb_anggota.id', '=', 'piutang.id_anggota')
                                                    ->select('terima_piutang.nomor AS nomor', 'terima_piutang.tanggal AS tanggal', 
                                                            DB::raw('IFNULL(tb_anggota.kd_anggota, "Masyarakat Umum") AS kode_anggota'), 
                                                            DB::raw('IFNULL(tb_anggota.nama_anggota, "Masyarakat Umum") AS nama_anggota'), 
                                                            DB::raw('IFNULL(tb_anggota.jabatan, "-") AS status'), 
                                                            'jurnal.keterangan AS keterangan', 'terima_piutang.terima_piutang AS jumlah_transaksi')
                                                    ->whereBetween('terima_piutang.tanggal', [$this->tanggal_awal, $this->tanggal_akhir])
                                                    ->distinct()
                                                    ->union($laporan_kas_masuk_pembelian)
                                                    ->get();
            }
        }
    }

    public function headings(): array {
        return ['Nomor Transaksi', 'Tanggal Transaksi', 'Kode Anggota', 
        'Nama Anggota', 'Status', 'Keterangan', 'Jumlah Transaksi'];
    }
}