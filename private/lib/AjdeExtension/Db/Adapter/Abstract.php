<?php

abstract class AjdeExtension_Db_Adapter_Abstract
{
	/**
	 * @var AjdeExtension_Db_Adapter_Interface 
	 */
	protected $_adapter = null;
	
	public function __construct($dsn, $user, $password, $options)
	{
		try {
			$connection = new PDO($dsn, $user, $password, $options);
		} catch (Exception $e) {
			// TODO:
			throw new Ajde_Exception('Could not connect to database');
		}
		$this->_connection = $connection;
	} 
	
	abstract public function getConnection();
}