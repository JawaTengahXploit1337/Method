<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Port Checker</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background-color: #f5f5f5;
        }
        h1 {
            color: #333;
        }
        table {
            width: 70%;
            margin: 0 auto;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        th, td {
            padding: 15px;
            border: 1px solid #ddd;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        .on {
            color: green;
            font-weight: bold;
        }
        .off {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h1>Status Port Server</h1>
    <table>
        <tr>
            <th>Port</th>
            <th>Deskripsi</th>
            <th>Status</th>
        </tr>
        <?php
        $ports = [
            21 => "FTP",
            22 => "SSH",
            23 => "Telnet",
            25 => "SMTP",
            53 => "DNS",
            80 => "HTTP",
            110 => "POP3",
            115 => "SFTP",
            135 => "RPC",
            139 => "NetBIOS",
            143 => "IMAP",
            194 => "IRC",
            443 => "SSL/HTTPS",
            445 => "SMB",
            1433 => "MSSQL",
            3306 => "MySQL",
            3389 => "Remote Desktop",
            5632 => "PCAnywhere",
            5900 => "VNC",
            25565 => "Minecraft"
        ];

        $host = '127.0.0.1'; // Ubah ke IP atau hostname lain jika diperlukan

        foreach ($ports as $port => $description) {
            $connection = @fsockopen($host, $port, $errno, $errstr, 2);
            if ($connection) {
                echo "<tr><td>$port</td><td>$description</td><td class='on'>ON</td></tr>";
                fclose($connection);
            } else {
                echo "<tr><td>$port</td><td>$description</td><td class='off'>OFF</td></tr>";
            }
        }
        ?>
    </table>
</body>
</html>
