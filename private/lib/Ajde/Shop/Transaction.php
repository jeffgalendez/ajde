<?php

abstract class Ajde_Shop_Transaction extends Ajde_Model
{
	public static function getProviderList()
	{
		$transactionProviders = Config::get('transactionProviders');
		return $transactionProviders;
	}
}