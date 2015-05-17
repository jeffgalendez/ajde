# Building a simple blog #

In this tutorial we'll set up the basics of a simple blog. This sample will be used in subsequent tutorials to elaborate on more advanced features of Ajde. You can check out the source code of all tutorials [here](http://code.google.com/p/ajde/source/browse/#svn%2Fbranches%2Fsamples)



## Setting up the database ##

For this example, we'll be using the MySQL adapter (currently the only one available) and create a single table to contain our blogposts.

The SQL for the table structure:

```

CREATE TABLE IF NOT EXISTS `blog` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `title` varchar(255) NOT NULL COMMENT 'Title',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Date',
  `content` text NOT NULL COMMENT 'Article',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
```

Now, we'll have to tell Ajde how to connect to the database. For this, open the `private/application/config/Config_Application.php` file and uncomment and/or edit the following lines to fit your database settings:

```
<?php

class Config_Application extends Config_Default {
        
        /* [...] */

        public $dbDsn = array("host" => "[HOST]", "dbname" => "[DB]");
        public $dbUser = "[USERNAME]";
        public $dbPassword = "[PASSWORD]";

        /* [...] */
        
}
```

_Note: You could also define different environments for development, staging and live systems._

→ Working with the [application configuration](DocumentationConfig.md)

## AjdeX ##

Now, for working with the database, we need the `AjdeX` extensions. Grab them from [SVN](DocumentationInstallationRequirements.md) and copy the `AjdeX` directory to your `private/lib/` directory.

The extensions provides you with a database abstraction layers, so there's no need for writing SQL yourself. This is a good practice, since it can help you with preventing SQL-injection attacks, and makes your code more clean and readable. Besides, it contains the tools to create a CRUD page for your models with only one line of code! (We'll get to this later)

## Module & Model ##

By default, AjdeX doesn't need any model definition because it grabs the structure from the db-schema for you to handle typecasting and validation. It only requires you to create a skeleton of the models - and optionally the collections - for each db table you want to use in your application. We'll do this now.

Create a new module called `blog` (see [Getting started with Ajde](DocumentationGettingStarted.md)) and in this module, a subdirectory called `model`. Here, create the following two files:

`BlogModel.php`:

```
<?php

class BlogModel extends AjdeX_Model
{
}
```

`BlogCollection.php`:

```
<?php

class BlogCollection extends AjdeX_Collection
{
}
```

Easy, huh?

_WORK IN PROGRESS_