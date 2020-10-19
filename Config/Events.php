<?php namespace Adnduweb\Ci4Logs\Config;

use CodeIgniter\Events\Events;

Events::on('post_system', function () {
	service('audits')->save();
});
