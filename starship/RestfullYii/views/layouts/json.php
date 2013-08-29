<?php
  ob_clean(); // clear output buffer to avoid rendering anything else
  @header('Content-type: application/json');
  @header($this->getHttpStatus());
  echo $content;
