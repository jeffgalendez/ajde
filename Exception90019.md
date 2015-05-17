# Exception 90019 #
## No variable with name _VARIABLENAME_ assigned to template ##

# Details #
In a template, assigned variables can be accessed with

```
/* @var $this Ajde_Template_Parser_Phtml_Helper */
echo $this->variable_name;
```

for PHTML templates or

```
<av:variable_name/>
```

for XHTML templates. This exception is thrown when a variable accessed in this way is not assigned to the template per

```
/* @var $template Ajde_Template */
$template->assign('variable_name', 'value');
```