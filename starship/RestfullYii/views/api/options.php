<?php
	@header("Access-Control-Allow-Origin: $origin");
	@header("Access-Control-Max-Age: $max_age");
	@header("Access-Control-Allow-Methods: " . implode(', ', $allowed_methods));
	@header("Access-Control-Allow-Headers: " . implode(', ', $allowed_headers));

	//Dump the header info for CLI requests. Makes testing easier.
	if(php_sapi_name() === 'cli') {
		echo CJSON::encode([
			"Access-Control-Allow-Origin:"		=> $origin,
			"Access-Control-Max-Age" 					=> $max_age,
			"Access-Control-Allow-Methods"		=> implode(', ', $allowed_methods),
			"Access-Control-Allow-Headers: "	=> implode(', ', $allowed_headers),
		]);
	}
