<?php

class AjdeExtension_Db extends AjdeExtension
{	
	function __construct()
	{
		$adapterName = 'AjdeExtension_Db_Adapter_' . ucfirst(Config::get('dbAdapter'));
		$dsn = Config::get('dbDsn');
		$user = Config::get('dbUser');
		$password = Config::get('dbPassword');
		$this->_adapter = new $adapterName($dsn, $user, $password);
	}
	
	/**
	 * @return PDO
	 */
	function getConnection()
	{
		return $this->_adapter->getConnection();
	}
}