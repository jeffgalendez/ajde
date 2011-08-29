<?php 

class SamplesController extends Ajde_Controller
{
	function view()
    {
        $id = $this->hasId() ? $this->getId() : 'Undefined';
        return 'Id : ' . $id;
    }
		
	function helloworld()
	{
		//return '<h2>Hello World!</h2>';
		return $this->render();
	}
}
