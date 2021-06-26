<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnggotaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_anggota', function (Blueprint $table) {
            $table->id();
            $table->string('kd_anggota');
            $table->string('nama_anggota');
            $table->enum('jenis_kelamin', ['Pria', 'Wanita']);
            $table->string('agama')->default('Lainnya');
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->longText('alamat');
            $table->string('no_hp');
            $table->string('no_wa');
            $table->string('foto');
            $table->string('status')->default('belum_kawin');
            $table->string('jabatan');
            $table->string('email');
            $table->string('username');
            $table->string('password');
            $table->string('role')->default('anggota')->comment('Hak akses pengguna');
            $table->double('gaji')->default(0);
            $table->double('limit_gaji')->default(0);
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
        Schema::dropIfExists('tb_anggota');
    }
}
