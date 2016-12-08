<?php
/**
 * Lop config
 * @author Nguyen Van Dai
 * @category classes
 */
	class Config {
		/**
		 * Lay gia tri config theo duong dan
		 *
		 * @param      <string>  $path   lay lan luot gia tri trong mang config
		 *
		 * @return     <string>|false  Tra ve gia tri cua no,neu ko tra ve gia tri false.
		 */
		public static function get($path = null) {
			if ($path) {
				$config = $GLOBALS['config'];
				$path	= explode('/', $path);

				foreach ($path as $bit) {
					if (isset($config[$bit])) {
						$config = $config[$bit];
					}
				}

				return $config;
			}
			
			return false;
		}
	}
?>