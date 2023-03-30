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
function addEvent(api_id, name, url, startTime, endTime) {
    let html = '<li class="draggable ' + api_id + '" draggable="true">' +
    '<a target="_blank" href="' + url + '">' + name + '</a>' +
    '<span class="time">' +
        '<input type="time" class="startEvent" title="Start Time" value="' + startTime + '"/>' +
        '<input type="time" class="endEvent" title="End Time" value="' + endTime + '" onchange="updateTimes(0)"/>' +
    '</span>' +
    '<span class="close">X</span>' +
'</li>';
    let poi = document.getElementById('poi')
    poi.insertAdjacentHTML('beforeend', html);

    let newElement = [...document.querySelectorAll('.draggable:not(.dragging)')].pop()
    addEventEventListeners(newElement)
}

//returns the stat time of the trip
function getStartTime() {
    if ([...document.querySelectorAll('.draggable:not(.dragging)')].length == 0) {
        // console.log(getItineraryStartTime())
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
        tripString += `{"apiId": "${poi.className.split(' ')[1]}", "startTime": "${poi.querySelector(".startEvent").value}","endTime": "${poi.querySelector(".endEvent").value}"},`;
    });
    tripString = tripString.slice(0, -1)
    tripString += ']}';
    return tripString;
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

function sortByValue(json){
    console.log(json)
    let sortedArray = [];
    for(let i in json)
    {
        // Push each JSON Object entry in array by [value, key]
        sortedArray.push([json[i], i]);
    }
    return sortedArray.sort().reverse();
}

function getItineraryApis() {
    //get all pois currently in the itinerary
    let itineraryApiList = []
    let itineraryElements = document.getElementById('poi').childNodes
    itineraryElements.forEach(element => {
        let classes = element.className;
        classes = classes.split(" ");
        const apiId = 1
        itineraryApiList.push(classes[apiId])
    });
    return itineraryApiList;
}

function checkPoiSaved(itineraryPoiArray, apiId) {
    let saved = false;
    itineraryPoiArray.forEach(ItineraryApiId => {
        if(apiId == ItineraryApiId) {
            saved = true;
            return;
        }
    });
    return saved;
}

function getRecommendationArray(recommendationList, allPoisJson, itineraryApiArray, amount) {
    console.log(recommendationList)
    let recommendedJson = JSON.parse(recommendationList)
    let poiRecommendationArray = sortByValue(recommendedJson) // [[tfidfValue, id], [tfidfValue, id], ...]
    const recommendationApiId = 1

    //get a list of all the pois (comes from functions.php createMapPoiJson())
    let allPoisList = JSON.parse(allPoisJson) // List of all the Pois
    const poiApiId = 3
    const poiName = 6
    const url = 9

    let recomendationArray = [];
    count = 0 // Makes sure that only n number of elements are actually displayed
    for(let j = 0; j < poiRecommendationArray.length; j++) {
        for(let i = 0; i < allPoisList.data.length; i++) {
            if(poiRecommendationArray[j][recommendationApiId] == allPoisList.data[i][poiApiId]) {
                // Make sure the api_id isn't in the itinerary already
                if(checkPoiSaved(itineraryApiArray, allPoisList.data[i][poiApiId])) {
                    break;
                }
                // Here if it isn't saved and its going to be recommended
                recomendationArray.push([
                    poiRecommendationArray[j][recommendationApiId], 
                    allPoisList.data[i][poiName], 
                    allPoisList.data[i][url],
                ])
                count++;
                break;
            }
        }
        if(count==amount) {
            break;
        }
    }
    return recomendationArray;
}


//adds the recommendations to the itinerary list
function displayRecommendations(recommendedArray) {
    // Check if there is nothing left to recommend
    if(recommendedArray.length == 0) {
        feedback("Out of recommendations!")
    }

    for (let index = 0; index < recommendedArray.length; index++) {
        const apiId = recommendedArray[index][0];
        const name = recommendedArray[index][1];
        const url = recommendedArray[index][2];

        addEvent(apiId, name, url, '00:00', '00:30');
        updateTimes(0);
    }
    
    
}

//popup for when the save button is clicked
function feedback(message) {
    alert(message);
    return true;
}

function changeColor(savedPois, marker, apiId) {
    //console.log(savedPois);
    for (let poi of savedPois) {
      const SAVED_API_ID = poi[0]
      if (SAVED_API_ID == apiId) {
        marker._icon.style.filter = "hue-rotate(120deg)"
        //console.log(marker._icon.style.filter);
        return true
      }
    }
    return false
}
  
  function getNewCoordinate(savedPois, apiId) {
    let cord = []
    savedPois.forEach(poi => {
        const SAVED_API_ID = poi[0]
        const START_TIME = poi[1]
        const LAT = poi[5]
        const LONG = poi[6]
        if(SAVED_API_ID == apiId){
            cord = [LAT, LONG, START_TIME];
            return;
        }
    });
    return cord;
}
  
  

module.exports = {sortByValue, changeColor, getNewCoordinate, checkPoiSaved, getRecommendationArray};
