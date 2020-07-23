<?php

// access to https://dev.v3.engagemanager.com/token/fb for getting longline page token

if(isset($_GET['code'])){
	$uri = "https://graph.facebook.com/oauth/access_token?";
	$uri .= "client_id=733399656843403&redirect_uri=https://dev.v3.engagemanager.com/token/fb&client_secret=49c961d7d966816ae9889bc135bbeabe&code=";

	$uri .= $_GET["code"];
	$res = file_get_contents($uri);

	print_r(json_decode($res));
}else{
	$url = "https://www.facebook.com/dialog/oauth?client_id=733399656843403&redirect_uri=https://dev.v3.engagemanager.com/token/fb";
	header("Location: {$url}");

	exit;
}
