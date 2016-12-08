<?php
/**
 * lớp điều hướng
 *  @author Nguyen Van Dai
 * @category classes
 * 
 */
	class Redirect {
		/**
		 * hàm chuyễn trang
		 *
		 * @param      <string>  $location  chuyền đường dẫn.
		 */
		public static function to($location = null) {
			if ($location) {
				if (is_numeric($location)) {
					switch ($location) {
						case '404':
							header('HTTP/1.0 404 Not Found');
							include 'includes/errors/404.php';
							exit();
						break;
					}
				}
				header('Location: '.$location);
				exit();
			}
		}
	}
?>