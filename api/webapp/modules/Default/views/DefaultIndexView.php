<?php

use Mojavi\View\BasicView;

class DefaultIndexView extends BasicView
{

	// +-----------------------------------------------------------------------+
	// | METHODS															   |
	// +-----------------------------------------------------------------------+

	/**
	 * Execute any presentation logic and set template attributes.
	 *
	 * @return void
	 */
	public function execute ()
	{
		parent::execute();
		// set our template
		$this->setTemplate('IndexSuccess.php');

	}

}
