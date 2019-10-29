<?php
class glPDO extends PDO {
  function __construct($name_host, $username='', $password='', $driverOptions=array()) {
    //$driverOptions[PDO::MYSQL_ATTR_USE_BUFFERED_QUERY] = true;
    $driverOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
    parent::__construct($name_host, $username, $password, $driverOptions);
  }

  function runQuery($query, $params = array()) {
  	$q = parent::prepare($query);
  	$q->execute($params);
  	$q->closeCursor();
  	unset($q);
  }

  function update($query, $params = array()) {
  	$this->runQuery($query, $params);
  }  

  function delete($query, $params = array()) {
  	$this->runQuery($query, $params);
  }  

  function insert($query, $params = array()) {
  	$this->runQuery($query, $params);
  	return parent::lastInsertId();
  }  

  function select($query, $params = array()) {
  	$q = parent::prepare($query);
  	$q->execute($params);
  	$result = $q->fetch(PDO::FETCH_ASSOC);
  	$q->closeCursor();
  	unset($q);
	return $result;
  	
  }

  function selectAll($query, $params = array()) {
  	$q = parent::prepare($query);
  	$q->execute($params);
  	$result = $q->fetchAll(PDO::FETCH_ASSOC);
  	$q->closeCursor();
  	unset($q);
  	return $result;
  }
}
