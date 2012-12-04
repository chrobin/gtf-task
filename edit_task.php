<?php

require 'autoload.class.php';
require_once 'task.func.php';

function submit() {
	try {
		$dbh = PDOBox::get();

		$update_sql = <<<SQL
	update task 
	set title=:title, description=:description, mod_date=datetime(), progress=:progress
	where task_id=:task_id
SQL;
		$stat = $dbh->prepare($update_sql);
		$length = $stat->execute(array(
			':task_id'=>$_REQUEST['task_id'],
			':title'=>$_REQUEST['title'],
			':description'=>$_REQUEST['description'],
			':progress'=>$_REQUEST['progress']
		));

		if ($length > 0) {
			header("Location: index.php");
		} else {
			echo "Error!";
		}
	} catch (PDOException $e) {
		echo "Error!!";
	}
}

if (array_key_exists('id', $_REQUEST)) {
	$page = new Page();
	$task = getTaskById($_REQUEST['id']);
	if ($task != null) {
		$page->def('task', $task);
	}
	$page->template('view/edit_task.phtml');
} else {
	submit();
}

