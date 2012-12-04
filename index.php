<?php

require 'autoload.base.php';
require_once 'task.func.php';

class TaskController {

  protected function done($page) {
    header("Location: ../index.php");

    $page->def('url', '../index.php');
    $page->template('view/done.phtml');
  }

  protected function error($page, $message='Unknown error!') {
    $page->def('message', $message);
    $page->template('view/error.phtml');
  }

  public function index() {
    $page = new Page();

    try {
      $dbh = PDOBox::get();

      $tasks_list = getTasks('where author_id=1 order by mod_date desc');
      $page->def('tasks-list', $tasks_list);

    } catch (PDOException $e) {
      $this->error($page, $e->getMessage());
    }

    $page->template('view/index.phtml');
  }

  public function editTask() {
    if (array_key_exists('id', $_REQUEST)) {
      $page = new Page();
      $task = getTaskById($_REQUEST['id']);
      if ($task != null) {
        $page->def('task', $task);
      }
      $page->template('view/edit_task.phtml');
    } else {
      try {
        $dbh = PDOBox::get();

        $update_sql =
<<<SQL
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
          $this->done($page);
        } else {
          $this->error($page, "Unknown error!");
        }
      } catch (PDOException $e) {
        $this->error($page, $e->getMessage());
      }
    }
  }

  public function delTask() {
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
        $this->done();
      } else {
        $this->error($page, 'Is there anything wrong with your input?');
      }

      $dbh = null;
    } catch (PDOException $e) {
      $this->error($page, $e->getMessage());
    }
  }

  public function addTask() {
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
        $this->done($page);
      } else {
        $this->error($page, 'Unknown error!');
      }

      $dbh = null;
    } catch (PDOException $e) {
      $this->error($page, $e->getMessage());
    }
  }
}

GtRouter::bootstrap(new TaskController);
