<?php 

class SamplesController extends Ajde_Acl_Controller
{
	protected $_allowedActions = array(
		'view',
	);
	
	public function beforeInvoke()
	{
		Ajde::app()->getDocument()->setTitle("Samples");
		return true;
	}
	
	function view()
    {
    	Ajde_Model::register($this);
		/* @var $blog BlogCollection */
		// Direct object creation and chaining only from PHP 5.3!
		// Use $blog = new BlogCollection() instead
		$blog = BlogCollection::create()
			->orderBy('date', Ajde_Query::ORDER_DESC)
			->load();
		$this->getView()->assign('blog', $blog);
		Ajde_Dump::warn('This is a test warning');
		Ajde::app()->getDocument()->setDescription("This is the samples module");
        return $this->render();
    }
		
	function edit()
	{
		Ajde_Model::register($this);
		return $this->render();
	}
	
	function sessionTest()
	{		
		$t = new Foo();
		$session = new Ajde_Session("foo");
		$session->setModel('t', $t);
		return 'set in session';
	}
	
	function retrieve()
	{
		$session = new Ajde_Session("foo");
		$t = $session->getModel('t', $t);
		return (string) $t;
	}
	
	function error()
	{
		echo (string) new Foo();
	}
	
	function payment()
	{
		return $this->render();
	}
}

class Foo extends Bar {
	function __construct() {
		
	}
}

class Bar {
	function __toString() {
		return 'bar';
	}
}