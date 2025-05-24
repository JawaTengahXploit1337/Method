<!DOCTYPE html>
<html>
<head>
    <title>Cek GCC & Python</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; padding-top: 50px; }
        .status { font-size: 24px; margin: 20px; }
        .on { color: green; }
        .off { color: red; }
    </style>
</head>
<body>

<h1>Status Checker</h1>

<?php
function isAvailable($command) {
    $output = null;
    $return_var = null;
    exec("command -v $command", $output, $return_var);
    return $return_var === 0;
}

$gcc_status = isAvailable("gcc") ? "<span class='on'>GCC: ON</span>" : "<span class='off'>GCC: OFF</span>";
$python_status = (isAvailable("python3") || isAvailable("python")) ? "<span class='on'>Python: ON</span>" : "<span class='off'>Python: OFF</span>";

echo "<div class='status'>$gcc_status</div>";
echo "<div class='status'>$python_status</div>";
?>

</body>
</html>
