<?php
/**
 * Lop Database.
 * @author Nguyen Van Dai
 * @category classes
 */
	class Database {
		/**
		 * { var_description }
		 *
		 * @var   string|null _instance 
		 */
		private static $_instance = null;
		/**
		 * @var   string _pdo Bien kiet noi co so du lieu
		 * @var   string _query Bien chua  thuc thi cau truy van
		 * @var   string|flase  _error Bien chua loi 
		 * @var   string _results Bien chua gia tri cua cau truy van thuc thi
		 * @var   string|0 _count Bien dem;
		 */
		private $_pdo,
				$_query,
				$_error = false,
				$_results,
				$_count = 0;

			/**
			 * ham khoi tao __construct().
			 * Ket noi voi dul ieu mysql
			 */
		private function __construct() {
			try {
				$this->_pdo = new PDO('mysql:host='.Config::get('mysql/host').';dbname='.Config::get('mysql/db'),Config::get('mysql/username'),Config::get('mysql/password'));
			} catch (PDOException $e) {
				die($e->getMessage());
			}
		}
		/**
		 * Gets the instance.
		 *
		 * @return     <type>  The instance.
		 */
		public static function getInstance() {
			if (!isset(self::$_instance)) {
				self::$_instance = new Database();
			}
			return self::$_instance;
		}
		/**
		 * ham thuc thi cau truy van
		 *
		 * @param      <string>  $sql     Cau truy van can thuc thi
		 * @param      array   $params  Mang tham so
		 *
		 * @return     $this.
		 */

		public function query($sql, $params = array()) {
			$this->_error = false;
			if ($this->_query = $this->_pdo->prepare($sql)) {
				$x = 1;
				if (count($params)) {
					foreach ($params as $param) {
						$this->_query->bindValue($x, $param);
						$x++;
					}
				}

				if ($this->_query->execute()) {
					$this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
					$this->_count	= $this->_query->rowCount();
				} else {
					$this->_error = true;
				}
			}

			return $this;
		}
		/**
		 * Ham thuc thi cau truy van.
		 *
		 * @param      <string>  $action  Loai cau lenh can thuc thi
		 * @param      <string>  $table   Ten bang
		 * @param      array   $where   dieu kien sau .
		 *
		 * @return     $this
		 */
		public function action($action, $table, $where = array()) {
			if (count($where) === 3) {	//Allow for no where
				$operators = array('=','>','<','>=','<=','<>');

				$field		= $where[0];
				$operator	= $where[1];
				$value		= $where[2];

				if (in_array($operator, $operators)) {
					$sql = "{$action} FROM {$table} WHERE ${field} {$operator} ?";
					if (!$this->query($sql, array($value))->error()) {
						return $this;
					}
				}
			}
			return false;
		}
		/**
		 * Ham  lay du lieu tu bang
		 *
		 * @param      <string>  $table  Ten bang can thuc  hien
		 * @param      <string>  $where  dien kien 
		 *
		 * @return     action();
		 */
		public function get($table, $where) {
			return $this->action('SELECT *', $table, $where); //ToDo: Allow for specific SELECT (SELECT username)
		}
		/**
		 * Ham xoa du lieu tu bang
		 *
		 * @param      <string>  $table Ten Bang
		 * @param      <string>  $where  dieu kien
		 *
		 * @return     action();
		 */
		public function delete($table, $where) {
			return $this->action('DELETE', $table, $where);
		}
		/**
		 * Ham Them du lieu
		 *
		 * @param      <string>   $table  Ten bang
		 * @param      array    $fields  Gia tri can them vao
		 *
		 * @return     boolean  
		 */
		public function insert($table, $fields = array()) {
			if (count($fields)) {
				$keys 	= array_keys($fields);
				$values = null;
				$x 		= 1;

				foreach ($fields as $field) {
					$values .= '?';
					if ($x<count($fields)) {
						$values .= ', ';
					}
					$x++;
				}

				$sql = "INSERT INTO {$table} (`".implode('`,`', $keys)."`) VALUES({$values})";

				if (!$this->query($sql, $fields)->error()) {
					return true;
				}
			}
			return false;
		}
		 /**
		  * Ham sua du lieu
		  *
		  * @param      <string>   $table  Tên bảng
		  * @param      <type>   $id      Id Khóa của dòng cần sửa dữ liệu
		  * @param      array    $fields  Mảng chứa giá trị cần update
		  *
		  * @return     boolean  
		  */
		public function update($table, $id, $fields = array()) {
			$set 	= '';
			$x		= 1;

			foreach ($fields as $name => $value) {
				$set .= "{$name} = ?";
				if ($x<count($fields)) {
					$set .= ', ';
				}
				$x++;
			}

			$sql = "UPDATE {$table} SET {$set} WHERE id = {$id}";
			
			if (!$this->query($sql, $fields)->error()) {
				return true;
			}
			return false;
		}
		/**
		 * Hàm trả  về resaults
		 *
		 * @return     kết quả
		 */
		public function results() {
			return $this->_results;
		}
		/**
		 * Hàm thực hiện lấy kết qua đầu tiên của dữ liệu
		 *
		 * @return     $this->_results[0];
		 */
		public function first() {
			return $this->_results[0];
		}
		/**
		 * Hàm lấy lấy thông báo lỗi
		 *
		 * @return     <string>  Lỗi.
		 */
		public function error() {
			return $this->_error;
		}
		/**
		 * Hàm đém số bản ghi
		 *
		 * @return     <number>  ( description_of_the_return_value )
		 */
		public function count() {
			return $this->_count;
		}
	}
?>