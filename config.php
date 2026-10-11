<?php
// ====== PENGATURAN ======
// Bawaan XAMPP: user "root", password kosong. Biasanya tidak perlu diubah.
const DB_HOST = 'localhost';
const DB_USER = 'root';
const DB_PASS = '';
const DB_NAME = 'toko_ff';          // dibuat otomatis kalau belum ada

// GANTI password admin ini sebelum website dipakai
const ADMIN_PASSWORD = 'admin123';

define('UPLOAD_DIR', __DIR__ . '/uploads');

// Membuat koneksi; database dan tabel dibuat otomatis saat pertama kali dipakai
function koneksi(): PDO {
    $pdo = new PDO('mysql:host=' . DB_HOST . ';charset=utf8mb4', DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    $pdo->exec('CREATE DATABASE IF NOT EXISTS `' . DB_NAME . '` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
    $pdo->exec('USE `' . DB_NAME . '`');
    $pdo->exec('CREATE TABLE IF NOT EXISTS akun (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nama VARCHAR(150) NOT NULL,
        level INT NOT NULL DEFAULT 0,
        `rank` VARCHAR(60) NOT NULL DEFAULT "",
        skin VARCHAR(255) NOT NULL DEFAULT "",
        harga INT NOT NULL DEFAULT 0,
        status ENUM("tersedia","terjual") NOT NULL DEFAULT "tersedia",
        gambar VARCHAR(120) NOT NULL DEFAULT "",
        dibuat TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) CHARACTER SET utf8mb4');
    return $pdo;
}
