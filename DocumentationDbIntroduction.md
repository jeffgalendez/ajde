# Connect to a database #



## Requirements ##

At the moment, Ajde only has an adapter for a MySQL database server. However, it should be easy to write an adapter for other, SQL based, database systems.

## Setup ##

In your `Config_Application.php`, include your database parameters like this:

```
public $dbDsn = array(
  "host" => "HOST",
  "dbname" => "DATABASE"
);
public $dbUser = 'USERNAME';
public $dbPassword = 'PASSWORD';
```

→ Working with the [application configuration](DocumentationConfig.md)

## General use ##

AjdeX provide an implementation of the [PHP PDO classes](http://php.net/manual/en/book.pdo.php) in the form of an `AjdeX_Db_PDO` and `AjdeX_Db_PDOStatement` class for you to work with.

A very basic example considered everything is set up correctly:

```
// Grab a database connection, returns AjdeX_Db_PDO object
$conn = AjdeX_Db::getInstance()->getConnection();

// Get everything
$sql = "SELECT * FROM user";

// Grab a AjdeX_Db_PDOStatement to work with and execute the query
$statement = $conn->prepare($sql);
$statement->execute();

// Load the results as an array
$results = $statement->fetchAll(PDO::FETCH_ASSOC);

// Display email addresses
foreach($results as $item) {
	echo $item["email"] . "<br/>";
}		
```

Most of this code can be simplified by using the `AjdeX_Model` and `AjdeX_Collection` database abstraction classes:

```
$users = new UserCollection();
$users->load();
foreach($users as $user)
	echo $user->email . "<br/>";
}
```

→ More about the [AjdeX database abstraction](DocumentationModelCollection.md)