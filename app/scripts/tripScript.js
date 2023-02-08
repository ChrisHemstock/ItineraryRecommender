document.querySelector('#trips ul').addEventListener('click', function(e) {
    if(e.target.classList[0] == 'close') {
        
        let id = e.target.classList[1];
        $.ajax({
            type: 'POST',
            url: 'resources/deleteTrip.php',
            data: { 'tripID': id },
            success: function (msg) {
                console.log("success")
                e.target.parentElement.remove()
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert("Status: " + textStatus); alert("Error: " + errorThrown);
            }
        });
    }
});