<?php

namespace Ardent\Collection;


interface Builder {

	/**
	 * @param $key
	 * @param $value
	 * @return void
	 */
	function add($key, $value);

	/**
	 * @return \Traversable
	 */
	function result();

}
