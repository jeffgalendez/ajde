# Ajde\_Document #

An abstract class from which all document formats are derived from. Holds the metadata of a document (i.e. title and description), the resources (i.e. js and css files) and is used to render output to the screen.



## Usage ##

[Ajde\_Application](APIApplication.md) initializes a document in the requested format based on the current route.

```
$route = new Ajde_Core_Route('main.html');
$document = Ajde_Document::fromRoute($route);
echo get_class($document); // 'Ajde_Document_Format_Html'
echo $document->getFormat(); // 'html'
```

In your controller, you can get the current document and perform operations on it.

```
$document = Ajde::app()->getDocument();

// Sets the document title
$document->setTitle('First page!');

// Sets the document description
$document->setDescription('Description of this page');

// Sets a custom layout for this controller action
$document->setLayout(new Ajde_Layout('custom'));
```

Keep in mind that documents set default header options in the [Ajde\_Http\_Response](APIHttpResponse.md) object. If you request a page with the 'json' format for example, the default cache-control for that page will be set to 'no-cache' instead of 'private'.

→ More about [Caching](DocumentationCaching.md)

## Methods ##

## Decendants ##

  * [Ajde\_Document](APIDocument.md)

## Ancestor ##

  * [Ajde\_Document](APIDocument.md)