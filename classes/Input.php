<?php
/**
 * lớp input
 *  @author Nguyen Van Dai
 *  @category classes
 */
	class Input {
		/**
		 * Lơp lấy post get
		 *
		 * @param      string   $type   truyền post hoach get
		 *
		 * @return     boolean 
		 */
		public static function exists($type = 'post') {
			switch ($type) {
				case 'post':
					return (!empty($_POST)) ? true : false;
					break;
				case 'get':
					return (!empty($_GET)) ? true : false;
					break;
				default:
					return false;
					break;
			}
		}
		/**
		 * Lớp kiểm tra có time số truyền vào post ,get ko?
		 *
		 * @param      <string>  $item   tham số truyên vào
		 *
		 * @return     Post trả vè post[$item]|có GET[$item] thì trả về.
		 */

		public static function get($item) {
			if (isset($_POST[$item])) {
				return $_POST[$item];
			} else if (isset($_GET[$item])) {
				return $_GET[$item];
			}
			return '';
		}
	}
?>