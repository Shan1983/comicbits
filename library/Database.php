<?php

class Database {

    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname = DB_NAME;

    private $databaseHandler;
    private $error;
    private $statement;


    public function __construct()
    {
        // configure the PDO Data Source Name
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;

        /* set up the PDO options, they configure the PDO and set things
           like error reporting, if the data should be made persistent,
           there are many of these, have a google to find out more.. [PDO options] */
        $options = [
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ];

        // ok now lets create the PDO instance
        $this->databaseHandler = new PDO($dsn, $this->user, $this->pass, $options);

    }

    /*
     Some of the following PDO methods I have wrapped up in their own methods,
     I did this so my IDE would play nice!
    */

    // set up how to handle the querying..
    public function query($query){
        $this->statement = $this->databaseHandler->prepare($query);
    }

    // set up how PDO binds its queries
    public function bind($param, $value, $type = null) {
        if (is_null($type)) {
            switch (true) {
                case is_int($value) :
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value) :
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value) :
                    $type = PDO::PARAM_NULL;
                    break;
                default :
                    $type = PDO::PARAM_STR;
            }
        }
        $this->statement->bindValue($param, $value, $type);
    }

    // execute a query
    public function execute() {
        return $this->statement->execute();
    }

    // return a set of results, kinda like get me the entire table..
    public function resultSet() {
        $this->execute();
        return $this->statement->fetchAll(PDO::FETCH_OBJ);
    }

    // get a single result..
    public function single() {
        $this->execute();
        return $this->statement->fetch(PDO::FETCH_OBJ);
    }

    // get the row count
    public function rowCount() {
        $this->statement->rowCount();
    }

    // and if super needed?? this will return the last id that was inserted
    public function lastInsertedId() {
        return $this->databaseHandler->lastInsertId();
    }

}