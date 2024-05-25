# SQL-Request-Master-PHP
SQL Request Master allows you to use CRUD (Create, Read, Update, Delete) system without the same code in projects. All queries are executed using PDO, which prevents your applications from being hacked using SQL Injections.

### Connecting to the database ###
```php
require 'library/SQLRequestMaster.php';

$sql = new SQLRequestMaster("localhost", "root", "", "database");
```

### Methods ###

`1` Creating a record in the database
```php
$data = array(
    "id" => 2,
    "status" => "false",
    "name" => "Test"
 );

$sql->Create("test", $data);
$sql->Close();
```

`2` Reading records in the database
```php
$data = $sql->Read("test");

 foreach ($data as $userData) {
   echo "ID: " . $userData['id'] . ", Status: " . $userData['status'];
 }

$sql->Close();
```

`3` Updateing records in the database
```php
$data = array('status' => false);
$condition = "id = 2";
$sql->Update("test", $data, $condition);

$sql->Close();
```

`4` Deleting records in the database
```php
$condition = "id = 2";
$sql->Delete("test", $condition);

$sql->Close();
```
