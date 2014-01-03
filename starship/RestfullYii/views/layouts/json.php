<?php
	ob_clean(); // clear output buffer to avoid rendering anything else
	@header('Content-type: application/json');
	@header($this->getHttpStatus());
	if($this->emitRest(
		ERestEvent::REQ_AUTH_CORS,
		[$this->emitRest(ERestEvent::REQ_CORS_ACCESS_CONTROL_ALLOW_ORIGIN)]
	)) {
		@header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
	}
	echo $content;
