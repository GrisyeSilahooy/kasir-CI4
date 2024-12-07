<?php

namespace App\Models;

use CodeIgniter\Model;

class Mpelanggan extends Model
{
    // Nama tabel yang digunakan dalam database
    protected $table = 'pelanggan';

    // Kolom primary key dari tabel
    protected $primaryKey = 'PelangganID';

    // Auto increment pada primary key
    protected $useAutoIncrement = true;

    // Tipe data yang dikembalikan
    protected $returnType = 'array';

    // Soft Deletes (false jika tidak digunakan)
    protected $useSoftDeletes = false;

    // Kolom yang diizinkan untuk diisi (mass-assignment)
    protected $allowedFields = ['NamaPelanggan', 'Alamat', 'NomorTelepon'];

    // Pengaturan timestamps otomatis
    protected $useTimestamps = true; // Aktifkan timestamps
    protected $dateFormat = 'datetime'; // Format waktu
    protected $createdField = 'created_at'; // Kolom untuk created_at
    protected $updatedField = 'updated_at'; // Kolom untuk updated_at
    protected $deletedField = 'deleted_at'; // Kolom untuk deleted_at (jika soft delete diaktifkan)

    // Validasi data
    protected $validationRules = [
        'NamaPelanggan' => 'required|min_length[3]|max_length[100]',
        'Alamat' => 'required|min_length[5]|max_length[255]',
        'NomorTelepon' => 'required|numeric|min_length[10]|max_length[15]',
    ];

    // Pesan error validasi
    protected $validationMessages = [
        'NamaPelanggan' => [
            'required' => 'Nama pelanggan harus diisi.',
            'min_length' => 'Nama pelanggan minimal {param} karakter.',
            'max_length' => 'Nama pelanggan maksimal {param} karakter.',
        ],
        'Alamat' => [
            'required' => 'Alamat harus diisi.',
            'min_length' => 'Alamat minimal {param} karakter.',
            'max_length' => 'Alamat maksimal {param} karakter.',
        ],
        'NomorTelepon' => [
            'required' => 'Nomor telepon harus diisi.',
            'numeric' => 'Nomor telepon harus berupa angka.',
            'min_length' => 'Nomor telepon minimal {param} digit.',
            'max_length' => 'Nomor telepon maksimal {param} digit.',
        ],
    ];

    // Validasi otomatis
    protected $skipValidation = false;
}
