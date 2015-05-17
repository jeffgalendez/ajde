# Exception 90013 #
## JavaScript library _LIBRARYNAME_ not available ##

# Details #

When requiring an JavaScript library from the `Ajde_Template_Resource_JsLibrary` class, given library name must be available in this class.

Example:
```
$name = 'unknown_library';
$version = '1.5';
$url = Ajde_Template_Resource_JsLibrary::getUrl($name, $version);
$resource = new Ajde_Template_Resource_Remote(Ajde_Template_Resource::TYPE_JAVASCRIPT, $url);
Ajde::app()->getDocument()->addResource($resource);
```