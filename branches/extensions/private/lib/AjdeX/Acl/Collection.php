<?php

class AjdeX_Acl_Collection extends AjdeX_Collection
{
	public function findRule($type, $ugId, $module, $action)
	{
		foreach($this as $rule)
		{
			if ($rule->get('type') === $type
					&& $rule->get($type) === $ugId
					&& $rule->get('module') === $module
					&& $rule->get('action') === $action) {
				return $rule;
			}
		}
		return false;
	}
}
