<?php

/*
 * Working with PDO [ PHP Data Objects ]
 * Create a PDO CLASS
 * If we were to create a PDO OBJECT we would need a CONSTRUCTOR FUNCTION
 */

//Set up the dBase class vars to connect to the MySQL dBase
class Database {
    //DSN = Data Source Name identifying the HOST COMPUTER and DATABASE to be USED
    private static $dsn = 'mysql:host=localhost;dbname=shoppinglist';
    //DEFINING username to LOGIN to dBase
    private static $username = 'root';
    //DEFINING password to LOGIN to dBase
    private static $password = 'password';
    //DEFINING EMPTY var for the dBase
    private static $db;
    
    /*private function __construct() { }*/

    //Creating the getDB METHOD which connects to the assigned dBase using a try/catch exception
    //if dBAse connection not created/open
    public static function getDB() {
    if(!isset(self::$db)) {
        //try to connect to dBase w the following credentials
        try {
            self::$db = new PDO(self::$dsn, self::$username, self::$password);
        //if there is an error - display and end the connection            
        } catch (PDOException $e) {
            self::display_db_error($e->getMessage());
            die();
        }
    } //END 
    //if all goes well return the dBase connection
    return self::$db;
    } //END METHOD getDB

    //Creating the display_db_error METHOD which collects and displays errors when requested
    public static function display_db_error($error_message) {
        $block = <<<ERR_MSG
        <div id="error"
                <h1>Database Error</h1>
                <p>A database error occurred.</p>
                <p>Error message: $error_message;</p>
                <br />
        </div>
//Make sure there is NO spaces before or after the ERR_MSG otherwise it will throw an error
ERR_MSG;
        echo $block;
    } //END METHOD display_db_error
} //END CLASS DATABASE
