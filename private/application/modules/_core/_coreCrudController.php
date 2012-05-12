<?php

/**
 * Default to extend Ajde_Acl_Controller for enhanced security
 */
class _coreCrudController extends Ajde_Acl_Controller
{
	/************************
	 * Ajde_Component_Crud
	 ************************/
	
	public function beforeInvoke() {
		$crud = $this->getCrudInstance();
		if (!$crud && Ajde::app()->getRequest()->has('crudId')) {
			Ajde_Model::registerAll();
			$session = new Ajde_Session('AC.Crud');			
			$crud = $session->getModel(Ajde::app()->getRequest()->getParam('crudId'));
		}
		if ($crud) {
			/* @var $crud Ajde_Crud */
			$this->setAclParam($crud->getSessionName());
		}
		return parent::beforeInvoke();
	}
	
	public function listHtml()
	{
		$cache = Ajde_Cache::getInstance();
		$cache->disable();
		
		if (Ajde::app()->getRequest()->has('edit') || Ajde::app()->getRequest()->has('new')) {
			return $this->editDefault();			
		}
		
		if (Ajde::app()->getRequest()->has('output') && Ajde::app()->getRequest()->get('output') == 'table') {
			Ajde::app()->getDocument()->setLayout(new Ajde_Layout('empty'));
		}
				
		$crud = $this->getCrudInstance();
		/* @var $crud Ajde_Crud */
		if (!$crud) {
			Ajde::app()->getResponse()->redirectNotFound();
		}		
				
		$session = new Ajde_Session('AC.Crud');
		$session->setModel($crud->getHash(), $crud);
		
		$viewSession = new Ajde_Session('AC.Crud.View');
		$sessionName = $crud->getSessionName();
		if ($viewSession->has($sessionName)) {
			$crudView = $viewSession->get($sessionName);
		} else {
			$crudView = new Ajde_Collection_View($sessionName);
		}
		$viewParams = Ajde::app()->getRequest()->getParam('view', array());
		$crudView->setOptions($viewParams);
		$viewSession->set($sessionName, $crudView);
		
		$crud->getCollection()->setView($crudView);
		
		$view = $crud->getTemplate();		
		$view->assign('crud', $crud);
		
		return $view->render();
	}
	
	public function editDefault()
	{		
		$this->setAction('edit');
		
		$crud = $this->getCrudInstance();
		/* @var $crud Ajde_Crud */
		if (!$crud) {
			Ajde::app()->getResponse()->redirectNotFound();
		}
		
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
		
		// Editor
		if (Config::get('textEditor')) {
			$editorClassName = "Ajde_Crud_Editor_" . ucfirst(Config::get('textEditor'));
			$textEditor = new $editorClassName();
			/* @var $textEditor Ajde_Crud_Editor */
			$textEditor->getResources($view);
		}
		
		return $view->render();
	}
	
	public function commitJson()
	{
		$operation = Ajde::app()->getRequest()->getParam('operation');
		$crudId = Ajde::app()->getRequest()->getParam('crudId');
		$id = Ajde::app()->getRequest()->getPostParam('id', false);
		
		Ajde_Model::registerAll();
		
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
		
		if (!is_array($id)) {
			$id = array($id);
		}
		
		$success = true;
		foreach($id as $elm) {
			$model->loadByPK($elm);
			$success = $success * $model->delete();
		}
		
		return array('operation' => 'delete', 'success' => (bool) $success);
	}
	
	public function save($crudId, $id)
	{
		$session = new Ajde_Session('AC.Crud');		
		/* @var $crud Ajde_Crud */
		$crud = $session->getModel($crudId);
		/* @var $model Ajde_Model */
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
		return array('operation' => $operation, 'id' => $model->getPK(), 'success' => $success);
	}
}