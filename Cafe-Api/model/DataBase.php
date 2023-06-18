
<?php

use Database as GlobalDatabase;

// ini_set('display_errors', '1');
// ini_set('display_startup_errors', '1');
// error_reporting(E_ALL);

class Database
{

  public $isConnected;
  public $connection;

  public function __construct()

  {
    require('connection_credits.php');
    $this->isConnected = true;
    try {
      $dsn = "mysql:dbname={$dbname};host={$dbhost};port={$dbport}";
      $this->connection = new PDO($dsn, $dbuser, $dbpassword);
      $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      throw new Exception($e->getMessage());
    }
  }

  public function getrows($tablename, $quary)
  {

    try {


      $stmt = $this->connection->prepare($quary);
      $stmt->execute();




      return $stmt;
    } catch (PDOException $e) {

      throw new Exception($e->getMessage());
    }
  }

  public function getrow($tablename, $quary, $parms = [])

  {

    try {
      //  $quary = "select * from $tablename where id=?";
      $stmt = $this->connection->prepare($quary);
      $stmt->execute($parms);

      return $stmt;
    } catch (PDOException $e) {

      throw new Exception($e->getMessage());
    }
  }
  public function insertRow($tablename, $quary, $parms = [])
  {
    try {

      $stmt = $this->connection->prepare($quary);
      $stmt->execute($parms);
      return TRUE;
    } catch (PDOException $e) {

      throw new Exception($e->getMessage());
    }
  }

  public function updateRow($tablename, $quary, $parms = [])
  {
    $stmt = $this->connection->prepare($quary);
    $stmt->execute($parms);
  }

  public function deleteRow($tablename, $quary, $parms = [])
  {
    try {
      $stmt = $this->connection->prepare($quary);
      $stmt->execute($parms);
      return true;
    } catch (PDOException $e) {

      throw new Exception($e->getMessage());
    }
  }
}


?>