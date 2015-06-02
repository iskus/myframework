<?php
	namespace core\database;

	/**
	 * Iskus Anton. Email: iskus1981@yandex.ru
	 * IDE PhpStorm. 15.04.2015
	 */

	class MysqlDbConnection extends \mysqli {
		public $table;
		
		public function insert(\stdClass $obj) {
			$values = [];
			foreach ($obj as $field => $value) {
				$values[$field] = $value;
			}
			$inc = "INSERT";
			if ($values['replace'] === TRUE) {
				unset($values['replace']);
				$inc = "REPLACE";
			}
			$query = "{$inc} INTO {$this->table} (" . implode(',',  array_keys($values))
			         . ") VALUES ('" . implode("','",  $values) . "')";
			//echo $query;
			return $this->query($query);

		}

		public function getRow($id) {
			$query = "SELECT * FROM {$this->table} WHERE id = " . (int)$id;
			echo $query;
			$result = $this->query($query);
			return $result->fetch_object();
		}

		public function update($id, $params) {
			$set = [];
			foreach ($params as $key => $val) {
				$set[] = "$key = '{$val}'";
			}
			$query = "UPDATE {$this->table} SET " . implode(', ', $set) . " WHERE id = " . (int)$id;
			return $this->query($query);

		}

		public function getRows($params = []) {
			$where = '';
			if (is_array($params) && !empty($params)) {
				$where = [];
				foreach ($params as $key => $val) {
					$where[] = 	"$key = '{$val}'";
				}
				$where = "WHERE " . implode(' AND ', $where);
			}
			$query = "SELECT * FROM {$this->table} {$where}";

			if (!$result = $this->query($query)) return FALSE;

			$out = [];
			while ($row = $result->fetch_object()) {
				$out[$row->id] = $row;
			}
			return $out;
		}

	}