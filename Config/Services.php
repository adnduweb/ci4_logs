<?php

namespace Adnduweb\Ci4_logs\Config;

use CodeIgniter\Config\BaseService;

class Services extends BaseService
{
	public static function audits(BaseConfig $config = null, bool $getShared = true)
	{
		if ($getShared) {
			return static::getSharedInstance('audits', $config);
		}

		// If no config was injected then load one
		if (empty($config)) {
			$config = config('Audits');
		}

		return new \Adnduweb\Ci4_logs\Audits($config);
	}
}
