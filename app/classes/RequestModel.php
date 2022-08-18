<?php
	namespace classes;


	use core\Model;

	class RequestModel extends Model{

		public $table = 'request';

		public function select(){
			$query = $this->db->query("SELECT COUNT(*) as over, (SELECT COUNT(*) FROM {$this->table} WHERE JSON_EXTRACT(header, '$.new') = 0) AS al  FROM {$this->table}");
			return $query->fetch(\PDO::FETCH_ASSOC);
		}

		public function insert($data){
			$stmt= $this->db->prepare("INSERT INTO {$this->table} (url,code, header, content) VALUES (:url,:code, :header, :content)");
			$stmt->execute($data);
			return true;
		}
	}