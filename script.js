let map = L.map('map').setView([39.80924029431849, -86.16061656273943], 13);


L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '© OpenStreetMap'
}).addTo(map);


fetch("nodes.json")
    .then(response => response.json())
    .then(data => {
        data.elements.forEach(element => {
            L.marker([element.lat, element.lon]).on('click', function(e) {
                let newElement = document.createElement("p");
                newElement.innerHTML = element.tags.name;
                document.getElementById("poiList").appendChild(newElement);
                }).addTo(map)
        });
        
    })
//https://www.w3schools.com/howto/howto_js_close_list_items.asp
