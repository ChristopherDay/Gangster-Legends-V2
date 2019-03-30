<?php
class glPDO extends PDO {
  function __construct($name_host, $username='', $password='', $driverOptions=array()) {
    $driverOptions[PDO::MYSQL_ATTR_USE_BUFFERED_QUERY] = true;
    $driverOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
    parent::__construct($name_host, $username, $password, $driverOptions);
  }

  function update($query, $params = array()) {
  	$q = parent::prepare($query);
  	foreach ($params as $key => $value) {
  		$q->bindParam($key, $value);
  	}
  	$q->execute();
  	$q->closeCursor();
  	unset($q);
  }  

  function delete($query, $params = array()) {
  	$q = parent::prepare($query);
  	foreach ($params as $key => $value) {
  		$q->bindParam($key, $value);
  	}
  	$q->execute();
  	$q->closeCursor();
  	unset($q);
  }  

  function insert($query, $params = array()) {
  	$q = parent::prepare($query);
  	foreach ($params as $key => $value) {
  		$q->bindParam($key, $value);
  	}
  	$q->execute();
  	$q->closeCursor();
  	unset($q);
  	return parent::lastInsertId();
  }  

  function select($query, $params = array()) {
  	$q = parent::prepare($query);
  	foreach ($params as $key => $value) {
  		$q->bindParam($key, $value);
  	}
  	$q->execute();
  	$result = $q->fetchAll(PDO::FETCH_ASSOC);
  	$q->closeCursor();
  	unset($q);
  	if (count($result)) return $result[0];
  	return array();
  }

  function selectAll($query, $params = array()) {
  	$q = parent::prepare($query);
  	foreach ($params as $key => $value) {
  		$q->bindParam($key, $value);
  	}
  	$q->execute();
  	$result = $q->fetchAll(PDO::FETCH_ASSOC);
  	$q->closeCursor();
  	unset($q);
  	return $result;
  }
}