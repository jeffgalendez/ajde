# Exception 90017 #
## Compressor for type _DOCUMENTFORMAT_ not found ##

# Details #

Local resource compressors need to have the class prefix `Ajde_Template_Resource_Local_Compressor_*`. For example, the JavaScript compressor is called `Ajde_Template_Resource_Local_Compressor_Js`.

**Example:**
```
$compressor = Ajde_Template_Resource_Local_Compressor::fromType("js");
```