<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zobrazení zemětřesení pomocí xml</title>
    <link rel="stylesheet" href="<?= base_url('node_modules/leaflet/dist/leaflet.css'); ?>">
    <script src="<?= base_url('node_modules/leaflet/dist/leaflet.js'); ?>"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <style>
        #map {
            height: 580px;
            width: 580px
        }
    </style>
</head>

<body>
    <div id="map"></div>
    <script>
         // Inicializace mapy
         var map = L.map('map').setView([49.8175, 15.473], 4); // Střed ČR, zoom 4

        // Přidání dlaždic
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        // Data o zemětřeseních
        const earthquakes = <?= $earthquakes ?>;

        // Přidání bodů na mapu
        earthquakes.forEach(eq => {
            const popupContent = `
                <strong>${eq.description}</strong><br>
                Magnitude: ${eq.magnitude}<br>
                Depth: ${eq.depth} m
            `;

            L.marker([eq.latitude, eq.longitude]).addTo(map)
                .bindPopup(popupContent);
        });
    </script>
</body>

</html>