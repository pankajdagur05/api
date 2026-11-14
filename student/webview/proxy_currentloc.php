<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

include("db.php");
include("lib_transport.php"); 

$payload = transport_build_feed($host);
$host->close();

transport_json_output($payload, $payload['success'] ? 200 : 502);
