<?php namespace Adnduweb\Ci4Logs\Traits;

use CodeIgniter\Config\Services;
use Adnduweb\Ci4Logs\Models\AuditModel;

/*** CLASS ***/
trait AuditsTrait
{
	// takes an array of model $returnTypes and returns an array of Audits, arranged by object and event
	// optionally filter by $events (string or array of strings)
	public function getAudits(array $objects, $events = null): array
	{
		if (empty($objects))
			return null;

		// get the primary keys from the objects
		$objectIds = array_column($objects, $this->primaryKey);

		$audits = new AuditModel();
		$query = $query->where('source', $this->table)
			->whereIn('source_id', $objectIds);
		if (is_string($events))
			$query = $query->where('event', $events);
		elseif (is_array($events))
			$query = $query->whereIn('event', $events);

		// index by objectId, event
		$array = [ ];
		while ($audit = $query->getUnbufferedRow()):
			if (empty($array[$audit->{$this->primaryKey}]))
				$array[$audit->{$this->primaryKey}] = [ ];

			if (empty($array[$audit->{$this->primaryKey}][$audit->event]))
				$array[$audit->{$this->primaryKey}][$audit->event] = [ ];

			$array[$audit->{$this->primaryKey}][$audit->event][] = $audit;
		endwhile;

		return $array;
	}

	// record successful insert events
	protected function auditInsert(array $data)
	{
		if (! $data['result'])
			return false;

		$audit = [
			'source'    => $this->localizeFile,
			'source_id' => $this->db->insertID() . '2',
			'event'     => 'insert',
			'summary'   => count($data['data']) . ' rows',
			'data'      => json_encode($data['data']),
		];
		Services::audits()->add($audit);

		return $data;
	}

	// record successful update events
	protected function auditUpdate(array $data)
	{
		$audit = [
			'source'    => $this->localizeFile,
			'source_id' => is_array($data['id']) ? $data['id'][0] : $data['id'],
			'event'     => 'update',
			'summary'   => count($data['data']) . ' rows',
			'data'      => json_encode($data['data']),
		];
		Services::audits()->add($audit);

		return $data;
	}

	// record successful delete events
	protected function auditDelete(array $data)
	{
		if (! $data['result'])
			return false;
		if (empty($data['id']))
			return false;

		$audit = [
			'source'    => $this->localizeFile,
			'event'     => 'delete',
			'summary'   => ($data['purge'])? 'purge' : 'soft',
		];

		// add an entry for each ID
		$audits = Services::audits();
		foreach ($data['id'] as $id):
			$audit['source_id'] = $id;
			$audits->add($audit);
		endforeach;

		return $data;
	}
}
