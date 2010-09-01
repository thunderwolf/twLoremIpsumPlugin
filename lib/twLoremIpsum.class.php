<?php

/**
 * twLoremIpsum library.
 *
 * @package     twLoremIpsum
 * @subpackage  lib
 * @author      Arkadiusz Tułodziecki
 * @version     SVN: $Id: twLoremIpsum.class.php 3287 2010-09-01 07:39:44Z ldath $
 */
class twLoremIpsum {
	static protected $gen_unique_words = array();

	protected $sentences_data = null;
	protected $words_data = null;

	protected $sentences_data_file = 'sentences_data.json';
	protected $words_data_file = 'words_data.json';

	/**
	 * Optional source file with sentences data
	 *
	 * @param string $file  Name of file in plugin data folder or full path to file
	 */
	public function setSentencesSourceFile($file) {
		$this->sentences_data_file = $file;
	}

	/**
	 * Optional source file with words data
	 *
	 * @param string $file  Name of file in plugin data folder or full path to file
	 */
	public function setWordsSourceFile($file) {
		$this->words_data_file = $file;
	}

	/**
	 * Unique String generator
	 *
	 * @param mixed $length  Length of string if int or range of length to randomly select if array($min, $max)
	 * @param string $namespace  Optional namespace to separate unique generator lists
	 * @return string
	 */
	public function generateUniqueString($length = 0, $namespace = 'default') {
		if (is_array($length)) {
			$length = twRandGenerator::getRandomLimit($length);
		}
		return twRandGenerator::getUniqueString($length, $namespace);
	}

	/**
	 * Unique Strings generator
	 *
	 * @param int $num  Number of strings to generate
	 * @param mixed $length  Length of string if int or range of length to randomly select if array($min, $max)
	 * @param string $namespace  Optional namespace to separate unique generator lists
	 * @return array
	 */
	public function generateUniqueStrings($num, $length = 0, $namespace = 'default') {
		$out = array();

		$change_lenght = false;
		if (is_array($length)) {
			$change_lenght = true;
		}

		if ($change_lenght === true) {
			for($i=0;$i<$num;$i++) {
				$out[] = twRandGenerator::getUniqueString(twRandGenerator::getRandomLimit($length), $namespace);
			}
		} else {
			for($i=0;$i<$num;$i++) {
				$out[] = twRandGenerator::getUniqueString($length, $namespace);
			}
		}
		return $out;
	}

	/**
	 * Paragraph generator
	 *
	 * @param mixed $length  Number of sentences in paragraph if int or number of sentences to randomly select if array($min, $max)
	 * @return string
	 */
	public function generateParagraph($length = 10) {
		return $this->generateRandomParagraph($length);
	}

	/**
	 * Paragraphs generator
	 *
	 * @param int $num  Number of paragraps to generate
	 * @param mixed $length  Number of sentences in paragraph if int or number of sentences to randomly select if array($min, $max)
	 * @return array
	 */
	public function generateParagraphs($num, $length = 10) {
		$out = array();
		for($i=0;$i<$num;$i++) {
			$out[] = $this->generateRandomParagraph($length);
		}
		return $out;
	}

	/**
	 * Sentence generator
	 *
	 * @param mixed $length  Number of sub sentences in sentence if int or number of sub sentences in sentence to randomly select if array($min, $max)
	 * @return string
	 */
	public function generateSentence($length = array(1, 3)) {
		return $this->generateRandomSentence($length);
	}

	/**
	 * Sentences generator
	 *
	 * @param int $num  Number of sentenes to generate
	 * @param mixed $length  Number of sub sentences in sentence if int or number of sub sentences in sentence to randomly select if array($min, $max)
	 * @return array
	 */
	public function generateSentences($num, $length = array(1, 3)) {
		$out = array();
		for($i=0;$i<$num;$i++) {
			$out[] = $this->generateRandomSentence($length);
		}
		return $out;
	}

	/**
	 * Unique word generator
	 *
	 * @param mixed $length  Minimum number of letters in word if int or number of letters in word to randomly select if array($min, $max)
	 * @param string $namespace  Optional namespace to separate unique generator lists
	 * @return string
	 */
	public function generateUniqueWord($length = 10, $namespace = 'default') {
		return $this->getRandomWord($length, true, $namespace);
	}

