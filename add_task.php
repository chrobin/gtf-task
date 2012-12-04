<?php

require 'autoload.base.php'
require_once 'page.class.php';

$page = new Page;
try {
	$dbh = PDOBox::get();

	$add_sql = 
<<<SQL
insert into task values(null,:title,:description,:author_id,datetime(),datetime(),:progress)
SQL;
	
	$stat = $dbh->prepare($add_sql);
	$length = $stat->execute(array(
		":title"=>$_REQUEST['title'],
		":description"=>$_REQUEST['description'],
		":author_id"=>"1",
		":progress"=>"0"
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
