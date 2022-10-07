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
                    var html = '<li id="' + element.id + '">' + element.tags.name + '<span class="close">X</span></li>';
                    document.getElementById('poiList').insertAdjacentHTML('beforeend', html);

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


    
