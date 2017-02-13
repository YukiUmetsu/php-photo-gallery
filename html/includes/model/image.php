<?php
require_once(LIB_PATH.DS.'database'.DS.'database.php');
require_once(LIB_PATH.DS.'model'.DS.'user.php');

class Image{
  protected static $table_name = "images";
  protected static $db_fields = array('id', 'filename', 'type', 'size', 'caption', 'user_id');

  public $id;
  public $filename;
  public $type;
  public $size;
  public $caption;
  public $user_id;

  private $tmp_path;
  protected $upload_dir = "images";
  protected $upload_errors = array(
    UPLOAD_ERR_OK         => "No errors.",
    UPLOAD_ERR_INI_SIZE   => "Larger than upload_max_filesize.",
    UPLOAD_ERR_FROM_SIZE  => "Larger than form max file size",
    UPLOAD_ERR_PARTIAL    => "partial upload.",
    UPLOAD_ERR_NO_FILE    => "No file.",
    UPLOAD_ERR_NO_TMP_DIR => "No temporary directory",
    UPLOAD_ERR_CANT_WRITE => "Can't write to disk",
    UPLOAD_ERR_EXTENSION  => "File upload stopped by extension"
    );
  public $errors = array();

  public function image_path(){
    return DS.'public'.DS.$this->upload_dir.DS.$this->filename;
  }

  public function attach_file($file){

    if(!$file || empty($file) || !is_array($file)){
      // check form parameters
      $this->errors[] = "No file was uploaded";
      return false;

    } else if($file['error'] != 0){
      // error report what php went wrong.
      $this->errors[] = $this->upload_errors[$file['error']];
      return false;
    }

    $this->tmp_path       = $file['tmp_name'];
    // add time at the end of the filename
    $this->filename       = strftime("%Y%m%d-%H%M", time()) . "_" . htmlspecialchars(basename($file['name']));
    $this->type           = $file['type'];
    $this->size           = $file['size'];
    $this->user_id  = $_SESSION['user_id'];
    return true;
  }

  public function save(){

    // A new record won't have an id yet.
    if(isset($this->id)){
      //update the caption
      $this->update();
    } else {

      if(!empty($this->errors)){ return false; }

      // check caption size
      if(strlen($this->caption) > 255){
        $this->errors[] = "The caption can only be 255 characters long";
        return false;
      }

      // check filename size
      if(strlen($this->filename) > 255){
        $this->errors[] = "The filename can only be 200 characters long";
        return false;
      }

      // can't save without filename and tmp location
      if(empty($this->filename) || empty($this->tmp_path)){
        $this->errors[] = "The file location was not available.";
        return false;
      }

      $target_path = SITE_ROOT.DS.'public'.DS.$this->upload_dir.DS.$this->filename;

      // check if file already exists
      if (file_exists($target_path)){
        $this->errors[] = "The file already exists";
        return false;
      }

      // attempt to move the file
      if(move_uploaded_file($this->tmp_path, $target_path)){
        // Moving the file Success!
        if($this->create()){
          unset($this->tmp_path); // erase tmp_path because the file is not there anymore
          return true;
        }
        $this->errors[] = "file was saved in the server but create() failed";
      } else {
        // the file was not moved
        $this->errors[] = "The file uploaded failed, possibly due to incorrect permissions";
        return false;
      }
    }
  }

  public static function find_all(){
    global $table_name;
    $photos = self::find_by_sql("SELECT * FROM " . self::$table_name);

    // create a new image instance and store it in an array
    return self::create_objects_from_array($photos);
  }

  public static function count_all(){
    global $table_name;
    $photos = self::find_by_sql("SELECT * FROM " . self::$table_name);

    // create a new image instance and store it in an array
    return count(self::create_objects_from_array($photos));
  }

  public static function get_photos_with_pagination($limit, $offset){
    global $table_name;
    $sql = "SELECT * FROM " . self::table_name . " LIMIT ? OFFSET ?";
    $params = array($limit, $offset);
    $results = self::find_by_sql($sql, $params);
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

  public function destroy(){
    // delete from the database
    if($this->delete()){
      $target_path = SITE_ROOT.DS.'public'.DS.$this->upload_dir.DS.$this->filename;
      // delete file from the server
      return unlink($target_path) ? true : false;
    } else {
      return false;
    }
  }

  public static function find_all_by_user_id($user_id){
    global $database;
    $sql = "SELECT * FROM " . self::$table_name . " WHERE user_id = ?";
    $results = $database->query($sql, $user_id);
    return self::create_objects_from_array($results);
  }

  public static function get_current_user_photos(){
    if(!empty($this->user_id)){
      $photos = self::find_all_by_user_id($this->user_id);
      return $photos;
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

  public function get_comments(){
    return Comment::find_comments_by_photo_id($this->id);
  }
}