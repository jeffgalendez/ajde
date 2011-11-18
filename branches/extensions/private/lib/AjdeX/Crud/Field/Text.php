<?php

class AjdeX_Crud_Field_Text extends AjdeX_Crud_Field
{
	protected function _getHtmlAttributes()
	{
		$attributes = '';
		$attributes .= ' type="text" ';
		$attributes .= ' value="' . Ajde_Component_String::escape($this->getValue()) . '" ';
		return $attributes;		
	}
}