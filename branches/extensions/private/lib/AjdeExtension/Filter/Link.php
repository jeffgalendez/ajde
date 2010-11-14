<?php

class AjdeExtension_Filter_Link extends AjdeExtension_Filter
{
	protected $_collection;	
	protected $_link;
	protected $_meta;
	protected $_value;
	
	public function __construct($collection, $link, $meta, $value)
	{
		$this->_collection = $collection;
		$this->_link = $link;
		$this->_meta = $meta;
		$this->_value = $value; 
	}
	
	public function prepare()
	{
		$sql  = $this->_meta['table'] . ' ON '; 
		$sql .= (string) $this->_collection->getTable() . '.' . $this->_collection->getTable()->getPK();
		$sql .= ' = ';
		$sql .= $this->_meta['table'] . '.' . $this->_meta['fields'][(string) $this->_collection->getTable()];
		$sql .= ' AND ';
		$sql .= $this->_meta['table'] . '.' . $this->_meta['fields'][$this->_link];
		$sql .= ' = :';
		$sql .= spl_object_hash($this);
		 
		return array(
			'join' => array(
				'sql' => $sql,
				'value' => array(spl_object_hash($this) => $this->_value)
			)
		);
	}
}