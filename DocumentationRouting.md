# Documentation Routing #

More information about how URLs are parsed in Ajde.

## Details ##

In `Ajde_Core_Route` all pre-defined routes can be found in the function `_extractRouteParts()`. You can, however, add custom routes to your application by adding them to your [configuration](DocumentationConfig.md) as follows:

```
public $routes = array(
	// Add route: ./module/post-5
	array('%^([^\?/\.]+)/post-([0-9]+)/?$%' => array('module', 'id'))
);
```

This configuration parameter must be an array of arrays with a regular expression as key and an array of route parts as value. Routes defined in the configuration have precedence over the pre-defined ones.

## Examples ##

To illustrate the pre-defined routes defined, let's create a new module created `article` with the following controller:

```
<?php 
// ArticleController.php

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
```

Now, the following URLs all point to the **view** action of above controller.

  * _localhost_/article/
  * _localhost_/article/**4** (_ID must be numeric_)
  * _localhost_/article/**view**/
  * _localhost_/article/**view**/**html**
  * _localhost_/article/**view**/**html**/**4**

  * _localhost_/article.**html**
  * _localhost_/article/**4**.**html** (_ID must be numeric_)
  * _localhost_/article/**view**.**html**
  * _localhost_/article/**view**/**4**.**html**

Also, try out these URLs:

  * _localhost_/article/**add**
  * _localhost_/article/**add**.**json**
  * _localhost_/article/**save**
  * _localhost_/article/**save**.**json**

### Subcontrollers ###

Subcontrollers can also be addressed per pre-defined routes. Let's illustrate by creating a subcontroller for the `article` module containing:

```
<?php 
// ArticleArchiveController.php

class ArticleArchiveController extends Ajde_Controller
{       
        function view()
        {
                $id = $this->hasId() ? $this->getId() : 'Undefined';
                return 'Archive Id : ' . $id;
        }
}
```

You can now make these requests to the subcontroller:

  * _localhost_/article/**archive:view**/
  * _localhost_/article/**archive:view**/**html**/
  * _localhost_/article/**archive:view**/**html**/**4**/
  * _localhost_/article/**archive:view**.**html**
  * _localhost_/article/**archive:view**/**4**.**html**