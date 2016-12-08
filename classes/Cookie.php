<?php
/**
 * Lop cookie xu ly thao tac ve cookie.
 * @author Nguyen Van Dai
 * @category classes
 * 
 */
	class Cookie {
		/**
		 * Ham kiem tra ton tai cua cookie
		 *
		 * @param      <string>  $name   ten cookie can kiem tra
		 *
		 * @return     <bool>   True neu ton tai ,False new khong ton tai
		 */
		public static function exists($name) {
			return (isset($_COOKIE[$name])) ? true : false;
		}
		/**
		 * ham tra ve cookie
		 *
		 * @param      <string>  $name   Ten cookie can lay
		 *
		 * @return     <string> Tra ve gia y=tri cua cookie.
		 */
		public static function get($name) {
			return $_COOKIE[$name];
		}
		/**
		 * Ham tao  mot cookie
		 *
		 * @param      <string>   $name    Ten cua cookie
		 * @param      <string>   $value  	Gia tri cua cookie
		 * @param      integer  $expiry  Thoi gian ton tai cua cookie.
		 *
		 * @return     boolean   
		 */

		public static function put($name, $value, $expiry) {
			if (setcookie($name, $value, time()+$expiry, '/')) {
				return true;
			}
			return false;
		}
		/**
		 * Ham thucj hien xoa cookie.
		 *
		 * @param      <string>  $name   ten cookie can xoa.
		 */
		public static function delete($name) {
			self::put($name, '', time()-1);
		}
	}
?>