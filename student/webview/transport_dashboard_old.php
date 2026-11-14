<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Parent Bus Tracker</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD1D-yIhaCPX82rreGc0x55gl5ihZJsq-U&libraries=geometry"></script>
    <style>
        :root {
            --bg: #f1f6fb;
            --panel: rgba(255,255,255,0.92);
            --line: #dbe7f0;
            --text: #102033;
            --muted: #64758b;
            --accent: #0f62fe;
            --accent-soft: rgba(15,98,254,0.1);
            --success: #0f9f6e;
            --success-soft: rgba(15,159,110,0.12);
            --warning: #ef8a17;
            --warning-soft: rgba(239,138,23,0.12);
            --danger: #e14a59;
            --shadow: 0 20px 60px rgba(21, 44, 73, 0.12);
            --radius: 24px;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: 'Manrope', sans-serif;
            color: var(--text);
            background:
                radial-gradient(circle at top left, rgba(15,98,254,0.12), transparent 30%),
                linear-gradient(180deg, #f8fbfd 0%, #eef4f8 100%);
        }
        .shell { max-width: 1280px; margin: 0 auto; padding: 18px; }
        .hero, .card, .map-card, .timeline-card {
            background: var(--panel);
            border: 1px solid rgba(255,255,255,0.85);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            backdrop-filter: blur(14px);
        }
        .timeline-card {
    height: auto;
    overflow-y: auto;
}

        .hero {
               padding: 7px 14px;
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            gap: 16px;
            align-items: center;
            flex-wrap: wrap;
        }
        .hero h1 { margin: 0 0 6px;     font-size: 16px; letter-spacing: -0.04em; }
        .hero p { margin: 0; color: var(--muted); }
        .pill, .status-chip, .mini-chip {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 800;
        }
        .pill { padding: 12px 16px; background: #fff; box-shadow: inset 0 0 0 1px var(--line); }
        .dot {
            width: 9px;
            height: 9px;
            border-radius: 50%;
            background: #18c37e;
            box-shadow: 0 0 0 0 rgba(24,195,126,0.6);
            animation: pulse 1.8s infinite;
        }
        .layout {
    display: flex;
    flex-direction: column;
    height: calc(100vh - 100px);
    gap: 12px;
}
        .timeline-card, .map-card {    overflow-y: scroll;}
        .section-pad { padding: 18px; }
        .card-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 12px; }
        .card {
            padding: 16px;
            background: linear-gradient(180deg, rgba(255,255,255,0.98), rgba(245,249,252,0.98));
        }
        .label { color: var(--muted); font-size: 12px; text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 8px; }
        .value { font-size: 17px; font-weight: 800; letter-spacing: -0.03em; }
        .status-chip { padding: 10px 12px; }
        .status-chip.live { background: var(--success-soft); color: var(--success); }
        .status-chip.warning { background: var(--warning-soft); color: var(--warning); }
        .mini-chip { padding: 8px 10px; background: #f3f7fb; color: #35506d; }
        .timeline-list { padding: 0 18px 18px; }
        .timeline-item { display: flex; gap: 14px; padding-bottom: 16px; position: relative; }
        .timeline-item:last-child { padding-bottom: 0; }
        .rail { width: 22px; flex: 0 0 22px; position: relative; }
        .rail::after {
            content: "";
            position: absolute;
            top: 18px;
            left: 10px;
            bottom: -6px;
            width: 2px;
            background: linear-gradient(180deg, rgba(15,98,254,0.24), rgba(15,98,254,0.03));
        }
        .timeline-item:last-child .rail::after { display: none; }
        .dot-stop {
            position: absolute;
            top: 4px;
            left: 1px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            border: 4px solid #fff;
            background: #afbcc9;
            box-shadow: 0 8px 16px rgba(20, 41, 67, 0.12);
        }
        .timeline-item.current .dot-stop { background: var(--success); box-shadow: 0 0 0 8px rgba(15,159,110,0.12); }
        .timeline-item.next .dot-stop { background: var(--accent); box-shadow: 0 0 0 8px rgba(15,98,254,0.12); }
        .timeline-item.departed .dot-stop { background: #95a5b5; box-shadow: none; }
        .stop-card {
            flex: 1;
            padding: 16px;
            border-radius: 20px;
            background: #fff;
            box-shadow: inset 0 0 0 1px rgba(219,231,240,0.95);
        }
        .timeline-item.current .stop-card { background: linear-gradient(145deg, rgba(15,159,110,0.12), rgba(255,255,255,1)); }
        .timeline-item.next .stop-card { background: linear-gradient(145deg, rgba(15,98,254,0.12), rgba(255,255,255,1)); }
        .stop-card h3 { margin: 0 0 8px; font-size: 16px; letter-spacing: -0.03em; }
        .stop-card p { margin: 0; color: var(--muted); font-size: 13px; }
        .chips-row { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 10px; }
        .map-card {
    height: 100%;
    position: relative;
    overflow: hidden;
}

#map {
    width: 100%;
     height: 55vh;
}
        .map-overlay {
            position: absolute;
            top: 16px;
            left: 16px;
            right: 16px;
            display: flex;
            justify-content: space-between;
            gap: 10px;
            pointer-events: none;
        }
        .float-card {
            min-width: 220px;
            padding: 14px 16px;
            border-radius: 18px;
            background: rgba(255,255,255,0.94);
            box-shadow: var(--shadow);
            pointer-events: auto;
        }
        .float-card strong { display: block; margin-bottom: 4px; font-size: 16px; }
        .muted { color: var(--muted); }
        .error-box {
            padding: 22px;
            text-align: center;
            color: var(--muted);
            background: rgba(255,255,255,0.9);
            border-radius: 20px;
            box-shadow: var(--shadow);
        }
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(24,195,126,0.6); }
            70% { box-shadow: 0 0 0 10px rgba(24,195,126,0); }
            100% { box-shadow: 0 0 0 0 rgba(24,195,126,0); }
        }
        @media (max-width: 1100px) {
            .layout { grid-template-columns: 1fr; }
           .map-card {
    height: 100%;
    position: relative;
    overflow: hidden;
}

#map {
    width: 100%;
    height: 55vh;
}
        }
        @media (max-width: 720px) {
            .shell { padding: 10px; }
            .card-grid { grid-template-columns: 1fr; }
            .map-overlay { flex-direction: column; }
            .map-card {
    height: 100%;
    position: relative;
    overflow: hidden;
}

#map {
    width: 100%;
     height: 55vh;
}
        }
    </style>
