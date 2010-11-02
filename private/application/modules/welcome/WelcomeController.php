<?php 

class WelcomeController extends Ajde_Controller
{	
	function viewDefault()
	{
		return $this->render();
	}
	
	function dbDefault()
	{
		AjdeExtension_Model::register($this);		
		$model = $this->getModel('test');		
		/* @var AjdeExtension_Model $model */
		$model->loadByPK(1);		
		$name = $model->name;		
		$this->getView()->assign('name', $name );
		
		AjdeExtension_Collection::register($this);		
		$collection = $this->getCollection('test');		
		/* @var AjdeExtension_Collection $collection */
		$collection->addFilter(new AjdeExtension_Collection_Filter('name', AjdeExtension_Collection_Filter::FILTER_IS, 'ajde') );
		$collection->load();			
		$this->getView()->assign('ajdes', $collection);		
		
		return $this->render();	
	}
	
	function zendDefault()
	{
		$date = new Zend_Date();
//		$app = new Zend_Application('live');
//		$app->run();
		
		$view = $this->getView();		
		$view->assign('date', $date);		
		return $view->render();
	}
}
