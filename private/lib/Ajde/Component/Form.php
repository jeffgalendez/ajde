<?php 

class Ajde_Component_Form extends Ajde_Component_Resource
{
	public static function processStatic(Ajde_Template_Parser $parser, $attributes)
	{
		$instance = new self($parser, $attributes);
		return $instance->process();
	}
	
	protected function _init()
	{
		return array(
			'route' => 'route'
		);
	}
	
	public function process()
	{
		switch($this->_attributeParse()) {
		case 'route':
			$controller = Ajde_Controller::fromRoute(new Ajde_Core_Route('component/formAjax'));
			$formAction = new Ajde_Core_Route($this->attributes['route']);
			$formAction->setFormat('json');
			
			$controller->setFormAction($formAction->__toString());
			$controller->setFormId(issetor($this->attributes['id'], spl_object_hash($this)));
			
			return $controller->invoke();
			break;
		}		
		// TODO:
		throw new Ajde_Component_Exception();	
	}
}