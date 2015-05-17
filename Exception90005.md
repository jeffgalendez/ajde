# Exception 90005 #
## Unable to load _CLASSNAME_ ##

# Details #

The Autoloader could not find the requested class. Make sure it exist in the appropriate directory.

Example:
```
$test = new Ajde_Not_Existing_Class();
```

Note:
This exception is only thrown if you use PHP version 5.3.0 or higher.