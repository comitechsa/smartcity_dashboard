<?php
	function url_origin( $s, $use_forwarded_host = false )
	{
		$ssl      = ( ! empty( $s['HTTPS'] ) && $s['HTTPS'] == 'on' );
		$sp       = strtolower( $s['SERVER_PROTOCOL'] );
		$protocol = substr( $sp, 0, strpos( $sp, '/' ) ) . ( ( $ssl ) ? 's' : '' );
		$port     = $s['SERVER_PORT'];
		$port     = ( ( ! $ssl && $port=='80' ) || ( $ssl && $port=='443' ) ) ? '' : ':'.$port;
		$host     = ( $use_forwarded_host && isset( $s['HTTP_X_FORWARDED_HOST'] ) ) ? $s['HTTP_X_FORWARDED_HOST'] : ( isset( $s['HTTP_HOST'] ) ? $s['HTTP_HOST'] : null );
		$host     = isset( $host ) ? $host : $s['SERVER_NAME'] . $port;
		return $protocol . '://' . $host;
	}

	function full_url( $s, $use_forwarded_host = false )
	{
		return url_origin( $s, $use_forwarded_host ) . $s['REQUEST_URI'];
	}

	$absolute_url = full_url( $_SERVER );
	//echo $absolute_url;
	//echo $_SERVER['REQUEST_URI'];
	//echo $_SERVER['HTTP_HOST'];
	$arr = explode(".",$_SERVER['HTTP_HOST']);
	$url = "https://app.smartiscity.gr/index.php?c=".$arr[0];
	redirect($url);
	function redirect($url, $statusCode = 303)
	{
	   header('Location: ' . $url, true, $statusCode);
	   die();
	}

?>