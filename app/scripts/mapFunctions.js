//Adds the drag event to a container
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

//
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

//returns the stat time of the trip
function getStartTime() {
    if ([...document.querySelectorAll('.draggable:not(.dragging)')].length == 0) {
        console.log(getItineraryStartTime())
        return getItineraryStartTime()
    } else {
        return [...document.querySelectorAll('.draggable:not(.dragging)')].pop().querySelector('.endEvent').value
    }
}

//adds the minutes to the time and returns it as a string
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

//adds all the event listeners to the elements in the list
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

//converts the itinerary list into a Json that can be read
function createItineraryJson() {
    let tripString = `{"tripId": "${window.location.href.split('&')[0].split('=')[1]}", "pois": [ `
    let pois = [...document.getElementsByClassName('draggable')];
    pois.forEach(poi => {
        tripString += `{"apiId": ${poi.className.split(' ')[1]}, "startTime": "${poi.querySelector(".startEvent").value}","endTime": "${poi.querySelector(".endEvent").value}"},`;
    });
    tripString = tripString.slice(0, -1)
    tripString += ']}';
    return tripString;
}

//adds the saved POIs to the itinerary from a json
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

//returns a time in minutes that is the time from startTime to endTime
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

//gets the starting time of the itinerary
function getItineraryStartTime() {
    return document.getElementById('poi').dataset.starttime
}


//sets the start time of the itinerary
function setItineraryStartTime(value) {
    if (value != undefined) {
        document.getElementById('poi').dataset.starttime = document.getElementById('poi').firstChild.childNodes[1].firstChild.value
    } else {
        document.getElementById('poi').dataset.starttime = '00:00'
    }
}


//updates the startTime and endTime for every list element from the current index
function updateTimes(indexCurrent) {
    //console.log(indexCurrent)
    let list = document.getElementById('poi').childNodes

    if (indexCurrent != list.length) {
        let startTimeInput = document.getElementById('poi').childNodes[indexCurrent].childNodes[1].firstChild.value
        let endTimeInput = document.getElementById('poi').childNodes[indexCurrent].childNodes[1].lastChild.value
        let currentDuration = getDuration(startTimeInput, endTimeInput)
        if (currentDuration < 0) {
            currentDuration = 30
        }
        if (indexCurrent == 0) {
            //console.log('startTime: ' + getItineraryStartTime())
            document.getElementById('poi').childNodes[indexCurrent].childNodes[1].firstChild.value = getItineraryStartTime()
        } else {
            document.getElementById('poi').childNodes[indexCurrent].childNodes[1].firstChild.value = document.getElementById('poi').childNodes[indexCurrent - 1].childNodes[1].lastChild.value
        }
        document.getElementById('poi').childNodes[indexCurrent].childNodes[1].lastChild.value = incrementTime(document.getElementById('poi').childNodes[indexCurrent].childNodes[1].firstChild.value, currentDuration)
        updateTimes(indexCurrent + 1)
    }
}

//adds the recommendations to the itinerary list
function displayRecommendations(recommendationList, data) {
  //console.log(recommendationList)
  let json = JSON.parse(recommendationList)
  //console.log(json) // Gives the ids and the recommendation tfidf values
  let poiIds = Object.keys(json)
  //console.log(poiIds) // Gives a list of the 10 recommendation ids

  let poiJson = JSON.parse(data)
  for(let i = 0; i < poiJson.data.length; i++) {
    //console.log(poiJson.data[i]);
    for(let j = 0; j < poiIds.length; j++) {
      if(poiJson.data[i][3] == poiIds[j]) {
        addEvent(poiIds[j], poiJson.data[i][6], '00:00', '00:30')
        updateTimes(0)
      }
    }
  }
}

//popup for when the save button is clicked
function feedback() {
    alert("Trip data Entered!");
    return true;
  }