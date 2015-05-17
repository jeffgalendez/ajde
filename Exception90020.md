# Exception 90020 #
## Number of routeparts does not match regular expression ##

# Details #
The matched route does not have the right number of routeparts defined.

**Example**
Three groups are present in the this routes regular expression, whereas only two routeparts are defined.
```
array('%^([^/\.]+)/([^/\.]+)/([^/\.]+)/?$%' => array('module', 'action')),
```