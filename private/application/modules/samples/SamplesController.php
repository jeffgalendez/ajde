<?php 

class SamplesController extends Ajde_Acl_Controller
{
	protected $_allowedActions = array(
		'view',
	);
	
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
        return $this->render();
    }
		
	function edit()
	{
		Ajde_Model::register($this);
		return $this->render();
	}
}
