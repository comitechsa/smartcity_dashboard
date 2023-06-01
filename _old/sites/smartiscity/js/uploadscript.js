$(document).ready(function (e) {
$("#__PageForm").on('submit',(function(e) {
	e.preventDefault();
	$("#message").empty();
	$('#loading').show();
	$.ajax({
		url: "/upload/ajax_php_file.php", // Url to which the request is send
		type: "POST",             // Type of request to be send, called as method
		data: new FormData(this), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
		contentType: false,       // The content type used when sending data to the server.
		cache: false,             // To unable request pages to be cached
		processData:false,        // To send DOMDocument or non processed data file it is set to false
		success: function(data)   // A function to be called if request succeeds
		{
			$('#loading').hide();
			$("#message").html(data);
		}
	});
	}));

	// Function to preview image after validation
	$(function() {
		$("#file").change(function() {
		$("#message").empty(); // To remove the previous error message
		var file = this.files[0];
		var xlsfile = file.type;
		var match= ["application/vnd.ms-excel","application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"];
		if(!((xlsfile==match[0]) || (xlsfile==match[1]))){
			//$('#previewing').attr('src','noimage.png');
			$("#message").html("<p id='error'>Please Select A valid Image File</p>"+"<h4>Note</h4>"+"<span id='error_message'>Only xls, xlsx type allowed</span>");
			return false;
		} else {
			var reader = new FileReader();
			reader.onload = imageIsLoaded;
			reader.readAsDataURL(this.files[0]);
		}
	});
	});
	function imageIsLoaded(e) {
		$("#file").css("color","green");
		$('#image_preview').css("display", "block");
		$('#previewing').attr('src', e.target.result);
		$('#previewing').attr('width', '250px');
		$('#previewing').attr('height', '230px');
	};
});