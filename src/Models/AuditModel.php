<?php namespace Spreadaurora\Ci4_logs\Models;

use CodeIgniter\Model;

class AuditModel extends Model
{
	protected $table      = 'audits';
	protected $primaryKey = 'id';

	protected $returnType = 'object';
	protected $useSoftDeletes = false;

	protected $allowedFields = ['source', 'source_id', 'user_id', 'event', 'summary', 'created_at'];

	protected $useTimestamps = false;

	protected $validationRules    = [];
	protected $validationMessages = [];
	protected $skipValidation     = false;
}
