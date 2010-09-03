<?php

class loremipsumSentencesgeneratorTask extends sfBaseTask {
	protected function configure() {
		$this->addArguments(array(
			new sfCommandArgument('source', sfCommandArgument::REQUIRED, 'Name of source file'),
			new sfCommandArgument('output', sfCommandArgument::REQUIRED, 'Name of output file'),
		));

		$this->addOptions(array(
			new sfCommandOption('nonumeric', null, sfCommandOption::PARAMETER_OPTIONAL, 'No Numeric', true),
		));

		$this->namespace = 'loremipsum';
		$this->name = 'sentences-generator';
		$this->briefDescription = '';
		$this->detailedDescription = <<<EOF
The [loremipsum:sentences-generator|INFO] task does things.
Call it with:

  [php symfony loremipsum:sentences-generator|INFO]
EOF;
	}

	protected function execute($arguments = array(), $options = array()) {
		$file = sfConfig::get('sf_plugins_dir').'/twLoremIpsumPlugin/data/'.$arguments['source'].'.txt';
		if (!is_readable($file)) {
			throw new sfCommandException(sprintf('File `%s` is not readable', $file));
		}
		$this->logSection('parser', 'Starting work');
		$parser = new twLoremParser($file, $options['nonumeric']);

		$sub_sentence = $parser->getSentences();
		$unique_sentences = array();
		while(!empty($sub_sentence)) {
			$sentence = strtolower(array_shift($sub_sentence));
			$key = md5($sentence);
			$unique_sentences[$key] = $sentence;
		}
		$this->logSection('parser', sprintf('Unique sentences: %d ', count($unique_sentences)));

		$sentences_data = array();
		$sentences_list  = array();
		$sentences_for_len = array();
		$sentences_lenghts = array();
		$i = 0;
		foreach ($unique_sentences as $val) {
			$sentences_list[$i] = $val;
			$len = strlen($val);
			$sentences_for_len[$len][] = $i;
			if (!in_array($len, $sentences_lenghts)) {
				array_push($sentences_lenghts, $len);
			}
			$i++;
		}
		asort($sentences_lenghts);

		$sentences_data['sentences_list'] = $sentences_list;
		$sentences_data['sentences_for_len'] = $sentences_for_len;
		$sentences_data['sentences_lenghts'] = array_values($sentences_lenghts);

		$out = sfConfig::get('sf_plugins_dir').'/twLoremIpsumPlugin/data/'.$arguments['output'].'_sentences_data.json';
		file_put_contents($out, json_encode($sentences_data));
		$this->logSection('parser', sprintf('Sentences file `%s` Generated', $arguments['output'].'_sentences_data.json'));
	}
}
