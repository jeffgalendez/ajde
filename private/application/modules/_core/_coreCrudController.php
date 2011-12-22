<?php

/**
 * Default to extend AjdeX_Acl_Controller for enhanced security
 */
class _coreCrudController extends AjdeX_Acl_Controller
{
	/************************
	 * Ajde_Component_Crud
	 ************************/
	
	public function listDefault()
	{
		$cache = Ajde_Cache::getInstance();
		$cache->disable();
		
		if (Ajde::app()->getRequest()->has('edit') || Ajde::app()->getRequest()->has('new')) {
			return $this->editDefault();			
		}
		
		$crud = $this->getCrudInstance();
		
		$session = new Ajde_Session('AC.Crud');
		$session->setModel($crud->getHash(), $crud);
		
		$view = $crud->getTemplate();		
		$view->assign('crud', $crud);
		
		return $view->render();
	}
	
	public function editDefault()
	{
		/* @var $crud AjdeX_Crud */
		$this->setAction('edit');
		
		$crud = $this->getCrudInstance();
		$editOptions = $crud->getOptions('edit');
		if ($crud->getOperation() === 'list') {
			if (!empty($editOptions) &&
					isset($editOptions['action'])) {
				$crud->setAction($editOptions['action']);
			} else {
				$crud->setAction('edit');
			}
		}
		
		if (!$crud->hasId()) {
			$crud->setId(Ajde::app()->getRequest()->getParam('edit', false));
		}
			
		$session = new Ajde_Session('AC.Crud');
		$session->setModel($crud->getHash(), $crud);
		
		$view = $crud->getTemplate();		
		
		$view->requireJsFirst('component/shortcut', 'html', MODULE_DIR . '_core/');		
		$view->assign('crud', $crud);
		
		return $view->render();
	}
	
	public function commitJson()
	{
		$operation = Ajde::app()->getRequest()->getParam('operation');
		$crudId = Ajde::app()->getRequest()->getParam('crudId');
		$id = Ajde::app()->getRequest()->getPostParam('id', false);
		
		AjdeX_Model::registerAll();
		
		switch ($operation) {
			case 'delete':
				return $this->delete($crudId, $id);
				break;
			case 'save':
				return $this->save($crudId, $id);
				break;
			default:
				return array('operation' => $operation, 'success' => false);
				break;
		}
	}
	
	public function delete($crudId, $id)
	{
		$session = new Ajde_Session('AC.Crud');
		$crud = $session->getModel($crudId);
		$model = $crud->getModel();
				
		$model->loadByPK($id);
		$success = $model->delete();
		
		return array('operation' => 'delete', 'success' => $success);
	}
	
	public function save($crudId, $id)
	{
		$session = new Ajde_Session('AC.Crud');		
		/* @var $crud AjdeX_Crud */
		/* @var $crud AjdeX_Crud */
		$crud = $session->getModel($crudId);
		/* @var $model AjdeX_Model */
		$model = $crud->getModel();		
		$model->setOptions($crud->getOptions('model'));
		
		// Get POST params
		$post = $_POST;
		foreach($post as $key => $value) {
			// Include empty values, so we can set them to null if the table structure allows us
//			if (empty($value)) {
//				unset($post[$key]);
//			}
		}
		$id = issetor($post["id"]);
		$operation = empty($id) ? 'insert' : 'save';
		
		if ($operation === 'save') {
			$model->loadByPK($id);
		}
		$model->populate($post);
		if (!$model->validate()) {
			return array('operation' => $operation, 'success' => false, 'errors' => $model->getValidationErrors());
		}
		$success = $model->{$operation}();
		if ($success === true) {
			// Destroy reference to crud instance
			$session->destroy($crudId);
		}
		return array('operation' => $operation, 'success' => $success);
	}
}