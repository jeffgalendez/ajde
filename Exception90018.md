# Exception 90018 #
## Could not create instance of incompatible class _CLASSNAME_ ##

# Details #
Altough Ajde is largely compatible with the Zend Framework, some classes, especially those in the Zend Framework MVC category, are not compatible with Ajde due to request/response interactions, route interpretation etc.

**Example:**
```
$app = new Zend_Application('live');
```