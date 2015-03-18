fcPDO
=====

*Full compilation of PHP Database Objects for manipulating MySQL Database with PDO*

Thanks `FcPDO`, you can connect to all your MySQL Databases and manage them. Just add a method for each Database like method 'database_test' in FcPDOId Object and GO!

##Usage
To use `FcPDO` in your website, simply include fcpdo.controller.php in your PHP scripts. That's it! Super!

```php
require_once "fcpdo.php";
```

Add method in fcpdo.id.php for each data connexion of your databases.

##Full Example
```php
try{
   /**
   * Connect to MySQL Database describe in FcPDOId method "database_test"
   * You must create a method in FcPDOId for each of your Database
   */
   /* Persistent connection */
   $database=FcPDOController::get("database_test",true);
   /* Non persistent connection */
   $database=FcPDOController::get("database_test");
   /** Get transaction state : return false */
   $database->transaction();
   /** Begin transaction */
   $database->begin();
   /** Begin transaction : manage by FcPDO Object, no thrown Exception */
   $database->begin();
   /** Select in Database */
   $result = $database->select("SELECT * from type");
   /** Select in Database with param and other fetch style */
   $result = $database->select("SELECT * from type WHERE id=:id", array("id"=>1),2);
   /** Add a row */
   $newRow = $database->exe("INSERT INTO type(name) VALUES(:name)", array("name"=>"Test"));
   /** Add a row and get new Id (need a table with auto-increment primary key) */
   $newRow = $database->exe("INSERT INTO type(name) VALUES(:name)", array("name"=>"Test2"));
   /** Get transaction state : return true */
   $database->transaction();
   /** Let you know last query and las param */
   echo "Last query " . $database->getLastQuery() . " and last param " . print_r($database->getLastParam(),true);
   /** Rollback transaction */
   $database->rollback();
   /** Commit transaction : manage by PcPDO Object, no thrown Exception */
   $database->commit();
}
catch(PDOException $pdoE){
   var_dump($pdoE);
}
catch(Exception $e){
   var_dump($e);
}
```

##Advantages
1. Connection to several database
2. Singleton management for manage and use just one instanciation for each connection
3. Let you make persistent or non persistent connection
4. Cross-object transaction (automatically desactivate with persistent connection)
5. Save PDOStatement for better performance

## License
`FcPDO` is licensed under the MIT license. (http://opensource.org/licenses/MIT)