	/**
	 * Unique words generator
	 *
	 * @param int $num  Number of words to generate
	 * @param mixed $length  Minimum number of letters in word if int or number of letters in word to randomly select if array($min, $max)
	 * @param string $namespace  Optional namespace to separate unique generator lists
	 * @return array
	 */
	public function generateUniqueWords($num, $length = 10, $namespace = 'default') {
		$out = array();
		for($i=0;$i<$num;$i++) {
			$out[] = $this->getRandomWord($length, true, $namespace);
		}
		return $out;
	}

	/**
	 * Word generator
	 *
	 * @param mixed $length  Minimum number of letters in word if int or number of letters in word to randomly select if array($min, $max)
	 * @return string
	 */
	public function generateWord($length = 10) {
		return $this->getRandomWord($length);
	}

	/**
	 * Words generator
	 *
	 * @param int $num  Number of words to generate
	 * @param mixed $length  Minimum number of letters in word if int or number of letters in word to randomly select if array($min, $max)
	 * @return array
	 */
	public function generateWords($num, $length = 10) {
		$out = array();
		for($i=0;$i<$num;$i++) {
			$out[] = $this->getRandomWord($length);
		}
		return $out;
	}

	protected function generateRandomParagraph($limit) {
		if (is_array($limit)) {
			$limit = twRandGenerator::getRandomLimit($limit);
		}
		$out = '';
		for($i=0;$i<$limit;$i++) {
			$out .= ucfirst($this->generateRandomSentence(array(1, 3))).'. ';
		}
		return trim($out);
	}

	protected function generateRandomSentence($limit) {
		$sentences_data = $this->getSentences();
		if (is_array($limit)) {
			$limit = twRandGenerator::getRandomLimit($limit);
		}
		$rand_keys = array_rand($sentences_data['sentences_list'], $limit);
		if (!is_array($rand_keys)) {
			return $sentences_data['sentences_list'][$rand_keys];
		}
		$rand_data = array();
		foreach ($rand_keys as $key) {
			$rand_data[] = $sentences_data['sentences_list'][$key];
		}

		return implode(', ',$rand_data);
	}

	protected function getRandomWord($length, $unique = false, $namespace = null) {
		$words_data = $this->getWords();
		if ($length == 0) {
			return $this->getRandomWordFromArray($words_data['words_list'], $unique, $namespace);
		}
		if (is_array($length)) {
			$min = $length[0];
			$max = $length[1];
		}
		$sum_array = array();
		for($i=$min;$i<=$max;$i++) {
			if (isset($words_data['words_for_len'][$i])) {
				$sum_array = array_merge($sum_array, array_values($words_data['words_for_len'][$i]));
			}
		}
		return $this->getRandomWordFromArray(array_flip($sum_array), $unique, $namespace);;
	}

	protected function getRandomWordFromArray($data, $unique, $namespace) {
		$words_data = $this->getWords();
		if ($unique !== true) {
			$rand_key = array_rand($data, 1);
			return $words_data['words_list'][$rand_key];
		} else {
			if (!isset(self::$gen_unique_words[$namespace])) {
				self::$gen_unique_words[$namespace] = array();
			}
			do {
				$rand_key = array_rand($data, 1);
				if (count(self::$gen_unique_words[$namespace]) >= count($data)) {
					throw new Exception(sprintf('%s: unique words limit `%d` reached', __METHOD__, count($data)));
				}
			} while (in_array($rand_key, self::$gen_unique_words[$namespace]));
			self::$gen_unique_words[$namespace][] = $rand_key;
			return $words_data['words_list'][$rand_key];
		}
	}

	protected function getSentences() {
		if (is_null($this->sentences_data)) {
			$this->loadSentences();
		}
		return $this->sentences_data;
	}

	protected function loadSentences() {
		if (is_readable($this->sentences_data_file)) {
			$source = $this->sentences_data_file;
		} else {
			$source = dirname(__FILE__) . '/../data/' . $this->sentences_data_file;
		}

		if (!is_readable($source)) {
			throw new Exception(sprintf('%s: Source file %s is not readable!', __METHOD__, $source));
		}

		$this->sentences_data = json_decode(file_get_contents($source), true);
	}

	protected function getWords() {
		if (is_null($this->words_data)) {
			$this->loadWords();
		}
		return $this->words_data;
	}

	protected function loadWords() {
		if (is_readable($this->words_data_file)) {
			$source = $this->words_data_file;
		} else {
			$source = dirname(__FILE__) . '/../data/' . $this->words_data_file;
		}

		if (!is_readable($source)) {
			throw new Exception(sprintf('%s: Source file %s is not readable!', __METHOD__, $source));
		}

		$this->words_data = json_decode(file_get_contents($source), true);
	}
}
