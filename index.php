<?php
// Mulai sesi untuk menyimpan data
session_start();

// Set zona waktu ke WITA (Asia/Makassar)
date_default_timezone_set('Asia/Makassar');

// Cek apakah ada parameter `jawaban` di URL
if (isset($_GET['jawaban'])) {
    echo nl2br(file_get_contents('messages.txt'));
    exit();
}

// Cek apakah ada data post yang dikirim
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['name'])) {
        $_SESSION['name'] = $_POST['name']; // Simpan nama ke sesi
    } elseif (isset($_POST['message'])) {
        // Dapatkan waktu dan tanggal saat ini
        $currentDateTime = date('d-m-Y H:i:s');
        // Simpan pesan ke file dengan waktu dan tanggal
        file_put_contents('messages.txt', $_SESSION['name'] . " (" . $currentDateTime . "): " . $_POST['message'] . "\n", FILE_APPEND);
        echo "Pesan berhasil dikirim!";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>klik donk</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            text-align: center;
            margin-top: 50px;
        }

        #container {
            background-color: #fff;
            width: 80%;
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        img {
            width: 100%;
            height: auto;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }

        button:hover {
            background-color: #45a049;
        }

        form {
            margin-top: 20px;
        }

        #loading {
            font-size: 20px;
            color: #333;
        }

        #noButton {
            background-color: #f44336;
        }
    </style>
</head>
<body>
    <div id="container">
        <?php if (!isset($_SESSION['name'])): ?>
            <div id="loading">Loading dulu ya...</div>
            <div id="nameInput" style="display:none;">
                <form method="POST">
                    <label for="name">Masukkan Nama Kamu:</label>
                    <input type="text" id="name" name="name" required>
                    <button type="submit">Next</button>
                </form>
            </div>
        <?php else: ?>
            <div id="message1">
                <img id="dynamicImage" src="images/gif1.gif" alt="Gambar">
                <p>Hai <?= htmlspecialchars($_SESSION['name']); ?>!</p>
                <button onclick="showRomanticText(0)">Next</button>
            </div>
            <div id="romanticText" style="display:none;">
                <p id="romanticContent"></p>
                <button id="nextButton" onclick="nextRomanticText()">Next</button>
            </div>
            <div id="finalQuestion" style="display:none;">
                <p>Kamu mau gak jadi pacarku?</p>
                <button onclick="yesResponse()">Ya</button>
                <button id="noButton" onclick="noResponse()">Tidak</button>
            </div>
            <div id="sendMessage" style="display:none;">
                <form method="POST">
                    <label for="message">Kirim Pesan:</label>
                    <input type="text" id="message" name="message" required>
                    <button type="submit">Kirim</button>
                </form>
            </div>
        <?php endif; ?>
    </div>

    <script>
        // Tampilkan input nama setelah 2 detik
        <?php if (!isset($_SESSION['name'])): ?>
        setTimeout(() => {
            document.getElementById('loading').style.display = 'none';
            document.getElementById('nameInput').style.display = 'block';
        }, 2000);
        <?php endif; ?>

        const romanticTexts = [
            "Aku suka senyum kamu!",
            "Kamu bikin hariku jadi lebih baik.",
            "Jadi pacarku yuk, biar aku bisa buat kamu bahagia setiap hari!"
        ];
        let currentTextIndex = 0;

        function showRomanticText(index) {
            document.getElementById('message1').style.display = 'none';
            document.getElementById('romanticText').style.display = 'block';
            document.getElementById('romanticContent').textContent = romanticTexts[index];
            // Ganti gambar berdasarkan index atau logika lain
            document.getElementById('dynamicImage').src = 'images/image' + (index + 1) + '.gif'; // Contoh mengganti gambar dengan format GIF
        }

        function nextRomanticText() {
            currentTextIndex++;
            if (currentTextIndex < romanticTexts.length) {
                document.getElementById('romanticContent').textContent = romanticTexts[currentTextIndex];
                document.getElementById('dynamicImage').src = 'images/image' + (currentTextIndex + 1) + '.gif'; // Ganti gambar dengan format GIF
            } else {
                document.getElementById('romanticText').style.display = 'none';
                document.getElementById('finalQuestion').style.display = 'block';
            }
        }

        function yesResponse() {
            alert("Makasi ya udah mau jadi pacarku");
            document.getElementById('finalQuestion').style.display = 'none';
            document.getElementById('sendMessage').style.display = 'block';
        }

        function noResponse() {
            alert("Jangan ditolak dong :(");
            // Membuat tombol "tidak" menghindar
            let button = document.getElementById('noButton');
            button.style.position = 'absolute';
            button.style.left = Math.random() * 80 + '%';
            button.style.top = Math.random() * 80 + '%';
        }
    </script>
</body>
</html>
