<?php

/**
 * twLoremParser library.
 *
 * @package     twLoremParser
 * @subpackage  lib
 * @author      Arkadiusz Tułodziecki
 */
class twLoremParser {

	protected $nonumeric = false;
	protected $data = array();
	protected $sentences = array();
	protected $words = array();

	public function __construct($file, $nonumeric = false) {
		$data = file_get_contents($file);
		$data = str_replace("\n", ' ', $data);
		$data = explode(' ', $data);

		foreach($data as $key => $val) {
			$data[$key] = trim($val);
			if (empty($val)) {
				unset($data[$key]);
				continue;
			}
			if ($nonumeric == true) {
				if (is_numeric($val)) {
					unset($data[$key]);
					continue;
				}
				$data[$key] = preg_replace('/[0-9]/', '', $val);
			}
		}
		$this->data = & $data;
		$this->nonumeric = $nonumeric;
	}

	public function getSentences() {
		$pre_sentence = '';
		while(!empty($this->data)) {
			$pre = array_shift($this->data);
			$pre_sentence .= ' '.trim($pre);
			if (in_array(mb_substr($pre, -1), array('.', ',', '?', '!', ':', ';'))) {
				$this->sentences[] = trim(mb_substr($pre_sentence, 0, -1));
				$pre_sentence = '';
			}
			if ((count($this->data) % 100) == 0) {
				echo '.';
			}
		}
		echo "\n";
		return $this->sentences;
	}

	public function getWords() {
		while(!empty($this->data)) {
			$pre = array_shift($this->data);
			if (in_array(mb_substr($pre, -1), array('.', ',', '?', '!', ':', ';'))) {
				$this->words[] = trim(mb_substr($pre, 0, -1));
			} else {
				$this->words[] = trim($pre);
			}
			if ((count($this->data) % 100) == 0) {
				echo '.';
			}
		}
		echo "\n";
		return $this->words;
	}
}

?>