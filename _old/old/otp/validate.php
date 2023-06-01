<?php
	$user=$_GET['id'];
?>
<!DOCTYPE html>
<html>
	<head><title>OTP Demo Page</title>
		<script>
			function verify () {
				// verify() : verify OTP
				// APPEND FORM DATA
				var data = new FormData();
				data.append('pass', document.getElementById("otp_pass").value);
				data.append('id', document.getElementById("otp_id").value);
				// INIT AJAX
				var xhr = new XMLHttpRequest();
				xhr.open('POST', "verify.php", true);
				// WHEN THE PROCESS IS COMPLETE
				xhr.onload = function(){
				var res = JSON.parse(this.response);
				if (res.status) {
						// OK - DO SOMETHING - REDIRECT USER TO ANOTHER PAGE OR CONTINUE TRANSACTION
						alert("OK");
					} else {
						// ERROR - DO SOMETHING
						alert(res.message);
					}
				};

				// SEND
				xhr.send(data);
				return false;
			}
		</script>
	</head>
	<body>
		<form onsubmit="return verify();">
			OTP Password
			<input type="password" id="otp_pass" required/>	<br>
			<input type="hidden" id="otp_id" readonly value="<?=$user?>"/><br>
			<input type="submit" value="Go"/>
		</form>
	</body>
</html>