<?php
echo (is_array($JSON)? CJSON::encode($JSON): $JSON);
