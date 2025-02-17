<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil nilai had maksimum daripada borang
    $sports_limit = $_POST['sports-limit'];
    $uniform_limit = $_POST['uniform-limit'];

    // Pastikan nilai had maksimum adalah nombor yang sah
    if (is_numeric($sports_limit) && is_numeric($uniform_limit) && $sports_limit > 0 && $uniform_limit > 0) {
        // Simpan had maksimum dalam fail atau pangkalan data (contoh: fail teks)
        file_put_contents("settings.txt", "Sukan & Permainan: $sports_limit\nUnit Beruniform: $uniform_limit\n");

        $message = "Tetapan had pendaftaran telah dikemaskini!";
    } else {
        $message = "Sila masukkan nilai yang sah untuk had maksimum.";
    }
}

// Dapatkan nilai had yang disimpan (jika ada)
$settings = file_get_contents("settings.txt");
$settingsArray = [];
if ($settings) {
    foreach (explode("\n", $settings) as $line) {
        $parts = explode(": ", $line);
        if (count($parts) == 2) {
            $settingsArray[$parts[0]] = (int)$parts[1];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengurusan Pendaftaran Kokurikulum - Admin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: 0 auto;
        }

        header {
            background-color: #333;
            color: #fff;
            padding: 20px;
            text-align: center;
        }

        h1 {
            margin: 0;
        }

        .admin-controls {
            background-color: #fff;
            padding: 20px;
            margin-top: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #333;
        }

        label {
            display: block;
            margin-bottom: 10px;
        }

        input[type="number"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        button.submit-btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button.submit-btn:hover {
            background-color: #45a049;
        }

        .message {
            margin-top: 15px;
            color: green;
        }

        .error {
            color: red;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Pengurusan Pendaftaran Kokurikulum - Admin</h1>
        </header>

        <div class="admin-controls">
            <h2>Tetapan Had Pendaftaran</h2>

            <?php if (isset($message)) { ?>
                <p class="message"><?= $message; ?></p>
            <?php } ?>

            <form action="admin_dashboard.php" method="post">
                <label for="sports-limit">Had Maksimum Sukan & Permainan:</label>
                <input type="number" id="sports-limit" name="sports-limit" min="1" max="100" value="<?= isset($settingsArray['Sukan & Permainan']) ? $settingsArray['Sukan & Permainan'] : ''; ?>" required>

                <label for="uniform-limit">Had Maksimum Unit Beruniform:</label>
                <input type="number" id="uniform-limit" name="uniform-limit" min="1" max="100" value="<?= isset($settingsArray['Unit Beruniform']) ? $settingsArray['Unit Beruniform'] : ''; ?>" required>

                <button type="submit" class="submit-btn">Kemaskini Tetapan</button>
            </form>
        </div>
    </div>
</body>
</html>
