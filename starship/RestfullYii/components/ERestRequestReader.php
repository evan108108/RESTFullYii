<?php
/**
 * ERestRequestReader
 * 
 * Provides an interface for reading REST request data
 *
 * @category   PHP
 * @package    Starship
 * @subpackage Restfullyii/components
 * @copyright  Copyright (c) 2013 Evan Frohlich (https://github.com/evan108108)
 * @license    https://github.com/evan108108   OSS
 * @version    Release: 1.2.0
 *
 * @property (Mixed)	filename
 */
class ERestRequestReader {
	protected $filename;

	/**
	 * __construct
	 *
	 * @param (String) (filename) file path or stream URI
	 */
	public function __construct($filename = 'php://input') {
		$this->filename = $filename;
	}

	/**
	 * getContents
	 *
	 * Read the request data
	 *
	 * @return (mixed) request data
	 */
	public function getContents() {
		if(is_resource($this->filename)) {
			rewind($this->filename);
			return stream_get_contents($this->filename);
		}
		return file_get_contents($this->filename);
	}
}
