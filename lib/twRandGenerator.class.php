<?php
/**
 * twRandGenerator library.
 *
 * @package     twLoremIpsum
 * @subpackage  lib
 * @author      Arkadiusz Tułodziecki
 * @version     SVN: $Id: twRandGenerator.class.php 3272 2010-08-29 14:52:35Z ldath $
 */
class twRandGenerator {
	static $gen_unique_strings = array();


	public static function getUniqueString($length = 0) {
		if ($length > 40) {
			throw new Exception(sprintf('%s: max length = `%d`', __METHOD__, 40));
		}
		$is_limit = false;
		if ($length > 0 && $length < 8) {
			$is_limit = true;
			$pow = 4 * $length;
			$limit = pow(2, $pow) - 1;
		}
		$i = 0;
		do {
			$string = sha1(uniqid(rand(), true));
			if ($length > 0) {
				$string = substr($string, 0, $length);
			}
			$i++;
			if ($is_limit && $i > $limit) {
				throw new Exception(sprintf('%s: with max length `%d` limit `%d` reached', __METHOD__, $length, $limit));
			}
		} while (in_array($string, self::$gen_unique_strings));
		self::$gen_unique_strings[] = $string;
		return $string;
	}
}

?>