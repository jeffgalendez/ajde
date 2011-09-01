<?php 

class BlogController extends Ajde_Controller
{
	function view()
    {
    	AjdeX_Model::register($this);
		$blog = new BlogCollection();
		$blog->load();
		$this->getView()->assign('blog', $blog);
        return $this->render();
    }
		
	function edit()
	{
		AjdeX_Model::register($this);
		return $this->render();
	}
}
