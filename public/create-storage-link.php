<?php
/**
 * Script untuk membuat symbolic link storage di cPanel.
 * 
 * Akses file ini dari browser: https://domain.com/create-storage-link.php
 * HAPUS FILE INI SETELAH BERHASIL!
 */

// Target: folder tempat foto sebenarnya berada
$target = __DIR__ . '/../storage/app/public';

// Link: folder "storage" di dalam folder public (tempat asset() mencari)
$link = __DIR__ . '/storage';

echo "<h2>Storage Link Creator</h2>";
echo "<pre>";

// Cek apakah target ada
if (!is_dir($target)) {
    echo "❌ ERROR: Target folder tidak ditemukan!\n";
    echo "   Path: $target\n";
    echo "   Pastikan folder storage/app/public/ ada di project Anda.\n";
    exit;
}

echo "✅ Target folder ditemukan: $target\n";

// Cek apakah link sudah ada
if (file_exists($link)) {
    if (is_link($link)) {
        echo "ℹ️  Symlink sudah ada: $link -> " . readlink($link) . "\n";
        echo "   Menghapus symlink lama...\n";
        unlink($link);
    } elseif (is_dir($link)) {
        echo "⚠️  Folder 'storage' sudah ada (bukan symlink).\n";
        echo "   Path: $link\n";
        echo "   Ini mungkin penyebab foto tidak tampil!\n";
        echo "   Menghapus folder ini dan menggantinya dengan symlink...\n";

        // Cek apakah folder kosong atau berisi file
        $files = array_diff(scandir($link), ['.', '..']);
        if (!empty($files)) {
            echo "   ⚠️  Folder berisi " . count($files) . " item.\n";
            echo "   Memindahkan isi ke target sebelum menghapus...\n";
            // Copy file yang ada ke target jika belum ada
            foreach ($files as $file) {
                $srcFile = $link . '/' . $file;
                $destFile = $target . '/' . $file;
                if (is_dir($srcFile)) {
                    if (!is_dir($destFile)) {
                        echo "   Menyalin folder: $file\n";
                        // Recursive copy
                        $iterator = new RecursiveIteratorIterator(
                            new RecursiveDirectoryIterator($srcFile, RecursiveDirectoryIterator::SKIP_DOTS),
                            RecursiveIteratorIterator::SELF_FIRST
                        );
                        if (!is_dir($destFile)) {
                            mkdir($destFile, 0755, true);
                        }
                        foreach ($iterator as $item) {
                            $dest = $destFile . '/' . $iterator->getSubPathname();
                            if ($item->isDir()) {
                                if (!is_dir($dest))
                                    mkdir($dest, 0755, true);
                            } else {
                                if (!file_exists($dest))
                                    copy($item, $dest);
                            }
                        }
                    }
                } elseif (!file_exists($destFile)) {
                    copy($srcFile, $destFile);
                    echo "   Menyalin file: $file\n";
                }
            }
        }

        // Hapus folder storage lama (recursive)
        $deleteDir = function ($dir) use (&$deleteDir) {
            $files = array_diff(scandir($dir), ['.', '..']);
            foreach ($files as $file) {
                $path = "$dir/$file";
                is_dir($path) ? $deleteDir($path) : unlink($path);
            }
            return rmdir($dir);
        };
        $deleteDir($link);
        echo "   ✅ Folder lama dihapus.\n";
    }
}

// Buat symlink
echo "\n🔗 Membuat symbolic link...\n";
echo "   Link: $link\n";
echo "   Target: $target\n\n";

if (@symlink($target, $link)) {
    echo "✅ BERHASIL! Symbolic link berhasil dibuat!\n";
    echo "   Foto-foto seharusnya sudah bisa tampil sekarang.\n";
} else {
    echo "❌ Symlink GAGAL. Server mungkin memblokir symlink.\n\n";
    echo "==========================================\n";
    echo "SOLUSI ALTERNATIF: Membuat .htaccess rewrite\n";
    echo "==========================================\n\n";

    // Jika symlink gagal, buat folder storage dan .htaccess redirect
    if (!is_dir($link)) {
        mkdir($link, 0755, true);
    }

    $htaccessContent = <<<'HTACCESS'
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ ../../storage/app/public/$1 [L]
HTACCESS;

    file_put_contents($link . '/.htaccess', $htaccessContent);
    echo "✅ .htaccess rewrite dibuat di $link/.htaccess\n";
    echo "   Ini akan me-redirect request ke folder storage yang benar.\n";
    echo "   Jika ini juga tidak berhasil, gunakan solusi PHP proxy di bawah.\n\n";

    // Buat PHP proxy sebagai fallback terakhir
    $proxyContent = <<<'PHP'
<?php
// Proxy untuk melayani file dari storage
$requestedFile = $_SERVER['PATH_INFO'] ?? ($_GET['file'] ?? '');
$requestedFile = ltrim($requestedFile, '/');

if (empty($requestedFile)) {
    http_response_code(404);
    exit('File not found');
}

$storagePath = realpath(__DIR__ . '/../../storage/app/public/' . $requestedFile);
$allowedBase = realpath(__DIR__ . '/../../storage/app/public');

// Security: pastikan path tidak keluar dari storage
if (!$storagePath || strpos($storagePath, $allowedBase) !== 0 || !is_file($storagePath)) {
    http_response_code(404);
    exit('File not found');
}

$mimeTypes = [
    'jpg' => 'image/jpeg',
    'jpeg' => 'image/jpeg',
    'png' => 'image/png',
    'gif' => 'image/gif',
    'webp' => 'image/webp',
];

$ext = strtolower(pathinfo($storagePath, PATHINFO_EXTENSION));
$mime = $mimeTypes[$ext] ?? 'application/octet-stream';

header('Content-Type: ' . $mime);
header('Content-Length: ' . filesize($storagePath));
header('Cache-Control: public, max-age=31536000');
readfile($storagePath);
exit;
PHP;

    file_put_contents($link . '/index.php', $proxyContent);
    echo "✅ PHP proxy juga dibuat di $link/index.php\n";
}

// Verifikasi
echo "\n==========================================\n";
echo "VERIFIKASI\n";
echo "==========================================\n";

$fotoDir = $target . '/progres-fisik-foto';
if (is_dir($fotoDir)) {
    $fotoFiles = array_diff(scandir($fotoDir), ['.', '..']);
    echo "📁 Folder progres-fisik-foto: " . count($fotoFiles) . " file ditemukan.\n";
    if (count($fotoFiles) > 0) {
        echo "   Contoh file: " . array_values($fotoFiles)[0] . "\n";
    }
} else {
    echo "❌ Folder progres-fisik-foto TIDAK DITEMUKAN di $fotoDir\n";
    echo "   Anda harus mengupload/menyalin foto ke folder ini!\n";
}

echo "\n⚠️  PENTING: HAPUS FILE INI (create-storage-link.php) SETELAH SELESAI!\n";
echo "</pre>";
