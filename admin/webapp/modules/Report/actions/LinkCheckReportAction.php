<?php
use Mojavi\Action\BasicAction;
use Mojavi\View\View;
use Mojavi\Request\Request;
// +----------------------------------------------------------------------------+
// | This file is part of the Flux package.									  |
// |																			|
// | For the full copyright and license information, please view the LICENSE	|
// | file that was distributed with this source code.						   |
// +----------------------------------------------------------------------------+
class LinkCheckReportAction extends BasicAction
{

	// +-----------------------------------------------------------------------+
	// | METHODS															   |
	// +-----------------------------------------------------------------------+

	/**
	 * Execute any application/business logic for this action.
	 *
	 * @return mixed - A string containing the view name associated with this action
	 */
	public function execute ()
	{
		$links = array();
		$links[] = array('name' => 'Vitapulse Hiveclick', 'url' => 'http://smldtrk.com/?a=16&c=1590&s1=sitecheck', 'error' => '404 - File or directory not found.');
		$links[] = array('name' => 'Vitapulse Hobby6', 'url' => 'http://smldtrk.com/?a=203&c=1590&s1=sitecheck', 'error' => '404 - File or directory not found.');

		$links[] = array('name' => 'Probiotic America Hiveclick', 'url' => 'http://smldtrk.com/?a=16&c=1119&s1=sitecheck', 'error' => '404 - File or directory not found.');
		$links[] = array('name' => 'Probiotic America Hobby6', 'url' => 'http://smldtrk.com/?a=203&c=1119&s1=sitecheck', 'error' => '404 - File or directory not found.');
		$links[] = array('name' => 'Probiotic America Hive01', 'url' => 'http://smldtrk.com/?a=518&c=1119&s1=sitecheck', 'error' => '404 - File or directory not found.');
		$links[] = array('name' => 'Probiotic America Hive02', 'url' => 'http://smldtrk.com/?a=519&c=1119&s1=sitecheck', 'error' => '404 - File or directory not found.');
		$links[] = array('name' => 'Probiotic America Hive03', 'url' => 'http://smldtrk.com/?a=520&c=1119&s1=sitecheck', 'error' => '404 - File or directory not found.');
		$links[] = array('name' => 'Probiotic America Hive04', 'url' => 'http://smldtrk.com/?a=521&c=1119&s1=sitecheck', 'error' => '404 - File or directory not found.');
		$links[] = array('name' => 'Probiotic America Hive05', 'url' => 'http://smldtrk.com/?a=522&c=1119&s1=sitecheck', 'error' => '404 - File or directory not found.');
		$links[] = array('name' => 'Probiotic America Hive06', 'url' => 'http://smldtrk.com/?a=523&c=1119&s1=sitecheck', 'error' => '404 - File or directory not found.');

		foreach ($links as $key => $link) {
			$ch = curl_init($link['url']);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POST, false);

			$retval = curl_exec($ch);
			if (strpos($retval, $link['error']) !== false) {
				$links[$key]['status'] = 'down';
			} else {
				$links[$key]['status'] = 'up';
			}
		}

		$this->getContext()->getRequest()->setAttribute('links', $links);
		
		return View::SUCCESS;
	}
}

?>