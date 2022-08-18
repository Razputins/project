<?php
	namespace classes;


	use core\Model;

	class RequestModel extends Model{

		public $table = 'request';

		public function select(){
			$query = $this->db->query("SELECT COUNT(*) as over, SUM(CASE WHEN JSON_EXTRACT(header, '$.new') = 1 THEN 1 ELSE 0 END) AS new FROM {$this->table}");
			return $query->fetch(\PDO::FETCH_ASSOC);
		}

		public function insert($data){
			$stmt= $this->db->prepare("INSERT INTO {$this->table} (url,code, header, content) VALUES (:url,:code, :header, :content)");
			$stmt->execute($data);
			return true;
		}
	}