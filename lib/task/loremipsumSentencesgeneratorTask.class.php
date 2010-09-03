<?php

class loremipsumSentencesgeneratorTask extends sfBaseTask {
	protected function configure() {
		// // add your own arguments here
		// $this->addArguments(array(
		//   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
		// ));

		// // add your own options here
		// $this->addOptions(array(
		//   new sfCommandOption('my_option', null, sfCommandOption::PARAMETER_REQUIRED, 'My option'),
		// ));

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
		// add your code here
	}
}
