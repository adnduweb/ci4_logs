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

	public function __construct()
    {
        parent::__construct();
        $this->audits = $this->db->table('audits');
    }

	public function getAllList(int $page, int $perpage, array $sort, array $query)
    {
        $this->audits->select();
        $this->audits->select('created_at as date_create_at');
        if (isset($query['generalSearch']) && !empty($query['generalSearch'])) {
            $this->audits->where('(source LIKE "%' . $query['generalSearch'] . '%" OR event LIKE "%' . $query['generalSearch'] . '%")');
            $this->audits->limit(0, $page);
        } else {
            $page = ($page == '1') ? '0' : (($page - 1) * $perpage);
            $this->audits->limit($perpage, $page);
        }
        $this->audits->orderBy($sort['field'] . ' ' . $sort['sort']);

        $groupsRow = $this->audits->get()->getResult();

        //echo $this->audits->getCompiledSelect(); exit;
        return $groupsRow;
    }

    public function getAllCount(array $sort, array $query)
    {
        $this->audits->select('id');
        if (isset($query['generalSearch']) && !empty($query['generalSearch'])) {
            $this->audits->where('(source LIKE "%' . $query['generalSearch'] . '%" OR event LIKE "%' . $query['generalSearch'] . '%")');
		} 

        $this->audits->orderBy($sort['field'] . ' ' . $sort['sort']);

        $users = $this->audits->get();
        return $users->getResult();
    }
}
