<?php
require_once(LIB_PATH.'database'.DS.'database.php');
class Admin {

  public $id;
  public $username;
  public $password;
  public $first_name;
  public $last_name;
  public $email;
  public $created_at;
  public $updated_at;
  protected static $table_name = "admin";
  protected static $db_fields = array('id', 'username', 'password', 'first_name', 'last_name', 'email', 'created_at', 'updated_at');

  public function __construct($username="", $password="", $first_name="", $last_name="", $email=""){
    $this->id = "";
    $this->username = $username;
    $this->password = self::create_hashed_password($password);
    $this->first_name = $first_name;
    $this->last_name = $last_name;
    $this->email = $email;
  }

  private static function create_hashed_password($given_password){
    // crypt username and password
    $hash = hash("sha256", $given_password);
    $password = hash("sha256", getenv("SALT_A").$given_password.getenv("SALT_B"));
    return $password;
  }

  public static function find_all(){
    global $table_name;
    return self::find_by_sql("SELECT * FROM " . self::$table_name);
  }

  public static function find_by_id($id=1){
    global $table_name;
    $sql = "SELECT * FROM " . self::$table_name . " WHERE id = ?";
    $result_set = self::find_by_sql($sql, $id);
    return self::sort_result($result_set);
  }

  public static function find_by_sql($sql="", $params=""){
    global $database;
    $result_set = $database->query($sql, $params);
    return $result_set;
  }

  public function full_name(){
    if (isset($this->first_name) && isset($this->last_name)){
      return $this->first_name . " " . $this->last_name;
    } else {
      return "";
    }
  }

  public static function authenticate($username="",$given_password=""){
    global $table_name;
    $sql = "SELECT * FROM ". self::$table_name . " WHERE username = ? AND password = ? LIMIT 1";

    // crypt username and password
    $password = self::create_hashed_password($given_password);

    $params = array($username, $password);
    $result_set = self::find_by_sql($sql, $params);
    return !empty($result_set) ? self::sort_result($result_set) : false;
  }

  private static function sort_result($result_set){
    if (count($result_set) == 1){
      // if only one element is in the result array, instantiate, and return the object
      return self::instantiate(array_shift($result_set));
    } else {
      $object_array = array();
      foreach ($result_set as $result) {
        $object_array = self::instantiate($result);
      }
      return $object_array;
    }
  }

  private static function instantiate($record){
    $object = new self;
    // $object->id = $record['id']
    // $object->username = $record['username'] etc
    foreach ($record as $attribute => $value) {
      if($object->has_attribute($attribute)){
        $object->$attribute = $value;
      }
    }
    return $object;
  }

  private function has_attribute($attribute){
    // return an associative array with all attributes of the object
    $object_vars = $this->attribute();
    // return if the key exists, true/false
    return array_key_exists($attribute, $object_vars);
  }

  protected function attribute(){
    $attributes = array();
    foreach (self::$db_fields as $field) {
      if(property_exists($this, $field)){
        $attributes[$field] = $this->$field;
      }
    }
    return $attributes;
  }

  public function save(){
    // A new record won't have an id yet.
    return (empty($this->id)) ? $this->create() : $this->update();
  }

  public function create(){
    // check if all the properties are set

    if (!$this->is_all_properties_set()){
      return false;
    }

    // create attribute array without id attribute
    $attributes = $this->attribute();
    unset($attributes['id']);
    // index 0 is gone. put index in order
    $attributes = array_merge($attributes);

    global $table_name;
    global $database;

    // create sql from table name, attribute name
    $sql = "INSERT INTO " . self::$table_name . " (";
    $sql .= join(", ", array_keys($attributes));
    $sql .= ") VALUES (" . sql_question_string($attributes);
    $sql .= ")";

    $result_set = $database->query($sql, array_values($attributes));
    if ($result_set){
      $this->id = $database->insert_id();
      return true;
    } else {
      return false;
    }
  }

  public function update(){
    global $table_name;
    global $database;

    // create attribute array without id attribute
    $attributes = $this->attribute();
    // bring the id attribute to the last for the update query
    $id_attribute = array_shift($attributes);
    $id_attribute_array = array('id'=>$id_attribute);
    $attributes = array_merge($attributes, $id_attribute_array);

    // create update sql statements
    $sql = "UPDATE " . self::$table_name . " SET ";
    $sql .= sql_update_string($attributes);
    $sql .= " WHERE id = ?";

    $result_set = $database->query($sql, array_values($attributes));
    return ($database->get_affected_rows() == 1) ? true : false;
  }

  public function delete(){
    global $table_name;
    global $database;
    $sql = "DELETE FROM " . self::$table_name . " WHERE id = ? LIMIT 1";
    $result_set = $database->query($sql, $this->id);
    return ($database->get_affected_rows() == 1) ? true : false;
  }

  private function is_all_properties_set(){
    $attributes = $this->attribute();
    unset($attributes['id']);
    foreach ($attributes as $key => $value) {
      if($key == 'created_at' || $key == 'created_at'){
        continue;
      } elseif(empty($value)){
        return false;
      }
    }
    return true;
  }

}