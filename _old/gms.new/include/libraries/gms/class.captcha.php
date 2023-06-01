<?
defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' );

/*
$_SESSION['security_code']
*/
class Captcha
{
	var $font = '/fonts/monofont.ttf';

	function generateCode($characters) 
	{
		/* list all possible characters, similar looking characters and vowels have been removed */
		$possible = '23456789bcdfghjkmnpqrstvwxyz';
		$code = '';
		$i = 0;
		while ($i < $characters) { 
			$code .= substr($possible, mt_rand(0, strlen($possible)-1), 1);
			$i++;
		}
		return $code;
	}

	function Captcha($width='120',$height='40',$characters='6') 
	{
		$fontPath = dirname(__FILE__) . $this->font;
		
		$code = $this->generateCode($characters);
		/* font size will be 75% of the image height */
		$font_size = $height * 0.75;
		$image = @imagecreate($width, $height);
		/* set the colours */
		$background_color = imagecolorallocate($image, 255, 255, 255);
		$text_color = imagecolorallocate($image, 36, 36, 36);
		$noise_color = imagecolorallocate($image, 117, 117, 117);
		/* generate random dots in background */
		for( $i=0; $i<($width*$height)/3; $i++ ) {
			imagefilledellipse($image, mt_rand(0,$width), mt_rand(0,$height), 1, 1, $noise_color);
		}
		/* generate random lines in background */
		for( $i=0; $i<($width*$height)/150; $i++ ) {
			imageline($image, mt_rand(0,$width), mt_rand(0,$height), mt_rand(0,$width), mt_rand(0,$height), $noise_color);
		}
		
		/* create textbox and add text */
		$textbox = @imagettfbbox($font_size, 0, $fontPath, $code);
		$x = ($width - $textbox[4])/2;
		$y = ($height - $textbox[5])/2;
		@imagettftext($image, $font_size, 0, $x, $y, $text_color, $fontPath , $code);
		/* output captcha image to browser */
		header('Content-Type: image/jpeg');
		imagejpeg($image);
		imagedestroy($image);
		$_SESSION['security_code'] = $code;
	}
}


function CaptchaRender($name="p_security_code", $attr = " style='width:100px' ") 
{
	$ret = "<table cellpadding='2' cellspacing='1' border='0' class='m_n'>";
	$ret .= "<tr>";
	$ret .= "<td><img src='index.php?captcha=true&width=100&height=40&characters=5' border='1'/></td>";
	$ret .= "<td align='center'><div style='padding:3px'>" . core_securityCode . "</div><input type='text' class='m_tb' name='" . $name . "' id='" . $name . "' " . $attr . "></td>";
	$ret .= "</tr>";
	$ret .= "</table>";
	return $ret;
}
?>