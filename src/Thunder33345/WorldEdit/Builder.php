<?php
/** Created By Thunder33345 **/
namespace Thunder33345\WorldEdit\Commands;

use Thunder33345\WorldEdit\ObjectGetter;

class Builder
{
	use ObjectGetter;
	static private $instance;
	private function __clone(){}
	public function __construct()
	{
		self::$instance = $this;
	}
	static public function getInstance(){
		return self::$instance;
	}
}