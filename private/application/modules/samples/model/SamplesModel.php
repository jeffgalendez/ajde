<?php

class SamplesModel extends Ajde_Model
{
	public function getVATPercentage()
	{
		return Config::get('defaultVAT');
	}
}
