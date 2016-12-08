<?php
/**
 * Lớp Token
 *@author Nguyen Van Dai
 * @category classes
 */
 
	class Token {
		/**
		 * Hàm tạo token
		 *
		 * @return     mixed
		 */
		public static function generate() {
			return Session::put(Config::get('session/tokenName'), md5(uniqid()));
		}
		/**
		 * Hàm kiểm tra token
		 *
		 * @param      <string>   $token  giá tri token
		 *
		 * @return     boolean  
		 */
		public static function check($token) {
			$tokenName = Config::get('session/tokenName');

			if (Session::exists($tokenName) && $token === Session::get($tokenName)) {
				Session::delete($tokenName);
				return true;
			} else {
				return false;
			}
		}
	}
?>