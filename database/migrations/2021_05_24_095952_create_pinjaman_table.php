<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePinjamanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_pinjaman', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pinjaman');
            $table->integer('id_anggota');
            $table->date('tanggal');
            $table->double('nominal_pinjaman');
            $table->double('bunga');
            $table->integer('tenor');
            $table->double('total_pinjaman');
            $table->double('nominal_angsuran');
            $table->integer('angsuran_ke')->default(0);
            $table->double('biaya_provisi')->default(0);
            $table->double('biaya_asuransi')->default(0);
            $table->integer('lunas')->default(0)->comment('0 = Belum Lunas, 1 = Lunas');
            $table->integer('status')->default(0)->comment('0 = Waiting, 1 = Acc, 2 = Pencairan');
            $table->string('kode_jurnal')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_pinjaman');
    }
}
