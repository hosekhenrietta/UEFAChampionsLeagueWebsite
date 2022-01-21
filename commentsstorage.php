<?php
include('storage2.php');

class CommentsStorage extends Storage2 {
  public function __construct() {
    parent::__construct(new JsonIO2('comments.json'));
  }
}
