<?php
use Mojavi\Action\BasicRestAction;
// +----------------------------------------------------------------------------+
// | This file is part of the Flux package.									  |
// |																			|
// | For the full copyright and license information, please view the LICENSE	|
// | file that was distributed with this source code.						   |
// +----------------------------------------------------------------------------+
class BuildPostUrlAction extends BasicRestAction
{

	// +-----------------------------------------------------------------------+
	// | METHODS															   |
	// +-----------------------------------------------------------------------+

	/**
	 * Execute any application/business logic for this action.
	 * @return mixed - A string containing the view name associated with this action
	 */
	public function execute ()
	{
		return parent::execute();
	}

	/**
	 * Returns the input form to use for this rest action
	 * @return \Flux\Client
	 */
	function getInputForm() {
		return new \Flux\Fulfillment();
	}

	/**
	 * Handles the GET
	 */
	function executeGet($input_form) {
		$ajax_form = new \Mojavi\Form\AjaxForm();

		$params = array();
		$url = substr($input_form->getPostUrl(), 0, strpos($input_form->getPostUrl(), '?'));
		$qs = substr($input_form->getPostUrl(), strpos($input_form->getPostUrl(), '?') + 1);
		parse_str($qs, $params);

		/* @var $data_field \Flux\DataField */
		$data_field = new \Flux\DataField();
		$data_field->setIgnorePagination(true);
		$data_fields = $data_field->queryAll();
		foreach ($params as $key => $param) {
			foreach ($data_fields as $data_field) {
				if (in_array(trim($key), $data_field->getRequestName()) && trim($param) == '') {
					$params[$key] = '#' . strtoupper($data_field->getKeyName()) . '#';
				}
			}
		}

		$qs = urldecode(http_build_query($params, null, '&'));
		$input_form->setPostUrl($url . '?' . $qs);
		$ajax_form->setRecord($input_form);
		return $ajax_form;
	}
}

?>