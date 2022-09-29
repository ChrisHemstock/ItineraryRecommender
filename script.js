var map = L.map('map').setView([51.505, -0.09], 13);

L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '© OpenStreetMap'
}).addTo(map);

L.marker([51.5, -0.09]).on('click', function(e) {
    var newElement = document.createElement("p");
    newElement.innerHTML = e.latlng;
    document.getElementById("poiList").appendChild(newElement);
}).addTo(map)