var map = L.map('map').setView([39.80924029431849, -86.16061656273943], 13);


L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '© OpenStreetMap'
}).addTo(map);

let markerArray = []
markerArray.push(
    L.marker([39.80924029431849, -86.16061656273943]).on('click', function(e) {
    var newElement = document.createElement("p");
    newElement.innerHTML = e.latlng;
    document.getElementById("poiList").appendChild(newElement);
    }).addTo(map)
)

fetch("nodes.json")
    .then(response => response.json())
    .then(data => {
        console.log(data)
        data.elements.forEach(element => {
            L.marker([element.lat, element.lon]).on('click', function(e) {
                var newElement = document.createElement("p");
                newElement.innerHTML = e.latlng;
                document.getElementById("poiList").appendChild(newElement);
                }).addTo(map)
        });
        
    })
