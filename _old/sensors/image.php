<?php

function base64_to_jpeg($base64_string, $output_file) {
    // open the output file for writing
    $ifp = fopen( $output_file, 'wb' ); 

    // split the string on commas
    // $data[ 0 ] == "data:image/png;base64"
    // $data[ 1 ] == <actual base64 string>
    $data = explode( ',', $base64_string );

    // we could add validation here with ensuring count( $data ) > 1
    fwrite( $ifp, base64_decode( $data[ 1 ] ) );

    // clean up the file resource
    fclose( $ifp ); 

    return $output_file; 
}

//echo '<img src="'.base64_to_jpeg($string,'test.jpg').'">';


$filename_path = md5(time().uniqid()).".jpg";
$base64_string = str_replace('data:image/png;base64,', '', $base64_string);
$base64_string = str_replace(' ', '+', $base64_string);
$decoded = base64_decode($base64_string);
file_put_contents("./".$filename_path,$decoded);

?>
