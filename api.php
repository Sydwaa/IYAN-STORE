<?php
require __DIR__ . '/config.php';
session_start();
header('Content-Type: application/json; charset=utf-8');

function kirim($data, int $kode = 200): void {
    http_response_code($kode);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}
function wajibLogin(): void {
    if (empty($_SESSION['admin'])) kirim(['error' => 'Belum login'], 401);
}
function hapusFile(?string $nama): void {
    if ($nama && is_file(UPLOAD_DIR . '/' . basename($nama))) unlink(UPLOAD_DIR . '/' . basename($nama));
}
// Simpan screenshot dengan nama otomatis (ff-<waktu>-<acak>.jpg). Mengembalikan nama file atau null.
function simpanGambar(): ?string {
    if (empty($_FILES['gambar']) || $_FILES['gambar']['error'] === UPLOAD_ERR_NO_FILE) return null;
    $f = $_FILES['gambar'];
    if ($f['error'] !== UPLOAD_ERR_OK) kirim(['error' => 'Gagal mengunggah gambar (cek ukuran file)'], 400);
    if ($f['size'] > 8 * 1024 * 1024) kirim(['error' => 'Ukuran gambar maksimal 8 MB'], 400);
    $ext = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'][mime_content_type($f['tmp_name'])] ?? null;
    if (!$ext) kirim(['error' => 'Format gambar harus JPG, PNG, atau WebP'], 400);
    $nama = 'ff-' . time() . '-' . bin2hex(random_bytes(3)) . '.' . $ext;
    if (!is_dir(UPLOAD_DIR)) mkdir(UPLOAD_DIR, 0755, true);
    move_uploaded_file($f['tmp_name'], UPLOAD_DIR . '/' . $nama);
    return $nama;
}

try {
    $db = koneksi();
} catch (PDOException $e) {
    kirim(['error' => 'Tidak bisa terhubung ke MySQL. Pastikan MySQL di XAMPP sudah Start.'], 500);
}

$aksi = $_GET['aksi'] ?? '';
$status = fn($s) => $s === 'terjual' ? 'terjual' : 'tersedia';

switch ($aksi) {
    case 'daftar':
        kirim($db->query('SELECT * FROM akun ORDER BY id DESC')->fetchAll());

    case 'cek':
        kirim(['login' => !empty($_SESSION['admin'])]);

    case 'login':
        if (!hash_equals(ADMIN_PASSWORD, (string)($_POST['password'] ?? ''))) kirim(['error' => 'Password salah'], 401);
        session_regenerate_id(true);
        $_SESSION['admin'] = true;
        kirim(['ok' => true]);

    case 'logout':
        $_SESSION = [];
        session_destroy();
        kirim(['ok' => true]);

    case 'tambah':
        wajibLogin();
        $nama = trim($_POST['nama'] ?? '');
        if ($nama === '') kirim(['error' => 'Nama akun wajib diisi'], 400);
        $gambar = simpanGambar() ?? '';
        $db->prepare('INSERT INTO akun (nama, level, `rank`, skin, harga, status, gambar) VALUES (?,?,?,?,?,?,?)')
           ->execute([$nama, (int)($_POST['level'] ?? 0), trim($_POST['rank'] ?? ''), trim($_POST['skin'] ?? ''),
                      (int)($_POST['harga'] ?? 0), $status($_POST['status'] ?? ''), $gambar]);
        kirim(['id' => (int)$db->lastInsertId()]);

    case 'ubah':
        wajibLogin();
        $st = $db->prepare('SELECT * FROM akun WHERE id = ?');
        $st->execute([(int)($_POST['id'] ?? 0)]);
        $lama = $st->fetch();
        if (!$lama) kirim(['error' => 'Akun tidak ditemukan'], 404);
        $baru = simpanGambar();
        if ($baru) hapusFile($lama['gambar']);
        $db->prepare('UPDATE akun SET nama=?, level=?, `rank`=?, skin=?, harga=?, status=?, gambar=? WHERE id=?')
           ->execute([
               $_POST['nama'] ?? $lama['nama'], (int)($_POST['level'] ?? $lama['level']),
               $_POST['rank'] ?? $lama['rank'], $_POST['skin'] ?? $lama['skin'],
               (int)($_POST['harga'] ?? $lama['harga']), $status($_POST['status'] ?? $lama['status']),
               $baru ?? $lama['gambar'], $lama['id'],
           ]);
        kirim(['ok' => true]);

    case 'hapus':
        wajibLogin();
        $st = $db->prepare('SELECT gambar FROM akun WHERE id = ?');
        $st->execute([(int)($_POST['id'] ?? 0)]);
        $lama = $st->fetch();
        if (!$lama) kirim(['error' => 'Akun tidak ditemukan'], 404);
        hapusFile($lama['gambar']);
        $db->prepare('DELETE FROM akun WHERE id = ?')->execute([(int)$_POST['id']]);
        kirim(['ok' => true]);

    default:
        kirim(['error' => 'Aksi tidak dikenal'], 400);
}
