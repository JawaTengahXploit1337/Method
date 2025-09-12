<?php
header('Content-Type: text/plain');

// Get IPv4
$ipv4_services = [
    'https://ipv4.icanhazip.com/',
    'https://v4.ident.me/',
    'https://api.ipify.org/'
];

$ipv4 = '';
foreach ($ipv4_services as $service) {
    $ip = @file_get_contents($service);
    if ($ip && filter_var(trim($ip), FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
        $ipv4 = trim($ip);
        break;
    }
}

// Get IPv6
$ipv6_services = [
    'https://ipv6.icanhazip.com/',
    'https://v6.ident.me/'
];

$ipv6 = '';
foreach ($ipv6_services as $service) {
    $ip = @file_get_contents($service);
    if ($ip && filter_var(trim($ip), FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
        $ipv6 = trim($ip);
        break;
    }
}

echo "IPv4 Address: " . ($ipv4 ?: 'Not available') . "\n";
echo "IPv6 Address: " . ($ipv6 ?: 'Not available') . "\n";
echo "Server IP: " . $_SERVER['SERVER_ADDR'] . "\n";
echo "Client IP: " . $_SERVER['REMOTE_ADDR'] . "\n";
?>
