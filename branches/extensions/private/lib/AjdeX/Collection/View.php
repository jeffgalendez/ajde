<?php

class AjdeX_Collection_View extends Ajde_Object_Standard
{
	private $_tableName;
	
	private $_rowCount;
	
	public function __construct($tableName, $listOptions = array()) {
		$this->_tableName = $tableName;
		
		$defaultOptions = array(
			'page'			=> 1,
			'pageSize'		=> 10,
			'filter'		=> array(),
			'search'		=> null,
			'orderBy'		=> null,
			'orderDir'		=> AjdeX_Query::ORDER_ASC
		);		
		$options = array_merge($defaultOptions, $listOptions);
		$this->setOptions($options);	
	}
	
	public function setOptions($options)
	{
		foreach($options as $key => $value) {
			$this->set($key, $value);
		}	
	}
	
	public function getPage()				{ return parent::getPage(); }
	public function getPageSize()			{ return parent::getPageSize(); }
	public function getFilter()				{ return parent::getFilter(); }
	public function getSearch()				{ return parent::getSearch(); }
	public function getOrderBy()			{ return parent::getOrderBy(); }
	public function getOrderDir()			{ return parent::getOrderDir(); }
	
	public function getRowCount(AjdeX_Collection $collection = null)
	{
		if (isset($collection)) {
			return $collection->count(true);
		}
		if (!isset($this->_rowCount)) {
			$sql = 'SELECT COUNT(*) AS count FROM ' . $this->_tableName;		
			$connection = AjdeX_Db::getInstance()->getConnection();	
			$statement = $connection->prepare($sql);
			$statement->execute();
			$result = $statement->fetch(PDO::FETCH_ASSOC);
			$this->_rowCount = $result['count'];
		}	
		return $this->_rowCount;
	}
	
	public function getPageCount(AjdeX_Collection $collection = null)
	{
		return ceil($this->getRowCount($collection) / $this->getPageSize());
	}
	
	public function getRowStart()
	{
		return ($this->getPage() - 1) * $this->getPageSize();
	}
	
}