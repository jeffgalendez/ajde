<?php

class Ajde_Crud_Field_Boolean extends Ajde_Crud_Field
{
	protected function _getHtmlAttributes()
	{
		$attributes = '';
		$attributes .= ' type="hidden" ';
		$attributes .= ' value="'.$this->getValue().'" ';
		return $attributes;		
	}
}