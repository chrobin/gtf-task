<?php

require '../autoload.base.php';

$create_sql = <<<SQL
drop table task;
create table task(
  task_id integer primary key autoincrement, 
  title text not null,
  description text,
  author_id integer,
  cre_date date,
  mod_date date,
  progress integer
);
create table author(
  author_id integer not null,
  name text not null,
  email text not null,
  primary key(author_id)
);
SQL;

try {
  $dbh = PDOBox::get();
  $dbh->exec($create_sql);
  $dbh = null;

  echo "Done!";
} catch (PDOException $e) {
  echo "Error!";
}
