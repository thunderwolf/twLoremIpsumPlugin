<?php

class loremipsumWordsgeneratorTask extends sfBaseTask {
	protected function configure() {
		$this->addArguments(array(
			new sfCommandArgument('source', sfCommandArgument::REQUIRED, 'Name of source file'),
			new sfCommandArgument('output', sfCommandArgument::REQUIRED, 'Name of output file'),
		));

		$this->addOptions(array(
			new sfCommandOption('nonumeric', null, sfCommandOption::PARAMETER_OPTIONAL, 'No Numeric', true),
			new sfCommandOption('regex', null, sfCommandOption::PARAMETER_OPTIONAL, 'Regex', '/[A-Z]/ui'),
		));

		$this->namespace = 'loremipsum';
		$this->name = 'words-generator';
		$this->briefDescription = '';
		$this->detailedDescription = <<<EOF
The [loremipsum:wors-generator|INFO] task does things.
Call it with:

  [php symfony loremipsum:wors-generator|INFO]
EOF;
	}

	protected function execute($arguments = array(), $options = array()) {
		$file = sfConfig::get('sf_plugins_dir').'/twLoremIpsumPlugin/data/'.$arguments['source'].'.txt';
		if (!is_readable($file)) {
			throw new sfCommandException(sprintf('File `%s` is not readable', $file));
		}
		$this->logSection('parser', 'Starting work');
		$parser = new twLoremParser($file, $options['nonumeric']);

		$words = $parser->getWords();
		$unique_words = array();
		while(!empty($words)) {
			$word = strtolower(array_shift($words));
			if (!in_array($word, $unique_words) && preg_match($options['regex'], $word)) {
				array_push($unique_words, $word);
			}
			if ((count($word) % 100) == 0) {
				echo '+';
			}
		}
		echo "\n";
		$this->logSection('parser', sprintf('Unique words: %d ', count($unique_words)));

		$words_data = array();
		$words_list = array();
		$words_for_len = array();
		$words_lenghts = array();
		$i = 0;
		foreach ($unique_words as $val) {
			$words_list[$i] = $val;
			$len = strlen($val);
			$words_for_len[$len][] = $i;
			if (!in_array($len, $words_lenghts)) {
				array_push($words_lenghts, $len);
			}
			$i++;
		}
		asort($words_lenghts);

		$words_data['words_list'] = $words_list;
		$words_data['words_for_len'] = $words_for_len;
		$words_data['words_lenghts'] = array_values($words_lenghts);

		$out = sfConfig::get('sf_plugins_dir').'/twLoremIpsumPlugin/data/'.$arguments['output'].'_words_data.json';
		file_put_contents($out, json_encode($words_data));
		$this->logSection('parser', sprintf('Sentences file `%s` Generated', $arguments['output'].'_words_data.json'));
	}
}
