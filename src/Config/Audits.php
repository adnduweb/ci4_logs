<?php namespace Spreadaurora\Ci4_logs\Config;

use CodeIgniter\Config\BaseConfig;

class Audits extends BaseConfig
{
	// key in $_SESSION that contains the integer ID of a logged in user
	public $sessionUserId = "logged_in";

	// whether to continue instead of throwing exceptions
	public $silent = true;
}
