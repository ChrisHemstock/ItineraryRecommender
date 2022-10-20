let map = L.map('map').setView([39.80924029431849, -86.16061656273943], 13);

L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '© OpenStreetMap'
}).addTo(map);

containerEvent(document.getElementById('poi'))

fetch("nodes.json")
    .then(response => response.json())
    .then(data => {
        data.elements.forEach(element => {
            L.marker([element.lat, element.lon]).on('click', function(e) {
                //adds an event to the last day on the itinerary
                addEvent(element)
            }).bindPopup(element.tags.name).on('mouseover', function (e) {
                this.openPopup();
            }).on('mouseout', function (e) {
                this.closePopup();
            }).addTo(map)
        });
        
    })

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

function addEvent(element) {
    // if(!document.getElementById(element.id)) {
    //     let html = '<li id="' + element.id + '" class="draggable" draggable="true">' + element.tags.name + '<span class="close">X</span></li>';
        
        let html = '<li class="draggable" draggable="true">' + element.tags.name + 
                    '<span class="time"><input type="time" class="startEvent" title="Start Time" value="'+ getStartTime() + 
                    '"/><input type="time" class="endEvent" title="End Time" value="' + incrementTime(getStartTime(), 30) + '"/></span>' +
                    '<span class="close">X</span></li>';
        let poi = document.getElementById('poi')
        poi.insertAdjacentHTML('beforeend', html);

        let newElement = [...document.querySelectorAll('.draggable:not(.dragging)')].pop()
        addEventEventListeners(newElement)
    // }

    function getStartTime() {
        if([...document.querySelectorAll('.draggable:not(.dragging)')].length == 0) {
            return "00:00"
        } else {
            return [...document.querySelectorAll('.draggable:not(.dragging)')].pop().querySelector('.endEvent').value
        }
    }

    function incrementTime(time, minutesAdded) {
        let timeArray = time.split(':')
        timeArray[0] = Number(timeArray[0])
        timeArray[1] = Number(timeArray[1]) + minutesAdded
        console.log(timeArray)
        while(timeArray[1] >= 60) {
            timeArray[1] = timeArray[1] - 60;
            timeArray[0] += 1;
            if(timeArray[0] > 23) {
                timeArray[0] = 0
            }
        }
        return String(timeArray[0]).padStart(2, '0') + ':' + String(timeArray[1]).padStart(2, '0')
    }
}

function addEventEventListeners(element) {
    element.addEventListener('dragstart', () => {
        element.classList.add('dragging')
    })
    
    element.addEventListener('dragend', () => {
        element.classList.remove('dragging')
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
    let dayString = `{ "userId": ${1}, "tripId": ${1}, "tripName": "${"this is a trip name"}", "pois": [`
    let pois = [...document.getElementsByClassName('draggable')];
    pois.forEach(poi => {
        dayString += `{"poiId": ${1},"poiName": "${poi.textContent.slice(0, -1)}","startTime": "${poi.querySelector(".startEvent").value}","endTime": "${poi.querySelector(".endEvent").value}"},`;
    });
    dayString = dayString.slice(0, -1)
    dayString += ']}'
    console.log(dayString)

}

function loadItinerary() {
    fetch("test.json")
    .then(response => response.json())
    .then(data => {
        data.pois.forEach(poi => {
            let html = '<li class="draggable" draggable="true">' + poi.poiName + 
                        '<span class="time"><input type="time" class="startEvent" title="Start Time" value="' + poi.startTime + '"/><input type="time" class="endEvent" title="End Time" value="' + poi.endTime + '"/></span>' +
                        '<span class="close">X</span></li>';
            document.getElementById('poi').insertAdjacentHTML('beforeend', html);
    
            let newElement = [...document.querySelectorAll('.draggable:not(.dragging)')].pop()
            addEventEventListeners(newElement)
        });
    });
}


