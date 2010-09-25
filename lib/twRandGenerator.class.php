<?php
/**
 * twRandGenerator library.
 *
 * @package     twLoremIpsum
 * @subpackage  lib
 * @author      Arkadiusz Tułodziecki
 */
class twRandGenerator {
	static protected $gen_unique_strings = array();

	static public function getUniqueString($length = 0, $namespace = 'default') {
		if ($length > 40) {
			throw new Exception(sprintf('%s: max length = `%d`', __METHOD__, 40));
		}
		$is_limit = false;
		if ($length > 0 && $length < 8) {
			$is_limit = true;
			$pow = 4 * $length;
			$limit = pow(2, $pow) - 1;
		}
		if (!isset(self::$gen_unique_strings[$namespace])) {
			self::$gen_unique_strings[$namespace] = array();
		}
		do {
			$string = sha1(uniqid(rand(), true));
			if ($length > 0) {
				$string = substr($string, 0, $length);
			}
			if ($is_limit && count(self::$gen_unique_strings[$namespace]) >= $limit) {
				throw new Exception(sprintf('%s: with max length `%d` limit `%d` reached', __METHOD__, $length, $limit));
			}
		} while (in_array($string, self::$gen_unique_strings[$namespace]));
		self::$gen_unique_strings[$namespace][] = $string;
		return $string;
	}

	static public function getRandomLimit($range) {
		if ($range[0] > $range[1] && $range[0] > 0) {
			return mt_rand($range[0], $range[1]);
		} else {
			return $range[1];
		}
	}

}
