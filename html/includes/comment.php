<?php
require_once(LIB_PATH.DS.'database'.DS.'database.php');

/**
* class for comments
*/
class Comment
{
  protected static $table_name = "comments";
  protected static $db_fields = array('id', 'photo_id', 'author', 'body', 'created_at');

  public $id;
  public $photo_id;
  public $user_id;
  public $body;
  public $created_at;

  public static function make($photo_id, $author="Anonymous", $body=""){
    if(!empty($photo_id) && !empty($author) && !empty($body)){
      $comment = new Comment();
      $comment->photo_id = (int)$photo_id;
      $comment->author = $author;
      $comment->body = $body;
      $comment->created_at = strftime("%Y-%m-%d %H:%M:%S", time());
      return $comment;
    } else {
      return false;
    }
  }

  public static function find_comments_by_photo_id($photo_id=1){
    global $database;
    $sql = "SELECT * FROM " . self::$table_name . " WHERE photo_id = ? ORDER BY created_at ASC";
    $results = self::find_by_sql($sql, $photo_id);
    // create a new image instance and store it in an array
    return self::create_objects_from_array($results);
  }

  public static function find_all(){
    global $table_name;
    $results = self::find_by_sql("SELECT * FROM " . self::$table_name);

    // create a new image instance and store it in an array
    return self::create_objects_from_array($results);
  }

  // return class instance from db instance
  private static function create_objects_from_array($array){
    $obj_array = array();
     // create a new instance and store it in an array
    foreach ($array as $db_obj) {
      $obj = new self();
      // create each properties from db instance
      // ex) $obj->id = $db_obj->id
      foreach (self::$db_fields as $field) {
        $obj->$field = $db_obj->$field;
      }
      $obj_array[]   = $obj;
    }
    return $obj_array;
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
      if(empty($value)){
        return false;
      }
    }
    return true;
  }

  public static function find_all_by_user_id($user_id){
    global $database;
    $sql = "SELECT * FROM " . self::table_name . " WHERE user_id = ?";
    $results = $database->query($sql, $user_id);
    return self::create_objects_from_array($results);
  }

  public static function get_current_user_comments(){
    if(!empty($this->user_id)){
      $results = self::find_all_by_user_id($this->user_id);
      return $results;
    } else {
      return "user not found";
    }
  }

  public function get_username(){
    if(!empty($this->user_id)){
      $user = User::find_by_id($this->user_id);
      return $user->username;
    } else {
      return "user not found";
    }
  }
}