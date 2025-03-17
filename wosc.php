<?php
error_reporting(0);
require 'functions.php';
require 'var.php';
echo $cln;

function perbarui()
{
    global $cln, $bold, $fgreen;
    echo "\n\e[91m\e[1m[+] ALAT PEMBARUAN RED HAWK [+]\nProses pembaruan berlangsung, harap tunggu...\n\n$cln";
    system("git fetch origin && git reset --hard origin/master && git clean -f -d");
    echo $bold . $fgreen . "[i] Pembaruan selesai! Silakan jalankan ulang RED HAWK.\n" . $cln;
    exit;
}

system("clear");
redhawk_banner();

if (!extension_loaded('curl') || !extension_loaded('dom')) {
    if (!extension_loaded('curl')) {
        echo $bold . $red . "\n[!] Modul cURL belum terinstal! Gunakan perintah 'fix' atau instal php-curl.\n" . $cln;
    }
    if (!extension_loaded('dom')) {
        echo $bold . $red . "\n[!] Modul DOM tidak ditemukan! Jalankan 'fix' atau instal php-xml.\n" . $cln;
    }
}

mulai:
echo "\n";
userinput("Masukkan Website yang ingin di-scan");
$ip = trim(fgets(STDIN, 1024));

if ($ip == "bantuan") {
    echo "\n\n[+] Menu Bantuan RED HAWK [+] \n\n";
    echo $bold . $lblue . "Perintah Tersedia\n";
    echo "===================\n";
    echo $fgreen . "[1] bantuan:$cln Lihat menu bantuan.\n";
    echo $bold . $fgreen . "[2] fix:$cln Instal modul yang dibutuhkan.\n";
    echo $bold . $fgreen . "[3] URL:$cln Masukkan domain untuk di-scan.\n";
    echo $bold . $fgreen . "[4] update:$cln Memperbarui script ke versi terbaru.\n";
    goto mulai;
} elseif ($ip == "fix") {
    echo "\n[+] MENU PERBAIKAN RED HAWK [+]\n\n";
    if (!extension_loaded('curl')) {
        echo "[!] Modul cURL belum terinstal! Menginstal...\n";
        system("sudo apt-get -qq --assume-yes install php-curl");
        echo "[i] Modul cURL berhasil diinstal.\n";
    } else {
        echo "[i] Modul cURL sudah terinstal.\n";
    }

    if (!extension_loaded('dom')) {
        echo "[!] Modul php-XML belum terinstal! Menginstal...\n";
        system("sudo apt-get -qq --assume-yes install php-xml");
        echo "[i] Modul DOM berhasil diinstal.\n";
    } else {
        echo "[i] Modul php-XML sudah terinstal.\n";
    }
    echo "[i] Instalasi selesai! Jalankan ulang RED HAWK.\n";
    exit;
} elseif ($ip == "update") {
    perbarui();
} elseif (strpos($ip, '://') !== false || strpos($ip, '.') === false || strpos($ip, ' ') !== false) {
    echo "[!] Format URL tidak valid! Gunakan format: domain.com tanpa http/https\n";
    goto mulai;
} else {
    userinput("Masukkan 1 untuk HTTP atau 2 untuk HTTPS");
    $ipsl = trim(fgets(STDIN, 1024));
    $ipsl = ($ipsl == "2") ? "https://" : "http://";
}

menu_scan:
system("clear");
echo "
      +--------------------------------------------------------------+
      +                  DAFTAR SCAN ATAU AKSI                       +
      +--------------------------------------------------------------+

      Website Target : $ipsl$ip\n";

echo " [0] Recon Dasar (Judul, IP, CMS, Cloudflare, Robots.txt)
 [1] Whois Lookup
 [2] Geo-IP Lookup
 [3] Ambil Banner HTTP
 [4] DNS Lookup
 [5] Kalkulator Subnet
 [6] Scan Port (NMAP)
 [7] Scan Subdomain
 [8] Reverse IP & Deteksi CMS
 [9] Scanner SQL Injection
 [10] Informasi Blogger
 [11] Scan WordPress
 [12] Web Crawler
 [13] MX Lookup
 [A] Scan Semua Sekaligus
 [F] Perbaikan Modul (Fix)
 [U] Cek Update
 [B] Scan Website Lain
 [Q] Keluar
";

pilih_scan:
userinput("Pilih jenis scan dari daftar di atas");
$scan = trim(fgets(STDIN, 1024));

switch (strtolower($scan)) {
    case 'q':
        echo "\nTerima kasih, sampai jumpa!\n\n";
        exit;
    case 'b':
        goto mulai;
    case 'u':
        perbarui();
        break;
    case 'f':
        echo "\nGunakan perintah 'fix' di menu utama.\n";
        goto menu_scan;
    case '0':
        $link = $ipsl . $ip;
        echo "\n[+] Memulai Recon Dasar pada: $link\n";
        echo "Judul Situs : " . getTitle($link) . "\n";
        echo "IP Address  : " . gethostbyname($ip) . "\n";
        echo "Server Web  : "; WEBserver($link);
        echo "CMS         : " . CMSdetect($link) . "\n";
        echo "Cloudflare  : "; cloudflaredetect($ip);
        echo "Robots File : "; robotsdottxt($link);
        break;

    // Tambahkan scan lain disini seperti contoh scan dasar di atas.

    default:
        echo "\n[!] Pilihan tidak valid! Pilih opsi yang tersedia.\n";
        goto menu_scan;
}

echo "\n[*] Scan selesai. Tekan Enter untuk lanjut.\n";
trim(fgets(STDIN, 1024));
goto menu_scan;

?>
