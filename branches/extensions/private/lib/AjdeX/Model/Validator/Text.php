<?php

class AjdeX_Model_Validator_Text extends AjdeX_Model_ValidatorAbstract
{
	protected function _validate()
	{
		if (!empty($this->_value)) {
			if ($length = $this->getLength()) {
				if (strlen($this->_value) > $length) {
					return array('valid' => false, 'error' => sprintf(
							__('Text is too long (max. %s characters)'), $length
						));
				}
			}
		}
		$strippedHtml = strip_tags($this->_value);
		if ($this->getIsRequired() && empty($strippedHtml)) {
			return array('valid' => false, 'error' => __('Required field'));
		}
		return array('valid' => true);
	}
}