let map = L.map('map').setView([39.80924029431849, -86.16061656273943], 13);

L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '© OpenStreetMap'
}).addTo(map);

containerEvent(document.getElementById('poi'))
let json = JSON.parse(data)
json.data.forEach(poi => {
    L.marker([poi[0], poi[1]]).on('click', function(e) {
        //adds an event to the last day on the itinerary
        addEvent(poi)
    }).bindPopup(poi[6]).on('mouseover', function (e) {
        this.openPopup();
    }).on('mouseout', function (e) {
        this.closePopup();
    }).addTo(map)
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
            return {offset: offset, element: child}
        
        } else {
            return closest
        }
    }, {offset: Number.NEGATIVE_INFINITY}).element
}

//addEvent adds an Event to the itinerary
function addEvent(element) {       
    let html = '<li class="draggable ' + element[3] + '" draggable="true">' + element[6] + 
                '<span class="time"><input type="time" class="startEvent" title="Start Time" value="'+ getStartTime() + 
                '"/><input type="time" class="endEvent" title="End Time" value="' + incrementTime(getStartTime(), 30) + '" onchange="updateTimes(0)"/></span>' +
                '<span class="close">X</span></li>';
    let poi = document.getElementById('poi')
    poi.insertAdjacentHTML('beforeend', html);

    let newElement = [...document.querySelectorAll('.draggable:not(.dragging)')].pop()
    addEventEventListeners(newElement)

    function getStartTime() {
        if([...document.querySelectorAll('.draggable:not(.dragging)')].length == 0) {
            console.log(getItineraryStartTime())
            return getItineraryStartTime()
        } else {
            return [...document.querySelectorAll('.draggable:not(.dragging)')].pop().querySelector('.endEvent').value
        }
    }
}

function incrementTime(time, minutesAdded) {
    let timeArray = time.split(':')
    timeArray[0] = Number(timeArray[0])
    timeArray[1] = Number(timeArray[1]) + minutesAdded
    while(timeArray[1] >= 60) {
        timeArray[1] = timeArray[1] - 60;
        timeArray[0] += 1;
        if(timeArray[0] > 23) {
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

    element.childNodes[1].firstChild.addEventListener('change', function(e) {
        console.log('here')
        setItineraryStartTime(document.getElementById('poi').childNodes[0].childNodes[1].firstChild.value)
        updateTimes(0)
    })

    //allows buttons to be closed
    let closebtns = document.getElementsByClassName("close");
    for (let i = 0; i < closebtns.length; i++) {
        closebtns[i].addEventListener("click", function() {
            this.parentElement.remove()
        });
    }
}

function createItineraryJson() {

    let dayString = `{ "userId": ${1}, "tripId": ${1}, "tripName": "${document.getElementById('name').value.replace(/[^a-zA-Z0-9 ]/g, "")}", "pois": [`
    let pois = [...document.getElementsByClassName('draggable')];
    pois.forEach(poi => {
        dayString += `{"poiId": ${poi.className.split(' ')[1]},"poiName": "${poi.textContent.slice(0, -1)}","startTime": "${poi.querySelector(".startEvent").value}","endTime": "${poi.querySelector(".endEvent").value}"},`;
    });
    dayString = dayString.slice(0, -1)
    dayString += ']}'
    console.log(dayString)
    document.location = 'http://localhost:8080/TripRecommender/ItineraryRecommender/TestFetch.php?tripData='+dayString;   


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
    for(let i = 0; i < startTime.length; i++) {
        startTime[i] = Number(startTime[i])
        endTime[i] = Number(endTime[i])
    }
    if(startTime[1] <= endTime[1]) {
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
    if(value != undefined) {
        document.getElementById('poi').dataset.starttime = document.getElementById('poi').firstChild.childNodes[1].firstChild.value
    } else {
        document.getElementById('poi').dataset.starttime = '00:00'
    }
}

function updateTimes(indexCurrent) {
    let list = document.getElementById('poi').childNodes

    if(indexCurrent != list.length) {
        let startTimeInput = document.getElementById('poi').childNodes[indexCurrent].childNodes[1].firstChild.value
        let endTimeInput = document.getElementById('poi').childNodes[indexCurrent].childNodes[1].lastChild.value
        let currentDuration = getDuration(startTimeInput, endTimeInput)
        if(currentDuration < 0) {
            currentDuration = 30
        }
        if(indexCurrent == 0) {
            console.log('startTime: ' + getItineraryStartTime())
            document.getElementById('poi').childNodes[indexCurrent].childNodes[1].firstChild.value = getItineraryStartTime()
        } else {
            document.getElementById('poi').childNodes[indexCurrent].childNodes[1].firstChild.value = document.getElementById('poi').childNodes[indexCurrent - 1].childNodes[1].lastChild.value
        }
        document.getElementById('poi').childNodes[indexCurrent].childNodes[1].lastChild.value = incrementTime(document.getElementById('poi').childNodes[indexCurrent].childNodes[1].firstChild.value, currentDuration)
        updateTimes(indexCurrent + 1)
    }
}

