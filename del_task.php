<?php

require 'autoload.base.php';

$page = new Page;
try {
	$dbh = PDOBox::get();

	$del_sql = 
<<<SQL
delete from task where task_id=:task_id
SQL;
	
	$stat = $dbh->prepare($del_sql);
	$length = $stat->execute(array(
		":task_id"=>$_REQUEST['id']
	));

	if ($length > 0) {
		header("Location: index.php");

		$page->def('url', 'index.php');
		$page->template('view/done.phtml');
	} else {
		$page->def('message', 'Is there anything wrong with your input?');
		$page->template('view/error.phtml');
	}

	$dbh = null;
} catch (PDOException $e) {
	$page->def('message', $e->getMessage());
	$page->template('view/error.phtml');
}
