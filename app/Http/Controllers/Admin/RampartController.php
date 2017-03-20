<?php

namespace App\Http\Controllers\Admin;

use LockFinder\Controllers\RampartMasterController as Rampart;

class RampartController extends Rampart
{
    /**
     * Unique Rampart application key. DO NOT REMOVE
     *
     * @var string
     */
	protected $appKey;

	/**
	 * class constructor
	 */
	public function __construct() {
		$this->appKey = env("RAMPART_API_TOKEN", null);

		parent::__construct();
	}
}