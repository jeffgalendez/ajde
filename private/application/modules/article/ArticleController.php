<?php 

class ArticleController extends Ajde_Controller
{       
        function view()
        {
                $id = $this->hasId() ? $this->getId() : 'Undefined';
                return 'Id : ' . $id;
        }

        function addHtml()
        {
                return 'Add item';
        }

        function saveJson()
        {
                return array('status' => 'saved');
        }
}