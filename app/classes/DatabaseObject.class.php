<?php

class DatabaseObject
{

  static protected $database;
  static protected $table_name = "";
  static protected $db_columns = [];
  public $errors = [];


  static public function set_database($database)
  {
    self::$database = $database;
  }

  static public function pdo_query($sql)
  {
    //Executes an SQL statement, returning a result set as a PDOStatement object
    $stmt = self::$database->query($sql);
    return $stmt;
  }

  static public function pdo_excec($sql)
  {
    //Execute an SQL statement and return the number of affected rows
    try {
      return self::$database->exec($sql);
    } catch (PDOException $e) {
      //echo $sql . "<br>" . $e->getMessage();
      return (false);
    }
    return (false);
  }

  static public function pdo_excecute_ps($sql, $conditions, $parameters, $limit = 0)
  {
    //Executes a prepared statement
    try {
      if (!empty($conditions)) {
        $sql .= " WHERE " . implode(" AND ", $conditions);
      }
      $sql .= " ORDER BY created_at DESC";
      if ($limit != 0) {
        $sql .= " LIMIT " . strval($limit);
      }
      // echo $sql;
      // var_dump($parameters);
      if (!empty($parameters)) {
        $stmt = self::$database->prepare($sql);
        $stmt->execute($parameters);
        $res = $stmt->fetchAll();
        if (!$res) return (false);
        return ($res);
      }
      return (false);
    } catch (PDOException $e) {
      //echo $sql . "<br>" . $e->getMessage();
      return (false);
    }
  }

  static public function get_where($fieldname, $value)
  {
    $sql = "SELECT * FROM " . static::$table_name . " ";
    $obj_array = static::pdo_excecute_ps($sql, [$fieldname . ' LIKE ?'], [$value],0);
    if (!empty($obj_array)) {
      return array_shift($obj_array);
    } else {
      return false;
    }
  }

  static public function get_all_where($fieldname, $value, $limit=0)
  {
    $sql = "SELECT * FROM " . static::$table_name . " ";
    $obj_array = static::pdo_excecute_ps($sql, [$fieldname . ' LIKE ?'], [$value],$limit);
    if (!empty($obj_array)) {
      return $obj_array;
    } else {
      return false;
    }
  }

  static public function get_where_conditions($conditions, $parameters)
  {
    $sql = "SELECT * FROM " . static::$table_name . " ";
    $obj_array = static::pdo_excecute_ps($sql, $conditions, $parameters,0);
    if (!empty($obj_array)) {
      return array_shift($obj_array);
    } else {
      return false;
    }
  }


  protected function validate()
  {
    $this->errors = [];

    // Add custom validations


    return $this->errors;
  }


  // Properties which have database columns, excluding ID
  public function attributes($include_id = 1)
  {
    $attributes = [];
    $skip_fields = ['created_at'];
    if ($include_id)
      $skip_fields[] = 'id';
    foreach (static::$db_columns as $column) {
      if (in_array($column, $skip_fields)) {
        continue;
      }
      $attributes[$column] = $this->$column;
    }
    return $attributes;
  }



  protected function create()
  {
    //die("database object create");
    // $this->validate();
    // if (!empty($this->errors)) {
    //   return false;
    // }

    $attributes = $this->attributes(0);
    $new_attrs  = [];
    foreach ($attributes as $key => $val) {
      $new_attrs[$key] = ":" . $key;
    }

    $sql = "INSERT INTO " . static::$table_name . " (";
    $sql .= join(', ', array_keys($new_attrs));
    $sql .= ") VALUES (";
    $sql .= join(", ", array_values($new_attrs));
    $sql .= ")";

    //echo $sql;
    //die($sql);
    return self::$database->prepare($sql)->execute($attributes);
  }



  protected function update()
  {
    //die("database object update");
    // $this->validate();
    // if(!empty($this->errors)) { return false; }

    $attributes = $this->attributes(1);
    $new_attrs  = [];
    $sql_attrs = " ";
    foreach ($attributes as $key => $val) {
      $new_attrs[] = $key . "=:" . $key;
    }
    $sql_attrs = join(', ', $new_attrs);

    //die($this->id);
    $attributes += ["id" => $this->id];
    $sql_attrs .= " WHERE id=:id";

    $sql = "UPDATE " . static::$table_name . " SET " .  $sql_attrs;

    //echo $sql;
    // print_r( $attributes);
    // die($sql);
    return self::$database->prepare($sql)->execute($attributes);
  }

  public function save()
  {
    // // A new record will not have an ID yet
    if (isset($this->id)) {
      return $this->update();
    } else {
      return $this->create();
    }
  }


  public function delete()
  {
    $sql = "DELETE FROM " . static::$table_name . " ";
    $sql .= "WHERE id=" . $this->id . " ";
    $sql .= "LIMIT 1";
    $result = static::pdo_excec($sql);
    return $result;
  }

  public static function delete_where($conditions, $parameters)
  {
    $sql = "DELETE FROM " . static::$table_name . " ";
    if (!empty($conditions)) {
      $sql .= " WHERE " . implode(" AND ", $conditions);
    }
    $stm = self::$database->prepare($sql);
    $stm->execute($parameters);
    return $stm->rowCount();
  }



  static public function instantiate($record)
  {
    if (empty($record))
      return null;
    $object = new static;
    // Could manually assign values to properties
    // but automatically assignment is easier and re-usable
    foreach ($record as $property => $value) {
      if (property_exists($object, $property)) {
        $object->$property = $value;
      }
    }
    return $object;
  }

  static public function get_all()
  {
    $sql = "SELECT * FROM " . static::$table_name;
    $sql .= " ORDER BY created_at DESC";
    return static::pdo_query($sql);
  }

  static public function get_limited($limit = 0)
  {
    $sql = "SELECT * FROM " . static::$table_name;
    $sql .= " ORDER BY created_at DESC";
    if ($limit != 0) {
      $sql .= " LIMIT " . strval($limit);
    }
    return static::pdo_query($sql);
  }


  static public function count_all()
  {
    $sql = "SELECT * FROM " . static::$table_name;
    $result = static::pdo_query($sql);
    return $result->rowCount();
  }

  static public function count_where($conditions, $parameters)
  {
    $sql = "SELECT count(*) FROM " . static::$table_name;
    if (!empty($conditions)) {
      $sql .= " WHERE " . implode(" AND ", $conditions);
    }
    $stm = self::$database->prepare($sql);
    $stm->execute($parameters);
    return $stm->fetchColumn();
  }
}
