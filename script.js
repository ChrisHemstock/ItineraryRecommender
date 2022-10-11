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
                //only adds list element if an element with that id doesn't exist
                if(!document.getElementById(element.id)) {
                    //adds a list element to the side
                    var html = '<li id="' + element.id + '" class="draggable" draggable="true">' + element.tags.name + '<span class="close">X</span></li>';
                    document.getElementById('poiList').insertAdjacentHTML('beforeend', html);

                    let newElement = [...document.querySelectorAll('.draggable:not(.dragging)')].pop()
                    newElement.addEventListener('dragstart', () => {
                        console.log("1")
                        newElement.classList.add('dragging')
                    })
                
                    newElement.addEventListener('dragend', () => {
                        console.log("2")
                        newElement.classList.remove('dragging')
                    })


                    //allows buttons to be closed
                    let closebtns = document.getElementsByClassName("close");
                    for (let i = 0; i < closebtns.length; i++) {
                        closebtns[i].addEventListener("click", function() {
                            this.parentElement.remove()
                        });
                    }
                }
            }).bindPopup(element.tags.name).on('mouseover', function (e) {
                this.openPopup();
            }).on('mouseout', function (e) {
                this.closePopup();
            }).addTo(map)
        });
        
    })

const containers = document.querySelectorAll('#poiList')

containers.forEach(container => {
    container.addEventListener('dragover', e => {
        e.preventDefault()
        const afterElement = getDragAfterElement(container, e.clientY)
        const draggable = document.querySelector('.dragging')
        if (afterElement == null) {
            console.log("3")
            container.appendChild(draggable)
        } else {
            console.log("4")
            container.insertBefore(draggable, afterElement)
        }
    })
})

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