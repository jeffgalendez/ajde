<?php 

class ArticleArchiveController extends Ajde_Controller
{       
        function view()
        {
                $id = $this->hasId() ? $this->getId() : 'Undefined';
                return 'Archive Id : ' . $id;
        }
}