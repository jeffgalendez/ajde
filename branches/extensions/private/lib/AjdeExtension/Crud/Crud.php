<?php

class AjdeExtension_Crud extends Ajde_Object_Standard
{
	protected $_model = null;
	protected $_collection = null;
	
	public function __construct($model) {
		if ($model instanceof AjdeExtension_Model) {
			$this->_model = $model;
		} else {
			$modelName = ucfirst($model) . 'Model';
			$this->_model = new $modelName();
		}
	}
	
	/**
	 * @return AjdeExtension_Collection
	 */
	public function getCollection()
	{
		if (!isset($this->_collection))	{
			$collectionName = str_replace('Model', '', get_class($this->getModel())) . 'Collection';
			$this->_collection = new $collectionName();
		}
		return $this->_collection;
	}
	
	/**
	 * @return AjdeExtension_Model
	 */
	public function getModel()
	{
		return $this->_model;
	}
	
	public function getItem($id)
	{
		$model = $this->getModel();
		$model->loadByPK($id);
		return $model;
	}
	
	public function getItems()
	{
		$collection = $this->getCollection();
		$collection->reset();
		$collection->load();
		return $collection;
	}
	
	public function getFields()
	{
		$model = $this->getModel();
		return $model->getTable()->getFieldLabels();
	}
}