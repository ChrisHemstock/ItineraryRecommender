<?php
include_once 'includes/functions.php';
include_once 'includes/dbconnect.php';
include_once 'resources/reviewRequest.php';
session_start();
set_time_limit(360);
$userID = $_SESSION["id"];
$json = createMapPoisJson($link);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.1/dist/leaflet.css"
    integrity="sha256-sA+zWATbFveLLNqWO2gtiw3HL/lh1giY/Inf1BJ0z14=" crossorigin="" />
  <link rel="stylesheet" href="styles/style.css" />
  <script src="https://unpkg.com/leaflet@1.9.1/dist/leaflet.js"
    integrity="sha256-NDI0K41gVbWqfkkaHj15IzU7PtMoelkzyKp8TOaFQ3s=" crossorigin=""></script>
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <title>Trip Recommender</title>
  <script>
    var data = '<?php echo $json; ?>';
  </script>
  <?php
  $jsonPoiList = populateSavedPois($link);
  ?>
  <script>
    var phpPoi = '<?php echo $jsonPoiList ?>';
    var recommendations = '<?php echo getRecommendations($link, $userID)?>';
  </script>
  
</head>

<body>
  <?php include 'includes/homebar.php' ?>
  <div id="itinerary">
    <?php echo "<h2>". $_GET['name'] ."</h3>";?>
    <ul id="poi" data-starttime='00:00'></ul>
    <input type="submit" value="Save" id="save" onclick="return feedback();" />
    <input type="button" value = "Make Recommendations" onclick="return displayRecommendations(recommendations, data);"/>

    <script>
      function feedback() {
        alert("Trip data Entered!");
        return true;
      }
    </script>
  </div>
  <div id="map"></div>
  <div id="recommendations">
      <ul id="poiRecommendations"></ul>
  </div>
  <script>
    let map = L.map('map').setView([39.80924029431849, -86.16061656273943], 13);

    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap'
    }).addTo(map);

    containerEvent(document.getElementById('poi'))
    let json = JSON.parse(data)
    console.log(json.data)
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
        $.ajax({
            type: 'POST',
            url: 'resources/tripData.php', //Not sure why this is working it should be ../resources/tripData.php
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

    function containerEvent(container) {
        container.addEventListener('dragover', e => {
            e.preventDefault()
            const afterElement = getDragAfterElement(container, e.clientY)
            const draggable = document.querySelector('.dragging')
            if (afterElement == null) {
                container.appendChild(draggable)
            } else {
                container.insertBefore(draggable, afterElement)
            }
        })
    }

    function getDragAfterElement(container, y) {
        const draggableElements = [...container.querySelectorAll('.draggable:not(.dragging)')]

        return draggableElements.reduce((closest, child) => {
            const box = child.getBoundingClientRect()
            const offset = y - box.top - box.height / 2
            if (offset < 0 && offset > closest.offset) {
                return { offset: offset, element: child }

            } else {
                return closest
            }
        }, { offset: Number.NEGATIVE_INFINITY }).element
    }

    //addEvent adds an Event to the itinerary
    function addEvent(poiId, name, startTime, endTime) {
        let html = '<li class="draggable ' + poiId + '" draggable="true">' + name +
            '<span class="time"><input type="time" class="startEvent" title="Start Time" value="' + startTime +
            '"/><input type="time" class="endEvent" title="End Time" value="' + endTime + '" onchange="updateTimes(0)"/></span>' +
            '<span class="close">X</span></li>';
        let poi = document.getElementById('poi')
        poi.insertAdjacentHTML('beforeend', html);

        let newElement = [...document.querySelectorAll('.draggable:not(.dragging)')].pop()
        addEventEventListeners(newElement)
    }

    function getStartTime() {
        if ([...document.querySelectorAll('.draggable:not(.dragging)')].length == 0) {
            console.log(getItineraryStartTime())
            return getItineraryStartTime()
        } else {
            return [...document.querySelectorAll('.draggable:not(.dragging)')].pop().querySelector('.endEvent').value
        }
    }

    function incrementTime(time, minutesAdded) {
        let timeArray = time.split(':')
        timeArray[0] = Number(timeArray[0])
        timeArray[1] = Number(timeArray[1]) + minutesAdded
        while (timeArray[1] >= 60) {
            timeArray[1] = timeArray[1] - 60;
            timeArray[0] += 1;
            if (timeArray[0] > 23) {
                timeArray[0] = 0
            }
        }
        return String(timeArray[0]).padStart(2, '0') + ':' + String(timeArray[1]).padStart(2, '0')
    }

    function addEventEventListeners(element) {
        element.addEventListener('dragstart', () => {
            element.classList.add('dragging')
        })

        element.addEventListener('dragend', () => {
            updateTimes(0)
            element.classList.remove('dragging')
        })

        element.childNodes[1].firstChild.addEventListener('change', function (e) {
            setItineraryStartTime(document.getElementById('poi').childNodes[0].childNodes[1].firstChild.value)
            updateTimes(0)
        })

        //allows buttons to be closed
        let closebtns = document.getElementsByClassName("close");
        for (let i = 0; i < closebtns.length; i++) {
            closebtns[i].addEventListener("click", function () {
                this.parentElement.remove()
            });
        }
    }

    function createItineraryJson() {
        let dayString = `{"tripId": "${window.location.href.split('&')[0].split('=')[1]}", "pois": [ `
        let pois = [...document.getElementsByClassName('draggable')];
        pois.forEach(poi => {
            dayString += `{"poiId": ${poi.className.split(' ')[1]}, "startTime": "${poi.querySelector(".startEvent").value}","endTime": "${poi.querySelector(".endEvent").value}"},`;
        });
        dayString = dayString.slice(0, -1)
        dayString += ']}';
        return dayString;
    }

    function loadItinerary() {
        fetch("test.json")
            .then(response => response.json())
            .then(data => {
                data.pois.forEach(poi => {
                    let html = '<li class="draggable" draggable="true" class="' + poi.poiId + '">' + poi.poiName +
                        '<span class="time"><input type="time" class="startEvent" title="Start Time" value="' + poi.startTime + '"/><input type="time" class="endEvent" title="End Time" value="' + poi.endTime + '"/></span>' +
                        '<span class="close">X</span></li>';
                    document.getElementById('poi').insertAdjacentHTML('beforeend', html);

                    let newElement = [...document.querySelectorAll('.draggable:not(.dragging)')].pop()
                    addEventEventListeners(newElement)
                });
            });
    }

    function getDuration(startTime, endTime) {
        startTime = startTime.split(':')
        endTime = endTime.split(':')
        for (let i = 0; i < startTime.length; i++) {
            startTime[i] = Number(startTime[i])
            endTime[i] = Number(endTime[i])
        }
        if (startTime[1] <= endTime[1]) {
            //st: 2:20 en: 3:30
            return endTime[1] - startTime[1] + (endTime[0] - startTime[0]) * 60
        } else {
            //st: 2:30 en: 3:00
            return 60 - startTime[1] + endTime[1] + (endTime[0] - startTime[0] - 1) * 60
        }

    }

    function getItineraryStartTime() {
        return document.getElementById('poi').dataset.starttime
    }

    function setItineraryStartTime(value) {
        if (value != undefined) {
            document.getElementById('poi').dataset.starttime = document.getElementById('poi').firstChild.childNodes[1].firstChild.value
        } else {
            document.getElementById('poi').dataset.starttime = '00:00'
        }
    }

    function updateTimes(indexCurrent) {
        console.log(indexCurrent)
        let list = document.getElementById('poi').childNodes

        if (indexCurrent != list.length) {
            let startTimeInput = document.getElementById('poi').childNodes[indexCurrent].childNodes[1].firstChild.value
            let endTimeInput = document.getElementById('poi').childNodes[indexCurrent].childNodes[1].lastChild.value
            let currentDuration = getDuration(startTimeInput, endTimeInput)
            if (currentDuration < 0) {
                currentDuration = 30
            }
            if (indexCurrent == 0) {
                console.log('startTime: ' + getItineraryStartTime())
                document.getElementById('poi').childNodes[indexCurrent].childNodes[1].firstChild.value = getItineraryStartTime()
            } else {
                document.getElementById('poi').childNodes[indexCurrent].childNodes[1].firstChild.value = document.getElementById('poi').childNodes[indexCurrent - 1].childNodes[1].lastChild.value
            }
            document.getElementById('poi').childNodes[indexCurrent].childNodes[1].lastChild.value = incrementTime(document.getElementById('poi').childNodes[indexCurrent].childNodes[1].firstChild.value, currentDuration)
            updateTimes(indexCurrent + 1)
        }
    }


    function displayRecommendations(recommendationList, data) {
      console.log(recommendationList)
      let json = JSON.parse(recommendationList)
      console.log(json)
      let poiIds = Object.keys(json)
      console.log(poiIds)

      let poiJson = JSON.parse(data)
      for(let i = 0; i < poiJson.data.length; i++) {
        console.log(poiJson.data[i]);
        for(let j = 0; j < poiIds.length; j++) {
          if(poiJson.data[i][3] == poiIds[j]) {
            addEvent(poiIds[j], poiJson.data[i][6], '00:00', '00:30')
            updateTimes(0)
          }
        }
      }
    }
  </script>
   <script src="scripts/mapScript.js" defer></script>
</body>

</html>