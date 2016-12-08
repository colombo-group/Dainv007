<?php
/**
 * Lớp validate
 *  @author Nguyen Van Dai
 * @category classes
 */
	class Validate {
		/**
		 * 
		 *
		 * @var boolean  $_pased      
		 * @var array  $_errors  danh sách lỗi    
		 * @var aray  $_db      
		 */
		private $_passed = false,
				$_errors = array(),
				$_db = null;
		/**
		 * Hàm khởi tạo
		 * 
		 */
		public function __construct() {
			$this->_db = Database::getInstance();
		}
		/**
		 * Hàm kiểm tra dữ liệu vào
		 *
		 * @param      <string>  $source  tên cần kiểm tra
		 * @param      array   $items   danh sách quy tắc càn kiểm tra
		 * @return     $this
		 */
		public function check($source, $items = array()) {
			foreach ($items as $item => $rules) {
				foreach ($rules as $rule => $rule_value) {
					$value 	= trim($source[$item]);
					$item 	= escape($item);
					
					if ($rule === 'required' && empty($value)) {
						$this->addError("{$item} is required");	//ToDo: Pick up 'name' value
					} else if (!empty($value)) {
						switch ($rule) {
							case 'min':
								if (strlen($value) < $rule_value) {
									$this->addError("{item} must be a minimum of {$rule_value} characters");
								}
								break;
							case 'max':
								if (strlen($value) > $rule_value) {
									$this->addError("{item} must be no longer than {$rule_value} characters");
								}
								break;
							case 'matches':
								if ($value != $source[$rule_value]) {
									$this->addError("{$rule_value} must match {$item}");
								}
								break;
							case 'unique':
								$check = $this->_db->get($rule_value,array($item, '=' , $value));
								if ($check->count()) {
									$this->addError("{$item} already exists");
								}
								break;
						}
					}
				}
			}

			if (empty($this->_errors)) {
				$this->_passed = true;
			}

			return $this;
		}
		/**
		 * Hàm thêm lỗi vào danh sách _errors
		 *
		 * @param      <string>  $error  lỗi
		 */
		private function addError($error) {
			$this->_errors[] = $error;
		}
		/**
		 * hàm get lỗi
		 *
		 * @return     mixed
		 */
		public function errors() {
			return $this->_errors;
		}
		/**
		 * 
		 *
		 * @return    bool
		 */
		public function passed() {
			return $this->_passed;
		}
	}
?>