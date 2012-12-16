<?php

require_once 'core/autoload.base.php';

class TaskController {

  private $page;

  public function __construct() {
    $this->page = new Page();
  }

  protected function done() {
    header("Location: ../index.php");

    $this->page->def('url', '../index.php');
    $this->page->template('view/done.phtml');
  }

  protected function error($message='Unknown error!') {
    $this->page->def('message', $message);
    $this->page->template('view/error.phtml');
  }

  public function index() {
    $tasks_list = TaskHelper::getTasks('where author_id=1 order by mod_date desc');
    if ($tasks_list != null) {
      $this->page->def('tasks-list', $tasks_list);
      $this->page->template('view/index.phtml');
    }
  }

  public function editTask() {
    if (array_key_exists('id', $_REQUEST)) {
      $task = TaskHelper::getTaskById($_REQUEST['id']);
      if ($task != null) {
        $this->page->def('task', $task);
      }
      $this->page->template('view/edit_task.phtml');
    } else {
      $task = array(
        ':task_id'=>$_REQUEST['task_id'],
        ':title'=>$_REQUEST['title'],
        ':description'=>$_REQUEST['description'],
        ':progress'=>$_REQUEST['progress']
      );
      if (TaskHelper::updateTask($task)) {
        $this->done();
      } else {
        $this->error("Unknown error!");
      }
    }
  }

  public function delTask() {
    $task_id = $_REQUEST['id'];
    if (TaskHelper::removeTask($task_id)) {
      $this->done();
    } else {
      $this->error("Unknown error!");
    }
  }

  public function addTask() {
    $task = array(
      ":title"=>$_REQUEST['title'],
      ":description"=>$_REQUEST['description'],
      ":author_id"=>"1",
      ":progress"=>"0"
    );
    if (TaskHelper::addTask($task)) {
      $this->done();
    } else {
      $this->error("Unknown error!");
    }
  }
}

GtRouter::bootstrap(new TaskController);
