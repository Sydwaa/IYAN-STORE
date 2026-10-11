CARA MENJALANKAN (VS Code + XAMPP)
1. Buka XAMPP Control Panel -> Start Apache dan MySQL (harus hijau).
2. Taruh folder "Web_Na_Akmal" di:  C:\xampp\htdocs\
3. Di VS Code: File > Open Folder > pilih C:\xampp\htdocs\Web_Na_Akmal
   (edit langsung di sana, simpan dengan Ctrl+S, lalu refresh browser)
4. Buka di browser (JANGAN klik dua kali file html / jangan pakai Live Server):
     http://localhost/Web_Na_Akmal/             (website)
     http://localhost/Web_Na_Akmal/admin.html   (kelola akun)
   Database "toko_ff" dibuat otomatis. Lihat datanya di http://localhost/phpmyadmin

YANG PERLU DIGANTI
- Password admin (bawaan admin123) -> config.php, baris ADMIN_PASSWORD
- Password MySQL (bawaan kosong)   -> config.php, baris DB_PASS
