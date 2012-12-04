<?php

require 'autoload.base.php';

function getTaskByID($task_id) {
	$sql = 
<<<SQL
select * from task where task_id=:task_id
SQL;
	try {
		$dbh = PDOBox::get();
		$stat = $dbh->prepare($sql);
		$stat->bindParam(':task_id', $task_id, PDO::PARAM_INT);
		$stat->execute();
		$rs = $stat->fetch();
		
		$dbh = null;
		return $rs;
	} catch(PDOException $e) {
		echo 'Error!!';
	}
	return null;
}

function getTasks($sql_cond=null, array $param=null) {
	$tasks_list = array();
	$sql = 
<<<SQL
select * from task $sql_cond
SQL;
	try {
		$dbh = PDOBox::get();
		$stat = $dbh->prepare($sql);
		$stat->execute($param);

		while ($row = $stat->fetch()) {
			$tasks_list[] = $row;
		}
	} catch(PDOException $e) {
		echo 'Error!!';
	}
	return $tasks_list;
}
