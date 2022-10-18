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

function addDay() {
    var html = '<ul class="itineraryDay"><li><label>Day <input type="date" class="day" value="' + getDate() + '"/></label></li></ul>'
    let day = document.getElementById('poi')
    day.insertAdjacentHTML('beforeend', html);

    let container = [...document.querySelectorAll('.itineraryDay')].pop()
    containerEvent(container)

    function getDate() {
        let days = [...document.querySelectorAll('.day')]
        if(days.length == 0) {
            let date = new Date()
            return date.getFullYear() + '-' + (date.getMonth() + 1).toString().padStart(2, '0') + '-' + date.getDate().toString().padStart(2, '0')
        } else {
            let day = days.pop().getAttribute('value').split('-')
            let date = new Date(day[0], day[1] - 1, day[2])
            date.setDate(date.getDate() + 1)
            return date.getFullYear() + '-' + (date.getMonth() + 1).toString().padStart(2, '0') + '-' + date.getDate().toString().padStart(2, '0')
        }
    }
}

function addEvent(element) {
    if ([...document.querySelectorAll('.itineraryDay')].length > 0) {
    // if(!document.getElementById(element.id)) {
    //     let html = '<li id="' + element.id + '" class="draggable" draggable="true">' + element.tags.name + '<span class="close">X</span></li>';
        let html = '<li class="draggable" draggable="true">' + element.tags.name + 
                    '<span class="time"><input type="time" class="startEvent" title="Start Time"/><input type="time" class="endEvent" title="End Time"/></span>' +
                    '<span class="close">X</span></li>';
        let day = [...document.querySelectorAll('.itineraryDay')].pop()
        day.insertAdjacentHTML('beforeend', html);

        let newElement = [...document.querySelectorAll('.draggable:not(.dragging)')].pop()
        newElement.addEventListener('dragstart', () => {
            newElement.classList.add('dragging')
        })
    
        newElement.addEventListener('dragend', () => {
            newElement.classList.remove('dragging')
        })


        //allows buttons to be closed
        let closebtns = document.getElementsByClassName("close");
        for (let i = 0; i < closebtns.length; i++) {
            closebtns[i].addEventListener("click", function() {
                this.parentElement.remove()
            });
        }
    // }
    }
}