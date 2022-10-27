function createItineraryJson() {
   var userID = "Testing";
   $.ajax({
      type: "POST",
      url: 'index.php',
      data: { userID : userID },
      success: function(data)
      {
          alert("success!");
      }
  });
}