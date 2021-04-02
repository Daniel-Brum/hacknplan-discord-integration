<?php

$php_minimum_version_array = array(5, 2, 0);
$curl_minimum_version_array = array(7, 10, 5);

echo 'Does the server use a supported PHP version? ';

$php_current_version = phpversion();

$php_current_version_array = explode('.', $php_current_version);

$minimum_php_version_supported = false;

for($i = 0; $i < 3; $i++) {
	if(intval($php_current_version_array[$i], 10) > $php_minimum_version_array[$i]) {
		$minimum_php_version_supported = true;
		break;
	} else if(intval($php_current_version_array[$i], 10) == $php_minimum_version_array[$i]) {
		if($i == 2) {
			$minimum_php_version_supported = true;
		}
	} else {
		break;
	}
}

if($minimum_php_version_supported) {
	echo 'Yes'.PHP_EOL.PHP_EOL;
} else {
	echo 'No'.PHP_EOL.PHP_EOL;
}

echo 'Does the server use a supported cURL version? ';

if(is_array(curl_version())) {
	$curl_current_version = curl_version()['version'];
	
	$curl_current_version_array = explode('.', $curl_current_version);
	
	$minimum_curl_version_supported = false;

	for($i = 0; $i < 3; $i++) {
		if(intval($curl_current_version_array[$i], 10) > $curl_minimum_version_array[$i]) {
			$minimum_curl_version_supported = true;
			break;
		} else if(intval($curl_current_version_array[$i], 10) == $curl_minimum_version_array[$i]) {
			if($i == 2) {
				$minimum_curl_version_supported = true;
			}
		} else {
			break;
		}
	}

	if($minimum_curl_version_supported) {
		echo 'Yes'.PHP_EOL.PHP_EOL;
	} else {
		echo 'No'.PHP_EOL.PHP_EOL;
	}
	
} else {
	echo 'No';
}

echo PHP_EOL;
