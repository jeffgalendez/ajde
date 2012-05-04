<?php

class Ajde_Crud_Field_Fk extends Ajde_Crud_Field
{
	/**
	 *
	 * @var Ajde_Collection
	 */
	private $_collection;
	
	/**
	 *
	 * @var Ajde_Model
	 */
	private $_model;
	
	public function getCollection()
	{
		if (!isset($this->_collection)) {
			$collectionName = ucfirst($this->getName()) . 'Collection';
			$this->_collection = new $collectionName;
		}
		return $this->_collection;
	}
	
	public function getModel()
	{
		if (!isset($this->_model)) {
			$modelName = ucfirst($this->getName()) . 'Model';
			$this->_model = new $modelName;
		}
		return $this->_model;
	}
	
	public function getValues()
	{
		$this->getCollection()->orderBy($this->getModel()->getDisplayField());
		$return = array();
		foreach($this->getCollection() as $model) {
			$return[(string) $model] = $model->get($model->getDisplayField());
		}
		return $return;
	}
}