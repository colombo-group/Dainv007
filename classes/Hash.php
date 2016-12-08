<?php
/**
 * Lớp hash
 *  @author Nguyen Van Dai
 * @category classes
 */
	class Hash {
		/**
		 * Lớp băm
		 *
		 * @param      <string>  $string  Chuỗi cần băm
		 * @param      string  $salt    The salt
		 *
		 * @return     
		 */
		public static function make($string, $salt = '') {
			return hash('sha256', $string.$salt);
		}
		/**
		 * Hàm tạo chuỗ cần băm
		 *
		 * @param      <int>  $length  dộ dài cần băm
		 *
		 * @return     string
		 */

		public static function salt($length) {
			return mcrypt_create_iv($length);
		}
		/**
		 * hàm
		 *
		 * @return     <string>  
		 */
		public static function unique() {
			return self::make(uniqid());
		}
	}
?>