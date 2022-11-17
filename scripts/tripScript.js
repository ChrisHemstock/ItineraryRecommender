document.querySelector('#trips ul').addEventListener('click', function(e) {
    if(e.target.classList[0] == 'close') {
        // $.ajax({
        //     type: 'POST',
        //     url: 'resources/deleteTrip.php',
        //     data: { 'tripData': tmp },
        //     success: function (msg) {
        //         console.log("success")
        //         console.log(msg);
        //     },
        //     error: function (XMLHttpRequest, textStatus, errorThrown) {
        //         alert("Status: " + textStatus); alert("Error: " + errorThrown);
        //     }
        // });
    }
});