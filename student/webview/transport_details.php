<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Transport Dashboard</title>
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background: #f4f6fa;
    }

    .container {
      display: flex;
      flex-direction: column;
      height: 100vh;
    }

    /* MAP SECTION */
    .map-section {
      height: 45vh;
      background: #d9e6ff;
      border-bottom: 2px solid #ccc;
      background-image: url('https://via.placeholder.com/1200x600?text=Map+Preview');
      background-size: cover;
      background-position: center;
      border-radius: 0 0 20px 20px;
    }

    /* DETAILS SECTION */
    .details-section {
      flex: 1;
      overflow-y: auto;
      padding: 15px;
    }

    .card {
      background: #fff;
      padding: 15px;
      border-radius: 15px;
      margin-bottom: 15px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.08);
    }

    .card h3 {
      margin: 0 0 10px;
      font-size: 18px;
      color: #222;
    }

    .card p {
      margin: 5px 0;
      font-size: 14px;
      color: #555;
    }

    .flex {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .status {
      padding: 5px 10px;
      font-size: 12px;
      border-radius: 12px;
      background: #e3f7e3;
      color: #0a8a0a;
      font-weight: bold;
    }

    .btn {
      background: #0066ff;
      color: #fff;
      padding: 10px 20px;
      border-radius: 12px;
      border: none;
      text-align: center;
      display: block;
      width: 100%;
      margin-top: 10px;
      font-weight: bold;
      cursor: pointer;
    }

    @media(max-width: 600px){
      .map-section{ height: 40vh; }
    }
    /* Mobile Responsive */
  @media (max-width: 600px) {
    .map-container{
      height: 230px;
    }
    .transport-card{
      margin: 8px;
      padding: 14px;
    }
    .card-header{
      flex-direction: column;
      align-items: flex-start;
      gap: 6px;
    }
    .route-details{
      flex-direction: column;
      gap: 10px;
    }
    .route-box{
      width: 100%;
    }
    .vehicle-info{
      flex-direction: column;
      gap: 8px;
      align-items: flex-start;
    }
    .status-dot{
      margin-left: 0;
    }
  }
  
  .map-section {
  width: 100%;
  margin: 0;
  padding: 0;
}

.map-responsive {
  position: relative;
  width: 100%;
  height: 0;
  padding-bottom: 70%; /* Adjust height ratio */
  overflow: hidden;
  border-radius: 12px;
}

.map-responsive iframe {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  border: 0;
}

</style>
</head>
<body>
  <div class="container">

    <!-- MAP SECTION -->
   <div class="map-section">
  <div class="map-responsive">
    <iframe 
      src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3558.705213242794!2d75.76639997561313!3d26.8811053614478!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x396db457e8ff533f%3A0x74aa8abbd2eed604!2sEdPlus%20AI%20Technologies%20Private%20Limited!5e0!3m2!1sen!2sin!4v1763704323133!5m2!1sen!2sin" 
      allowfullscreen="" 
      loading="lazy"
      referrerpolicy="no-referrer-when-downgrade">
    </iframe>
  </div>
</div>


    <!-- DETAILS SECTION -->
    <div class="details-section">

      <!-- VEHICLE INFO -->
      <div class="card">
        <div class="flex">
          <h3>Vehicle Details</h3>
          <span class="status">Running</span>
        </div>
        <p><strong>Vehicle No:</strong> RJ14 XX 1234</p>
        <p><strong>Driver Name:</strong> Mahesh Kumar</p>
        <p><strong>Speed:</strong> 45 km/h</p>
        <p><strong>Last Updated:</strong> 1 min ago</p>
        <button class="btn">View Full Route</button>
      </div>

      <!-- ROUTE INFO -->
      <div class="card">
        <h3>Route Details</h3>
        <p><strong>Route No:</strong> 12</p>
        <p><strong>Total Stops:</strong> 18</p>
        <p><strong>Current Stop:</strong> Padu Kalan</p>
        <p><strong>Next Stop:</strong> Murlipura</p>
      </div>

      <!-- UPCOMING STOPS -->
      <div class="card">
        <h3>Upcoming Stops</h3>
        <p>• Stop 1 — 500m</p>
        <p>• Stop 2 — 1.2 km</p>
        <p>• Stop 3 — 2.0 km</p>
      </div>

    </div>
  </div>
</body>
</html>
