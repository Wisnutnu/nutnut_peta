<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Sistem</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            height: 100vh;
            background: linear-gradient(135deg, #4e73df, #1cc88a);
            display: flex;
            justify-content: center;
            align-items: center;

        }

        /* .login-card {
            background: #fff;
            padding: 40px;
            width: 350px;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
            animation: fadeIn 0.8s ease-in-out;
        }

        @keyframes fadeIn {
            from {opacity: 0; transform: translateY(-20px);}
            to {opacity: 1; transform: translateY(0);}
        } */

        
        .form-group {
            margin-bottom: 20px;
        }

        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border-radius: 8px;
            border: 1px solid #ddd;
            outline: none;
            transition: 0.3s;
        }

        .form-group input:focus {
            border-color: #4e73df;
            box-shadow: 0 0 5px rgba(78,115,223,0.3);
        }

        .btn-login {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 8px;
            background: #4e73df;
            color: #fff;
            font-weight: 500;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-login:hover {
            background: #2e59d9;
        }

        .error-msg {
            background: #ffe6e6;
            color: #cc0000;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 15px;
            text-align: center;
            font-size: 14px;
        }

        .footer-text {
            text-align: center;
            margin-top: 15px;
            font-size: 13px;
            color: #ffffff;
        }
        /* MAP BACKGROUND */
#map-bg {
    position: absolute;
    width: 80%;
    height: 80%;
    border-radius: 30px;
    z-index: 0;
    opacity: 0.25; /* bikin soft supaya gradient tetap dominan */
    pointer-events: none; /* Supaya map gak ganggu klik di form */
}

/* Supaya login di atas map */


    </style>
</head>
<body>
<body>

<div id="map-bg"></div>

<div class="login-card">

<div class="login-card">
    <h2>Welcome Back 👋</h2>

    <?php if(session()->getFlashdata('error')): ?>
        <div class="error-msg">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <form action="/loginProcess" method="post">
        <?= csrf_field() ?>

        <div class="form-group">
            <input type="text" name="username" placeholder="Username" required>
        </div>

        <div class="form-group">
            <input type="password" name="password" placeholder="Password" required>
        </div>

        <button type="submit" class="btn-login">Login</button>
    </form>

    <div class="footer-text ">
        <h4>© <?= date('Y') ?> Wisnutnut </h4>
    </div>
</div>
<script>
// INIT MAP
var map = L.map('map-bg', {
    zoomControl:false,
    attributionControl:false
}).setView([-2.5, 118], 5);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);


// LOAD GEOJSON (ganti dengan path kamu)
fetch('/assets/geojson/kabupaten_coba_master.json')
.then(res => res.json())
.then(data => {

    L.geoJSON(data, {
        style: {
            color:"#3b82f6",
            weight:1,
            fillOpacity:0.4
        },
        onEachFeature: function(feature, layer){
            layer.on('click', function(){
                alert("Wilayah: " + feature.properties.name);
            });
        }
    }).addTo(map);

});
</script>
</body>
</html>
