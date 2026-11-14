<?php
header('Content-Type: application/json; charset=utf-8');

$bus_lat = $_GET['bus_lat'];
$bus_lon = $_GET['bus_lon'];
$stop_lat = $_GET['stop_lat'];
$stop_lon = $_GET['stop_lon'];
$school_lat = $_GET['school_lat'];
$school_lon = $_GET['school_lon'];

function getDistance($originLat, $originLon, $destLat, $destLon) {
    $url = "https://routes.googleapis.com/distanceMatrix/v2:computeRouteMatrix";

    $data = [
        "origins" => [["waypoint" => ["location" => ["latLng" => ["latitude" => (float)$originLat, "longitude" => (float)$originLon]]]]],
        "destinations" => [["waypoint" => ["location" => ["latLng" => ["latitude" => (float)$destLat, "longitude" => (float)$destLon]]]]],
        "travelMode" => "DRIVE",
        "routingPreference" => "TRAFFIC_AWARE" // Traffic ko dhyan mein rakhne ke liye
    ];

    $headers = [
        "Content-Type: application/json",
        "X-Goog-Api-Key: AIzaSyA4DcfhR1ziaFXYe6Eilip65TldRpSjSHQ", // Ensure this is valid
        "X-Goog-FieldMask: distanceMeters,duration,status"
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    $response = curl_exec($ch);
    curl_close($ch);

    $arr = json_decode($response, true);
    
    if (!empty($arr[0]['distanceMeters'])) {
        // FIX: Duration string "300s" ko integer 300 mein badalna
        $duration_str = $arr[0]['duration'] ?? "0s";
        $duration_seconds = (int)str_replace('s', '', $duration_str); 
        
        return [
            "distance_m" => $arr[0]['distanceMeters'],
            "duration_s" => $duration_seconds
        ];
    }
    return ["distance_m" => 0, "duration_s" => 0];
}

$bus_to_stop = getDistance($bus_lat, $bus_lon, $stop_lat, $stop_lon);
$stop_to_school = getDistance($stop_lat, $stop_lon, $school_lat, $school_lon);

// Realistic ETA: Agar distance 0 hai toh ETA bhi 0
$eta_bus_stop = ($bus_to_stop['duration_s'] > 0) ? ceil($bus_to_stop['duration_s'] / 60) : 0;
$eta_stop_school = ($stop_to_school['duration_s'] > 0) ? ceil($stop_to_school['duration_s'] / 60) : 0;

echo json_encode([
    "bus_to_stop" => [
        "distance_km" => round($bus_to_stop['distance_m'] / 1000, 2),
        "eta_min" => $eta_bus_stop
    ],
    "stop_to_school" => [
        "distance_km" => round($stop_to_school['distance_m'] / 1000, 2),
        "eta_min" => $eta_stop_school
    ]
], JSON_PRETTY_PRINT);
?>