</head>
<body>
    <div class="shell">
        <section class="hero">
            <div>
                <h1 id="titlee">Bus Live Tracking </h1> 
            </div>
            <div class="pill"><span class="dot"></span>Live update every 10s</div>
        </section>

        <div id="errorBox" class="error-box" style="display:none;"></div>

       <section id="appLayout" class="layout" style="display:none;">

    <!-- MAP TOP -->
    <section class="map-card">
        <div id="map"></div>
    </section>

    <!-- TIMELINE BOTTOM -->
    <aside class="timeline-card">

        <div class="section-pad">
            <div style="display:flex; justify-content:space-between;">
                <div> 
                    <div id="studentHeadline" class="value">Loading...</div>
                    <div id="studentSummary" class="muted"></div>
                </div>
                <div id="liveStatus"></div>
            </div>
        </div>

        <div id="timelineList" class="timeline-list"></div>

    </aside>

</section>
    </div>

    <script>
        const params = new URLSearchParams(window.location.search);
        const POLL_MS = 10000;
        const query = {
            student_id: params.get('student_id') || '',
            cid: params.get('cid') || '163',
            route: params.get('route') || '',
            student_stop_name: params.get('student_stop_name') || '',
            student_stop_index: params.get('student_stop_index') || '',
            student_name: params.get('student_name') || 'Student',
            taskto: params.get('taskto') || ''
        };

        let map;
        let vehicleMarker;
        let routePolyline;
        let lastVehiclePosition = null;
        let animationFrame = null;

        function escapeHtml(value) {
            return String(value ?? '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#039;');
        }

        function formatDistance(value) {
            return value === null || value === undefined || value === '' ? '--' : `${Number(value).toFixed(1)} km`;
        }

        function formatEta(value) {
            return value === null || value === undefined || value === '' ? '--' : `${value} min`;
        }

        function showError(message) {
            document.getElementById('errorBox').style.display = 'block';
            document.getElementById('errorBox').textContent = message;
            document.getElementById('appLayout').style.display = 'none';
        }

        async function fetchParentData() {
            const qs = new URLSearchParams(query);
            qs.set('t', Date.now());
            const response = await fetch(`parent_live_data.php?${qs.toString()}`, {
                headers: { 'Cache-Control': 'no-cache' }
            });
            const payload = await response.json();
            if (!response.ok || !payload.success) {
                throw new Error(payload.message || 'Unable to load parent tracker.');
            }
            return payload;
        }

        function studentStatusChip(vehicle, student) {
            if (vehicle.studentStop?.status === 'current') {
                return '<span class="status-chip live">Bus stop par aa chuki hai</span>';
            }
            if ((student.distanceKm ?? null) !== null && Number(student.distanceKm) <= 1) {
                return '<span class="status-chip warning">Bus 1 km ke andar hai</span>';
            }
            return '<span class="status-chip live">Live Status</span>';
        }

        function renderSummary(data) {
            const student = data.student;
            const vehicle = data.vehicle;
            const progress = vehicle.progressSummary || {};
 
            document.getElementById('studentHeadline').textContent = `${student.name} • ${student.stopName}`;
            document.getElementById('studentSummary').textContent = student.statusText;
            document.getElementById('liveStatus').innerHTML = studentStatusChip(vehicle, student);
  
        }

        function renderTimeline(data) {
            const stops = Array.isArray(data.vehicle?.stops) ? data.vehicle.stops : [];
            document.getElementById('timelineList').innerHTML = stops.map((stop, index) => `
                <div class="timeline-item ${escapeHtml(stop.status || 'upcoming')}">
                    <div class="rail"><div class="dot-stop"></div></div>
                    <div class="stop-card">
                        <h3>${escapeHtml(stop.name || `Stop ${index + 1}`)}</h3>
                        <p>${escapeHtml(stop.status === 'departed' ? 'Departed' : stop.status === 'current' ? 'Bus is waqt isi stop par hai' : stop.status === 'next' ? 'Yeh aapke bacche ka next live stop hai' : 'Bus iss stop ki taraf aa rahi hai')}</p>
                        <div class="chips-row">
                            <span class="mini-chip">Distance ${escapeHtml(formatDistance(stop.distance))}</span>
                            <span class="mini-chip">ETA ${escapeHtml(formatEta(stop.etaMin))}</span>
                            <span class="mini-chip">Arrival ${escapeHtml(stop.arrivalTime || '--')}</span>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        function createBusIcon(rotation = 0) {
            const svg = `<svg xmlns="http://www.w3.org/2000/svg" width="72" height="72" viewBox="0 0 72 72"><g transform="rotate(${rotation} 36 36)"><circle cx="36" cy="36" r="26" fill="rgba(15,98,254,0.14)"/><path d="M22 23c0-6 4.9-11 11-11h6c6.1 0 11 5 11 11v21c0 2.3-1.4 4.5-3.5 5.5V55a2.6 2.6 0 0 1-5.2 0v-3.8H30.7V55a2.6 2.6 0 0 1-5.2 0v-5.5A5.8 5.8 0 0 1 22 44V23z" fill="#0f62fe"/><rect x="27" y="19" width="18" height="10" rx="4" fill="#a8d2ff"/><rect x="26" y="34" width="20" height="9" rx="3" fill="#e3f1ff"/><circle cx="29" cy="47" r="4" fill="#102033"/><circle cx="43" cy="47" r="4" fill="#102033"/></g></svg>`;
            return { url: `data:image/svg+xml;charset=UTF-8,${encodeURIComponent(svg)}`, scaledSize: new google.maps.Size(52, 52), anchor: new google.maps.Point(26, 26) };
        }

        function animateVehicle(fromPosition, toPosition) {
            if (!vehicleMarker || !toPosition) return;
            cancelAnimationFrame(animationFrame);

            if (!fromPosition) {
                vehicleMarker.setPosition(toPosition);
                return;
            }

            const start = performance.now();
            const duration = 8000;

            const frame = (now) => {
                const progress = Math.min((now - start) / duration, 1);
                const eased = progress < 0.5 ? 4 * progress * progress * progress : 1 - Math.pow(-2 * progress + 2, 3) / 2;
                vehicleMarker.setPosition({
                    lat: fromPosition.lat + ((toPosition.lat - fromPosition.lat) * eased),
                    lng: fromPosition.lng + ((toPosition.lng - fromPosition.lng) * eased)
                });
                if (progress < 1) animationFrame = requestAnimationFrame(frame);
            };

            animationFrame = requestAnimationFrame(frame);
        }

        function drawRoute(vehicle, student) {
            const pathPoints = (vehicle.routePath || []).map(point => ({ lat: Number(point.lat), lng: Number(point.lng) }));
            if (!pathPoints.length) return;

            if (!routePolyline) {
                routePolyline = new google.maps.Polyline({
                    map,
                    path: pathPoints,
                    strokeColor: '#0f62fe',
                    strokeOpacity: 0.85,
                    strokeWeight: 5
                });
            } else {
                routePolyline.setPath(pathPoints);
            }

            const vehiclePos = { lat: Number(vehicle.latitude), lng: Number(vehicle.longitude) };
            const bounds = new google.maps.LatLngBounds();
            pathPoints.forEach(point => bounds.extend(point));
            bounds.extend(vehiclePos);
            map.fitBounds(bounds, 70);
            if (map.getZoom() > 15) map.setZoom(15);

            if (!vehicleMarker) {
                vehicleMarker = new google.maps.Marker({
                    map,
                    position: vehiclePos,
                    icon: createBusIcon(0),
                    zIndex: 999
                });
            }

            if (lastVehiclePosition) {
                const heading = google.maps.geometry.spherical.computeHeading(
                    new google.maps.LatLng(lastVehiclePosition.lat, lastVehiclePosition.lng),
                    new google.maps.LatLng(vehiclePos.lat, vehiclePos.lng)
                );
                vehicleMarker.setIcon(createBusIcon(heading));
            }

            animateVehicle(lastVehiclePosition, vehiclePos);
            lastVehiclePosition = vehiclePos;
        }

        function ensureMap() {
            if (map) return;
            map = new google.maps.Map(document.getElementById('map'), {
                center: { lat: 26.673, lng: 74.049 },
                zoom: 13,
                mapTypeControl: false,
                streetViewControl: false,
                fullscreenControl: false,
                zoomControl: true,
                styles: [
                    { featureType: 'poi', stylers: [{ visibility: 'off' }] },
                    { featureType: 'transit', stylers: [{ visibility: 'off' }] },
                    { featureType: 'road', elementType: 'geometry', stylers: [{ color: '#ffffff' }] },
                    { featureType: 'road', elementType: 'labels.text.fill', stylers: [{ color: '#617286' }] },
                    { featureType: 'landscape', stylers: [{ color: '#eef4f8' }] },
                    { featureType: 'water', stylers: [{ color: '#cde7ff' }] }
                ]
            });
        }

        async function refresh() {
            try {
                const data = await fetchParentData();
                document.getElementById('errorBox').style.display = 'none';
                document.getElementById('appLayout').style.display = 'grid';
                renderSummary(data);
                renderTimeline(data);
                ensureMap();
                drawRoute(data.vehicle, data.student);
            } catch (error) {
                showError(error.message);
            }
        }

        refresh();
        setInterval(refresh, POLL_MS);
    </script>
</body>
</html>
