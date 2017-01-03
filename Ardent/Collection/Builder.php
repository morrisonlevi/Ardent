<?php

namespace Ardent\Collection;


interface Builder {

	function add($key, $value);

	/**
	 * @return \Ardent\Collection
	 */
	function result();

}
