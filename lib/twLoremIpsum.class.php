<?php

/**
 * twLoremIpsum library.
 *
 * @package     twLoremIpsum
 * @subpackage  lib
 * @author      Arkadiusz Tułodziecki
 * @version     SVN: $Id: twLoremIpsum.class.php 3272 2010-08-29 14:52:35Z ldath $
 */
class twLoremIpsum {

	/**
	 * Unique Strings generator
	 *
	 * @param int $num  Number of strings to generate
	 * @param int $max_len  Maximum string lengtht
	 * @param int $min_len  Minimum string lengtht
	 * @return string
	 */
	public function generateUniqueStrings($num, $max_len = 0, $min_len = 0) {
		$out = array();
		$length = $max_len;

		$change_lenght = false;
		if (!empty($min_len) && !empty($max_len)) {
			if ($min_len < $max_len) {
				$change_lenght = true;
			}
		}

		if ($change_lenght === true) {
			for($i=0;$i<$num;$i++) {
				$out[] = twRandGenerator::getUniqueString(rand($min_len, $max_len));
			}
		} else {
			for($i=0;$i<$num;$i++) {
				$out[] = twRandGenerator::getUniqueString($length);
			}
		}
		return $out;
	}

	/**
	 * Paragraphs generator
	 *
	 * @param int $num  Number of paragraps to generate
	 * @return string
	 */
	public function generateParagraphs($num) {
		$sentences_data = json_decode(file_get_contents(dirname ( __FILE__ ) . '/../data/sentences_data.json'));

		$out = '';
		for($i=0;$i<$num;$i++) {
			$out[] = $this->generateRandomParagraph($sentences_data);
		}

		return $out;
	}

	protected function generateRandomParagraph($sentences_data) {
		$max_sentences = rand(6,10);
		$out = '';
		for($i=0;$i<$max_sentences;$i++) {
			$out .= ucfirst($this->generateRandomSentence($sentences_data)).'. ';
		}
		return trim($out);
	}

	protected function generateRandomSentence($sentences_data) {
		$rand_keys = array_rand($sentences_data->sentences_list, rand(1, 3));
		if (!is_array($rand_keys)) {
			return $sentences_data->sentences_list[$rand_keys];
		}
		$rand_data = array();
		foreach ($rand_keys as $key) {
			$rand_data[] = $sentences_data->sentences_list[$key];
		}

		return implode(', ',$rand_data);
	}

	/**
	 * Words generator
	 *
	 * @param int $num  Number of words to generate
	 * @return string
	 */
	public function generateWords($num) {
		;
	}

	/**
	 * Bytes generator
	 *
	 * @param int $num  Number of bytes to generate
	 * @return string
	 */
	public function generateBytes($num) {
		;
	}

	/**
	 * Lists generator
	 *
	 * @param int $num  Number of lists to generate
	 * @return string
	 */
	public function generateLists($num) {
		;
	}
}
?>