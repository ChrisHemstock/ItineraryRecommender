let map = L.map('map').setView([39.80924029431849, -86.16061656273943], 13);

L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '© OpenStreetMap'
}).addTo(map);
let lineCoordinate = []
containerEvent(document.getElementById('poi'))
let json = JSON.parse(allPoisJson)
json.data.forEach(poi => {
    const LAT = poi[0]
    const LONG = poi[1]
    const API_ID = poi[3]
    const NAME = poi[6]
    const URL = poi[9]
    var marker = L.marker([LAT, LONG]).on('click', function (e) {
        //adds an event to the last day on the itinerary
        addEvent(API_ID, NAME, URL, getStartTime(), incrementTime(getStartTime(), 30))
    }).bindPopup(NAME).on('mouseover', function (e) {
        this.openPopup();
    }).on('mouseout', function (e) {
        this.closePopup();
    }).addTo(map)
    let savedPois = JSON.parse(savedPoiJson)
    let color = changeColor(savedPois, marker, API_ID);
    let colorFirst = changeColorFirst(savedPois, marker, API_ID);
    console.log(colorFirst);
    let cord = getNewCoordinate(savedPois, API_ID);
    if(cord.length != 0) {
        lineCoordinate.push(cord);
    }
});

lineCoordinate.sort((a, b) => a[2].localeCompare(b[2]))
lineCoordinate = lineCoordinate.map(([first, second]) => [first, second]);
L.polyline(lineCoordinate, {color: 'red'}).addTo(map);

//Adds the event listeners to the loaded pois in the itinerary
if (typeof savedPoiJson !== 'undefined') {
    let savedPois = JSON.parse(savedPoiJson)
    savedPois.forEach(poi => {
        const API_ID = poi[0]
        const NAME = poi[3]
        const URL = poi[4]
        const START_TIME = poi[1]
        const END_TIME = poi[2]
        addEvent(API_ID, NAME, URL, START_TIME, END_TIME)
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
            document.location.reload(true)
            //console.log(msg);
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            alert("Status: " + textStatus); alert("Error: " + errorThrown);
        }
    });
    
});