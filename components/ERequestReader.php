<?php
	class ERequestReader {
		protected $filename;

		public function __construct($filename = 'php://input') {
			$this->filename = $filename;
		}

		public function getContents() {
			return file_get_contents($this->filename);
		}
	}
