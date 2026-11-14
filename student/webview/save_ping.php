<?php
/**
 * save_ping.php
 * Cron: har 1 minute — GPS history save karta hai (duplicate ping skip).
 *   * * * * * wget -q -O /dev/null "https://SCHOOL.edpluss.com/transport/save_ping.php?key=CRON_KEY"
 */
declare(strict_types=1);

define('TRANSPORT_CRON', true);

require_once __DIR__ . '/transport_config.php';
require_once __DIR__ . '/transport_lib.php';

transport_require_cron();
header('Content-Type: text/plain; charset=utf-8');

$cfg = transport_settings();
$cid = transport_cid();
$list = fetch_wheelseye_list($cfg);

if ($list === null) {
    exit("No data found\n");
}

$stmt = transport_db()->prepare(
    'INSERT IGNORE INTO vehicle_ping
        (cid, vehicle_number, device_number, latitude, longitude, speed, ignition, created_epoch, created_readable, location)
     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
);

$saved = 0;
foreach ($list as $v) {
    $vehicleNumber = (string) ($v['vehicleNumber'] ?? '');
    if ($vehicleNumber === '') {
        continue;
    }
    $deviceNumber = (string) ($v['deviceNumber'] ?? '');
    $lat = (string) ($v['latitude'] ?? '0');
    $lng = (string) ($v['longitude'] ?? '0');
    $speed = (string) ($v['speed'] ?? '0');
    $ignition = !empty($v['ignition']) ? 1 : 0;
    $epoch = (string) ($v['createdDate'] ?? '');
    $readable = (string) ($v['createdDateReadable'] ?? '');
    $location = (string) ($v['location'] ?? '');

    $stmt->bind_param('ssssssisss', $cid, $vehicleNumber, $deviceNumber, $lat, $lng, $speed, $ignition, $epoch, $readable, $location);
    $stmt->execute();
    $saved += $stmt->affected_rows > 0 ? 1 : 0;
}
$stmt->close();

echo "Data saved successfully! New pings: $saved\n";