<?php
/**
 * Lớp user
 * 
 * @author Nguyen Van Dai
 * @category classes
 */
 
	class User {
		/**
		 * @var  string db 
		 * @var   string data
		 * @var   string _sessionName
		 * @var   string _cookieName
		 * @var   string _isLoggedIn
		 */
		private $_db,
				$_data,
				$_sessionName,
				$_cookieName,
				$_isLoggedIn;
				/**
				 * Hàm khởi tạo
				 *
				 * @param      <string>  $user|null   Ten user muốn xử lý.
				 */
		public function __construct($user = null) {
			$this->_db 			= Database::getInstance();
			$this->_sessionName = Config::get('session/sessionName');
			$this->_cookieName 	= Config::get('remember/cookieName');

			if (!$user) {
				if (Session::exists($this->_sessionName)) {
					$user = Session::get($this->_sessionName);

					if ($this->find($user)) {
						$this->_isLoggedIn = true;
					} else {
						self::logout();
					}
				}
			} else {
				$this->find($user);
			}
		}
		/**
		 * Hàm cập nhật user
		 *
		 * @param      array      $fields  Mảng thông tin user
		 * @param      <int>     $id      id user
		 *
		 * @throws     Exception  (description)
		 */
		public function update($fields = array(), $id = null) {

			if (!$id && $this->isLoggedIn()) {
				$id = $this->data()->ID;
			}

			if (!$this->_db->update('users', $id, $fields)) {
				throw new Exception("There was a problem updating your details");
			}
		}
		/**
		 * hàm thêm mới user
		 *
		 * @param      array      $fields  Thông tin user
		 *
		 * @throws     Exception  (description)
		 */
		public function create($fields = array()) {
			if (!$this->_db->insert('users', $fields)) {
				throw new Exception("There was a problem creating your account");
			}
		}
		/**
		 * hàm tìm user
		 *
		 * @param      <string>   $user   user cần tìm kiếm hoặc username
		 *
		 * @return     boolean  ( description_of_the_return_value )
		 */
		public function find($user = null) {
			if ($user) {
				$fields = (is_numeric($user)) ? 'id' : 'username';	//Numbers in username issues
				$data 	= $this->_db->get('users', array($fields, '=', $user));

				if ($data->count()) {
					$this->_data = $data->first();
					return true;
				}
			}
			return false;
		}
		/**
		 * hàm login
		 *
		 * @param      <string>   $username  tên đăng nhập
		 * @param      <string>   $password  Mật khẩu
		 * @param      boolean  $remember  kiểm tra có click ghi nhớ pass
		 *
		 * @return     boolean  ( description_of_the_return_value )
		 */
		public function login($username = null, $password = null, $remember = false) {
			if (!$username && !$password && $this->exists()) {
				Session::put($this->_sessionName, $this->data()->ID);
			} else {
				$user = $this->find($username);
				if ($user) {
					if ($this->data()->password === Hash::make($password,$this->data()->salt)) {
						Session::put($this->_sessionName, $this->data()->ID);

						if ($remember) {
							$hash = Hash::unique();
							$hashCheck = $this->_db->get('usersSessions', array('userID','=',$this->data()->ID));

							if (!$hashCheck->count()) {
								$this->_db->insert('usersSessions', array(
									'userID' 	=> $this->data()->ID,
									'hash' 		=> $hash
								));
							} else {
								$hash = $hashCheck->first()->hash;
							}
							Cookie::put($this->_cookieName, $hash, Config::get('remember/cookieExpiry'));
						}

						return true;
					}
				}
			}
			return false;
		}
		/**
		 * hàm thực hiên quyền của user
		 *
		 * @param      <string>   $key    mã quyền người dùng
		 *
		 * @return     boolean  True có cho phép, False không cho phép.
		 */
		public function hasPermission($key) {
			$group = $this->_db->get('groups', array('ID', '=', $this->data()->userGroup));
			if ($group->count()) {
				$permissions = json_decode($group->first()->permissions,true);

				if ($permissions[$key] == true) {
					return true;
				}
			}
			return false;
		}
		/**
		 * Hàm kiểm tra người dùng có tồn tại trong CSDL ko
		 *
		 * @return     
		 */
		public function exists() {
			return (!empty($this->_data)) ? true : false;
		}
		/**
		 * Hàm xử lý đăng xuất
		 * xóa cookie, session
		 */
		public function logout() {
			$this->_db->delete('usersSessions', array('userID', '=', $this->data()->ID));
			Session::delete($this->_sessionName);
			Cookie::delete($this->_cookieName);
		}
		/**
		 * Hàm lấy csdl
		 *
		 * @return     this->_data
		 */
		public function data() {
			return $this->_data;
		}
		/**
		 * Hàm kiểm tra đăng nhập
		 *
		 * @return     boolean  True đã đâng nhập, False chưa.
		 */
		public function isLoggedIn() {
			return $this->_isLoggedIn;
		}
	}
?>