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
    })
)

fetch("nodes.json")
    .then(response => response.json())
    .then(data => {
        data.features.forEach(element => {
            L.marker(element.geometry.coordinates).on('click', function(e) {
                var newElement = document.createElement("p");
                newElement.innerHTML = e.latlng;
                document.getElementById("poiList").appendChild(newElement);
                })
        });
    })

for(let i=0; i<markerArray.length; i++) {
    markerArray[i].addTo(map)
}
