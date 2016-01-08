<?php
use Mojavi\Action\BasicAction;
use Mojavi\View\View;
use Mojavi\Request\Request;

use Knp\Snappy\Pdf;
// +----------------------------------------------------------------------------+
// | This file is part of the Flux package.									  |
// |																			|
// | For the full copyright and license information, please view the LICENSE	|
// | file that was distributed with this source code.						   |
// +----------------------------------------------------------------------------+
class CampaignPostInstructionDownloadAction extends BasicAction
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
		/* @var $campaign \Flux\Campaign */
		$campaign = new \Flux\Campaign();
		$campaign->populate($_REQUEST);
		$campaign->query();
		
		require(MO_WEBAPP_DIR . '/vendor/autoload.php');
		
		/* @var $snappy \Knp\Snappy\Pdf */
		$snappy = new \Knp\Snappy\Pdf(MO_WEBAPP_DIR . '/vendor/ioki/wkhtmltopdf-amd64-centos6/bin/wkhtmltopdf-amd64-centos6');
		header('Content-Type: application/pdf');
		header('Content-Disposition: attachment; filename="campaign-post-instructions.pdf"');
		
		$pdf_contents = $snappy->getOutput(str_replace("api", "www", MO_API_URL) . '/campaign/campaign-post-instruction?_id=' . $campaign->getId());		
		echo $pdf_contents;
		
		return View::NONE;
	}
}

?>