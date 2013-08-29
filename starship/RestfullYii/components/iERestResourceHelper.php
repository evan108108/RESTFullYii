<?php

interface iERestResourceHelper
{
	public function __construct(Callable $emitter);

	public function setEmitter(Callable $emitter);

	public function getEmitter();	
}
