<?php
// +----------------------------------------------------------------------------+
// | This file is part of the Flux package.									  |
// |																			|
// | For the full copyright and license information, please view the LICENSE	|
// | file that was distributed with this source code.						   |
// +----------------------------------------------------------------------------+
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

		// set the title
		$this->setTitle('Flux');

		$this->setDecoratorTemplate(MO_TEMPLATE_DIR . "/index.shell.php");

	}
	
	/**
	 * Returns the menu
	 * @return Zend\Navigation
	 */
	function getMenu() {
		/* @var $saved_search \Flux\SavedSearch */
		$saved_search = new \Flux\SavedSearch();
		$saved_search->setSort('name');
		$saved_search->setSord('ASC');
		$saved_search->setUser($this->getContext()->getUser()->getUserDetails()->getId());
		$saved_search->setSearchType(\Flux\SavedSearch::SAVED_SEARCH_TYPE_LEAD);
		$saved_search->setIgnorePagination(true);
		$lead_saved_searches = $saved_search->queryAll();
		
		/* @var $saved_search \Flux\SavedSearch */
		$saved_search = new \Flux\SavedSearch();
		$saved_search->setSort('name');
		$saved_search->setSord('ASC');
		$saved_search->setUser($this->getContext()->getUser()->getUserDetails()->getId());
		$saved_search->setSearchType(\Flux\SavedSearch::SAVED_SEARCH_TYPE_CAMPAIGN);
		$saved_search->setIgnorePagination(true);
		$campaign_saved_searches = $saved_search->queryAll();
		
		/* @var $saved_search \Flux\SavedSearch */
		$saved_search = new \Flux\SavedSearch();
		$saved_search->setSort('name');
		$saved_search->setSord('ASC');
		$saved_search->setUser($this->getContext()->getUser()->getUserDetails()->getId());
		$saved_search->setSearchType(\Flux\SavedSearch::SAVED_SEARCH_TYPE_OFFER);
		$saved_search->setIgnorePagination(true);
		$offer_saved_searches = $saved_search->queryAll();
		
		$navigation_config = MO_WEBAPP_DIR . '/config/navigation.xml';
		
		if (file_exists($navigation_config)) {
			$navigation_contents = file_get_contents($navigation_config);
			
			// Add the lead saved searches
			$saved_search_array = array();
			foreach ($lead_saved_searches as $lead_saved_search) {
				$new_page = '<saved_search_' . $lead_saved_search->getId() . '>' . 
												  '<label><![CDATA[' . $lead_saved_search->getName() . ']]></label>' . 
												  '<uri><![CDATA[/lead/lead-search?' . urldecode(http_build_query($lead_saved_search->getQueryString(), '&')) . ']]></uri>' . 
												  '<class>nav-secondary</class>' . 
											  '</saved_search_' . $lead_saved_search->getId() . '>';
				$saved_search_array[] = $new_page;
			}
			if (count($saved_search_array) > 0) {
				$saved_search_array[] = '<saved_search_lead_spacer><label></label><module></module><controller></controller><class>nav-spacer</class></saved_search_lead_spacer>';
				$navigation_contents = str_replace('<!-- <saved_searches_lead /> -->', implode("\n", $saved_search_array), $navigation_contents);
			}
			
			// Add the campaign saved searches
			$saved_search_array = array();
			foreach ($campaign_saved_searches as $saved_search) {
				$new_page = '<saved_search_' . $saved_search->getId() . '>' .
					'<label>' . $saved_search->getName() . '</label>' .
					'<uri>/campaign/campaign-search?' . urldecode(http_build_query($saved_search->getQueryString(), '&')) . '</uri>' .
					'<class>nav-secondary</class>' .
					'</saved_search_' . $saved_search->getId() . '>';
				$saved_search_array[] = $new_page;
			}
			if (count($saved_search_array) > 0) {
				$saved_search_array[] = '<saved_search_campaign_spacer><label></label><module></module><controller></controller><class>nav-spacer</class></saved_search_campaign_spacer>';
				$navigation_contents = str_replace('<!-- <saved_searches_campaign /> -->', implode("\n", $saved_search_array), $navigation_contents);
			}
			
			// Add the offer saved searches
			$saved_search_array = array();
			foreach ($offer_saved_searches as $saved_search) {
				$new_page = '<saved_search_' . $saved_search->getId() . '>' .
					'<label>' . $saved_search->getName() . '</label>' .
					'<uri>/offer/offer-search?' . urldecode(http_build_query($saved_search->getQueryString(), '&')) . '</uri>' .
					'<class>nav-secondary</class>' .
					'</saved_search_' . $saved_search->getId() . '>';
				$saved_search_array[] = $new_page;
			}
			if (count($saved_search_array) > 0) {
				$saved_search_array[] = '<saved_search_offer_spacer><label></label><module></module><controller></controller><class>nav-spacer</class></saved_search_offer_spacer>';
				$navigation_contents = str_replace('<!-- <saved_searches_offer /> -->', implode("\n", $saved_search_array), $navigation_contents);
			}
			
			
			
			// Load the modified menu
			$reader = new \Zend\Config\Reader\Xml();
			$data   = $reader->fromString($navigation_contents);
			$zend_navigation = new \Zend\Navigation\Navigation($data);
			
			return $zend_navigation;
		}
		return null;
	}

}