<?php

class AjdeExtension_Db_Adapter_MySql extends AjdeExtension_Db_Adapter_Abstract
{
	protected $_connection = null;
	
	function __construct($dsn, $user, $password)
	{
		$dsnString = 'mysql:';
		foreach($dsn as $k => $v) {
			$dsnString .= $k . '=' . $v . ';'; 
		}
		parent::__construct(
			$dsnString,
			$user,
			$password, 
		    array(
		    	PDO::MYSQL_ATTR_INIT_COMMAND 	=> "SET NAMES utf8", 	// Modern, please
		    	PDO::ATTR_PERSISTENT 			=> true					// Fast, please
		    )
		);
	}
	
	function getConnection()
	{
		return $this->_connection;
	}
}