<?php
/**
 * Lớp session
 * @author Nguyen Van Dai
 * @category classes
 */
	class Session {
		/**
		 * Lớp kiểm tra  nhận session
		 *
		 * @param      <string>  $name   Tên session truyền vào
		 *
		 * @return     boole
		 */
		public static function exists($name) {
			return (isset($_SESSION[$name])) ? true : false;
		}
		/**
		 * Hàm set session
		 *
		 * @param      <string>  $name   Tên session.
		 * @param      <string>  $value  Giá trị của session
		 *
		 * @return     Gán giá trị vào session;
		 */
		public static function put($name, $value) {
			return $_SESSION[$name] = $value;
		}
		/**
		 * Hàm in ra session
		 *
		 * @param      <string>  $name   Tên seession
		 *
		 * @return     trả về giá trị của session
		 */
		public static function get($name) {
			return $_SESSION[$name];
		}
		/**
		 * Hàm xóa session
		 *
		 * @param      <string>  $name   Tên session
		 */
		public static function delete($name) {
			if (self::exists($name)) {
				unset($_SESSION[$name]);
			}
		}
		/**
		 * Hàm thiết lập session cho flash
		 *
		 * @param      <String>  $name    Tên sesion
		 * @param      string  $string  The string
		 *
		 * @return     sesion
		 */
		public static function flash($name, $string = '') {
			if (self::exists($name)) {
				$session = self::get($name);
				self::delete($name);
				return $session;
			} else {
				self::put($name, $string);
			}
		}
	}	
?>