<?php

class Ajde_Document_Format_Js extends Ajde_Document
{
	protected $_cacheControl = 'public';
	protected $_contentType = 'text/javascript';

	public function render()
	{
		Ajde::app()->getDocument()->setLayout(new Ajde_Layout('empty'));
		Ajde::app()->getResponse()->addHeader('Content-Type', $this->_contentType);
		Ajde::app()->getResponse()->addHeader('Cache-Control', $this->_cacheControl);
		$this->removeSetCookieHeader();
		return parent::render();
	}
}