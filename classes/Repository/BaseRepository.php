<?php

namespace Plu\Repository;

use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class BaseRepository {

  private $db;
  protected $tableName;
  protected $converter;

  public function __construct($db, $converter, $tableName) {
    $this->db = $db;
    $this->tableName = $tableName;
    $this->converter = $converter;
  }

  public function findByIdentifier($id) {
    $sql = "SELECT * FROM $this->tableName WHERE id = ?";
    $row = $this->db->fetchAssoc($sql, array((int) $id));
    if(!$row) {
        throw new ResourceNotFoundException("Could not find $id in $this->tableName");
    }
    return $this->converter->fromDB($this->tableName, $row);
  }

  public function add($obj) {
      $this->db->insert($this->tableName, $this->converter->toDB($obj));
      $id = $this->db->lastInsertId();
      $obj->id = $id;
      return $obj;
  }

  public function update($obj) {
      $this->db->update($this->tableName, $this->converter->toDB($obj), ['id' => $obj->id]);
      return $obj;
  }

  public function remove($obj) {
      $this->db->delete($this->tableName, ['id' => $obj->id]);
      return null;
  }

  public function createNew() {
      return $this->converter->fromVoid($this->tableName);
  }

}

