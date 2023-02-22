let map = L.map('map').setView([39.80924029431849, -86.16061656273943], 13);

L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '© OpenStreetMap'
}).addTo(map);

containerEvent(document.getElementById('poi'))
let json = JSON.parse(data)
json.data.forEach(poi => {
    const lat = poi[0]
    const long = poi[1]
    const api_id = poi[3]
    const name = poi[6]
    L.marker([lat, long]).on('click', function (e) {
        //adds an event to the last day on the itinerary
        addEvent(api_id, name, getStartTime(), incrementTime(getStartTime(), 30))
    }).bindPopup(name).on('mouseover', function (e) {
        this.openPopup();
    }).on('mouseout', function (e) {
        this.closePopup();
    }).addTo(map)
});

//Adds the event listeners to the loaded pois in the itinerary
if (typeof phpPoi !== 'undefined') {
    let jsonPois = JSON.parse(phpPoi)
    jsonPois.forEach(poi => {
        addEvent(poi[0], poi[3], poi[1], poi[2])
    });
}

$('#save').click(function () {
    var tmp = createItineraryJson()
   // console.log(temp)
    $.ajax({
        type: 'POST',
        url: 'resources/tripData.php',
        data: { 'tripData': tmp },
        success: function (msg) {
            console.log("success")
            console.log(msg);
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            alert("Status: " + textStatus); alert("Error: " + errorThrown);
        }
    });
});