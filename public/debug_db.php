<?php
// ============================================
// STANDALONE DATABASE CONNECTION TESTER
// ============================================
session_start();

$host = $_POST['host'] ?? 'db04.kkp.go.id';
$port = $_POST['port'] ?? '3306';
$dbname = $_POST['dbname'] ?? 'kkpsamudrahostin_bioflok';
$username = $_POST['username'] ?? 'kkpsamudrahostin_bioflok';
$password = $_POST['password'] ?? 'RorenKKPBahari';

$message = '';
$messageColor = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        $pdo = new PDO($dsn, $username, $password, $options);

        $message = "✅ KONEKSI BERHASIL! <br> Kredensial ini BENAR dan divalidasi oleh MySQL.";
        $messageColor = "green";

        // Test query
        $stmt = $pdo->query("SHOW TABLES");
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        $message .= "<br><br><b>Tabel ditemukan (" . count($tables) . "):</b><br>" . implode("<br>", $tables);

    } catch (\PDOException $e) {
        $message = "❌ KONEKSI GAGAL: <br>" . $e->getMessage();
        $messageColor = "red";
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>MySQL Connection Tester</title>
    <style>
        body {
            font-family: Arial;
            margin: 40px;
            background: #f4f4f9;
        }

        .container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            max-width: 500px;
        }

        input {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            box-sizing: border-box;
        }

        button {
            background: #007bff;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background: #0056b3;
        }

        .alert {
            padding: 15px;
            margin-top: 20px;
            border-radius: 4px;
            color: white;
            word-break: break-all;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>🔍 Test Koneksi Database MySQL</h2>
        <p>Gunakan script ini untuk menguji apakah user MySQL cPanel Anda benar-benar bisa terhubung ke database tanpa
            melewati Laravel.</p>

        <form method="POST">
            <label>DB_HOST</label>
            <input type="text" name="host" value="<?php echo htmlspecialchars($host); ?>">

            <label>DB_PORT</label>
            <input type="text" name="port" value="<?php echo htmlspecialchars($port); ?>">

            <label>DB_DATABASE</label>
            <input type="text" name="dbname" value="<?php echo htmlspecialchars($dbname); ?>">

            <label>DB_USERNAME</label>
            <input type="text" name="username" value="<?php echo htmlspecialchars($username); ?>">

            <label>DB_PASSWORD</label>
            <input type="password" name="password" value="<?php echo htmlspecialchars($password); ?>">

            <button type="submit">Test Koneksi</button>
        </form>

        <?php if ($message): ?>
            <div class="alert" style="background: <?php echo $messageColor; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>