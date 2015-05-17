# Exception 90022 #
## Class Tidy not found ##

# Details #
For the document processor `Ajde_Document_Format_Processor_Html_Beautifier` to work, the PHP Tidy extension has to be installed.

# Solution #
  * On Windows, uncomment the `extension=php_tidy.dll` in `php.ini`.
  * On Linux ...
  * To disable this processor, make sure in the config files, Beautifier is disabled:

```
public $documentProcessors = array(
    "html" => array(
        /*"Beautifier"*/
    )
);	
```