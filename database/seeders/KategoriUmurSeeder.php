<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriUmurSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['nama' => 'Bayi / Anak Balita',       'umur_min' => 0,  'umur_max' => 4,  'keterangan' => 'Bayi & balita'],
            ['nama' => 'Anak / Kanak-kanak',       'umur_min' => 5,  'umur_max' => 14, 'keterangan' => 'Sekolah dasar & menengah pertama'],
            ['nama' => 'Remaja / Pemuda',          'umur_min' => 15, 'umur_max' => 24, 'keterangan' => 'Sekolah menengah atas & awal dewasa'],
            ['nama' => 'Dewasa Muda',              'umur_min' => 25, 'umur_max' => 34, 'keterangan' => 'Dewasa awal, usia produktif'],
            ['nama' => 'Dewasa',                   'umur_min' => 35, 'umur_max' => 44, 'keterangan' => 'Usia produktif matang'],
            ['nama' => 'Paruh Baya / Menengah',    'umur_min' => 45, 'umur_max' => 54, 'keterangan' => 'Dewasa menengah'],
            ['nama' => 'Tua / Lanjut Usia Awal',   'umur_min' => 55, 'umur_max' => 64, 'keterangan' => 'Mendekati pensiun'],
            ['nama' => 'Lansia / Senior',          'umur_min' => 65, 'umur_max' => null, 'keterangan' => 'Usia pensiun & lanjut'],
        ];

        DB::table('kategori_umur')->insert($data);
    }
}
