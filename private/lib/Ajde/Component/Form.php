<?php 

class Ajde_Component_Form extends Ajde_Component
{
	public static function processStatic(Ajde_Template_Parser $parser, $attributes)
	{
		$instance = new Ajde_Component_Form($parser, $attributes);
		$t = new stdClass(); // Force unique object hash, see http://www.php.net/manual/es/function.spl-object-hash.php#76220
		return $instance->process();
	}
	
	protected function _init()
	{
		return array(
			'ajax' => 'ajax',
			'route' => 'form',
			'saveDir' => 'upload' 
		);
	}
	
	public function process()
	{
		switch($this->_attributeParse()) {
		case 'form':
			$controller = Ajde_Controller::fromRoute(new Ajde_Core_Route('_core/component:form'));
			
			$controller->setFormAction($this->attributes['route']);
			$controller->setFormId(issetor($this->attributes['id'], spl_object_hash($this)));
			$controller->setExtraClass(issetor($this->attributes['class'], ''));
			$controller->setInnerXml($this->innerXml);
			
			return $controller->invoke();
			break;
		case 'ajax':
			$controller = Ajde_Controller::fromRoute(new Ajde_Core_Route('_core/component:formAjax'));
			$formAction = new Ajde_Core_Route($this->attributes['route']);
			$formAction->setFormat('json');
			
			$controller->setFormAction($formAction->__toString());
			$controller->setFormId(issetor($this->attributes['id'], spl_object_hash($this)));
			$controller->setExtraClass(issetor($this->attributes['class'], ''));
			$controller->setInnerXml($this->innerXml);
			
			return $controller->invoke();
			break;
		case 'upload':
			$controller = Ajde_Controller::fromRoute(new Ajde_Core_Route('_core/component:formUpload'));
			
			$controller->setName($this->attributes['name']);
			$controller->setSaveDir($this->attributes['saveDir']);
			$controller->setExtensions($this->attributes['extensions']);
			$controller->setInputId(issetor($this->attributes['id'], spl_object_hash($this)));
			$controller->setExtraClass(issetor($this->attributes['class'], ''));
			
			return $controller->invoke();
			break;
		}
		// TODO:
		throw new Ajde_Component_Exception();	
	}
	
}