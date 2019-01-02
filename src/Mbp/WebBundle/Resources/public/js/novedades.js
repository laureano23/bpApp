$(window).load(function() {
    $("img").on("click", function() {
    	console.log("hola");
	   $('#imagepreview').attr('src', $(this).attr('src')); // here asign the image to the modal when the user click the enlarge link
	   $('#myModal').modal('show'); // imagemodal is the id attribute assigned to the bootstrap modal, then i use the show function
	});	
});
