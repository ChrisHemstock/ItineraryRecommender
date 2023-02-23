let map = L.map('map').setView([39.80924029431849, -86.16061656273943], 13);

L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '© OpenStreetMap'
}).addTo(map);

containerEvent(document.getElementById('poi'))
let json = JSON.parse(data)
//console.log(json.data)
json.data.forEach(poi => {
    L.marker([poi[0], poi[1]]).on('click', function (e) {
        //adds an event to the last day on the itinerary
        addEvent(poi[3], poi[6], getStartTime(), incrementTime(getStartTime(), 30))
    }).bindPopup(poi[6]).on('mouseover', function (e) {
        this.openPopup();
    }).on('mouseout', function (e) {
        this.closePopup();
    }).addTo(map)
});

//Adds the event listeners to the loaded pois in the itinerary
if (typeof phpPoi !== 'undefined') {
    let jsonPois = JSON.parse(phpPoi)
    jsonPois.forEach(poi => {
        //console.log('HERE in thisLOOP')
        addEvent(poi[0], poi[3], poi[1], poi[2])
    });
}

$('#save').click(function () {
    var tmp = createItineraryJson()
    //console.log(temp)
    $.ajax({
        type: 'POST',
        url: 'resources/tripData.php',
        data: { 'tripData': tmp },
        success: function (msg) {
            console.log("success")
            document.location.reload(true)
            //console.log(msg);
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            alert("Status: " + textStatus); alert("Error: " + errorThrown);
        }
    });
});