<?php
require 'config.php';

class MySQLDatabase{

  private $connection;
  public $last_query;
  public $last_query_params;
  public $last_affected_rows;
  public $last_insert_id;

  function __construct(){
    $this->open_connection();
  }

  public function open_connection(){
    try{
        $dsn = "mysql:dbname=" . DB_NAME . ";host=" . DB_SERVER;
        $this->connection = new PDO($dsn, DB_USER, DB_PASS);
    }catch (PDOException $e){
        $this->confirm_query($e);
    }
  }

  public function close_connection(){
    if (isset($this->connection)){
      unset($this->connection);
    }
  }

  public function query($sql, $given_params=NULL){

    // check if query is INSERT query
    $is_sql_insert = false;
    if (strpos($sql, 'INSERT') !== false) {
      $is_sql_insert = true;
    }

    // if $params is not an array, make an array with the value
    if(!empty($given_params) && !is_array($given_params)){
      $params = array();
      array_push($params, $given_params);
    } else {
      $params = $given_params;
    }

    // store query data
    $last_query = $sql;
    $last_query_params = $params;

    $results = [];

    if (empty($params)){
      try{
        $stmt = $this->connection->query($sql);
        $result_flag = $stmt->execute();  // if success, flag is true
        $this->last_affected_rows = $stmt->rowCount();
      } catch (PDOException $e){
        $this->confirm_query($e);
      }
    } else {
      try{
        $stmt = $this->connection->prepare($sql);
        $result_flag = $stmt->execute($params);
        $this->last_affected_rows = $stmt->rowCount();
      } catch (PDOException $e){
        $this->confirm_query($e);
      }
    }

    // if sql is insert query, return true or false
    if ($is_sql_insert){
      $this->last_insert_id = $this->connection->lastInsertId;
      return $result_flag;
    } else {
      $this->last_insert_id = "";
    }

    // organize result in an array
    while($result = $stmt->fetch(PDO::FETCH_OBJ)){
        array_push($results, $result);
    }
    return $results;
  }

  public function confirm_query($e){
    $output = "Database query failed: " . $e->getMessage() . "<br /><br />";
    $output .= "Last SQL query : " . $this->last_query;
    die($output);
  }

  public function get_affected_rows(){
    return $this->last_affected_rows;
  }

  /*
   * Returns the auto generated id used in the latest query
  */
  public function insert_id(){
    return $this->last_insert_id;
  }

  public function insert($sql, ...$params){
    $this->query($sql, ...$params);
  }
}

$db = new MySQLDatabase();
$database = &$db;