<?php
use Mojavi\Action\BasicAction;
use Mojavi\View\View;
use Mojavi\Request\Request;

use Flux\Offer;
use Flux\Lead;
// +----------------------------------------------------------------------------+
// | This file is part of the Flux package.									  |
// |																			|
// | For the full copyright and license information, please view the LICENSE	|
// | file that was distributed with this source code.						   |
// +----------------------------------------------------------------------------+
class LeadExportAction extends BasicAction
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
		set_time_limit(0);
		
		/* @var $lead \Flux\Lead */
		$lead = new \Flux\Lead();
		$lead->populate($_REQUEST);
		$lead->setSort('_id');
		$lead->setSord('DESC');
		if ($lead->getItemsPerPage() == 0) {
			$lead->setIgnorePagination(true);
		}

		$leads = $lead->queryAll(array(), false);
		$leads_found = $leads->count();
				
		$header_names = array('id','created');
		$headers = array();
		foreach ($_REQUEST['headers'] as $header_col) {
			$header = new \Flux\DataField();
			$header->setKeyName($header_col);
			$header->queryByKeyName();
			$headers[] = $header;
			$header_names[] = $header->getName();
		}
		
		echo implode("\t", $header_names) . "\n";
		
		
		
		header("Content-Type: text/plain");
		header("Content-Disposition: attachment; filename=\"lead_export-" . date('Ymdhi') . "\";");
		header("Content-Transfer-Encoding: ascii");
		$counter = 0;
		while ($leads->hasNext()) {
			$counter++;
			$start_time = microtime(true);
			$cursor_item = $leads->next();
			
			$lead = new \Flux\Lead();
			$lead->populate($cursor_item);
			
			$line = array();
			$line[] = $lead->getId();
			$line[] = date('Y-m-d g:i:s a', $lead->getId()->getTimestamp());
			
			/* @var $header \Flux\DataField */
			foreach ($headers as $header) {
				$value = $lead->getValue($header->getKeyName());
				$value = $header->callMappingFunc($value, $lead);
				if (is_array($value)) {
					$line[] = implode(",", $value);
				} else {
					$line[] = $value;
				}
			}
			echo implode("\t", $line) . "\n";
		}
		return View::NONE;
	}
}

?>