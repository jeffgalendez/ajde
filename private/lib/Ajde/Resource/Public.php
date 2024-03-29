<?php

class Ajde_Resource_Public extends Ajde_Resource
{
	public function  __construct($type, $filename)
	{
		$url = 'public/' . $type . '/' . $filename;
		$this->setUrl($url);
		parent::__construct($type);
	}

	public function getFilename()
	{
		return $this->getUrl();
	}

	protected function getLinkUrl()
	{
		return $this->getUrl();
	}

}