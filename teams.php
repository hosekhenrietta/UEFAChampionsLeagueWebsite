<?php
include_once('data.php');
include('usersstorage.php');
include('commentsstorage.php');
function teambyID($id){   
  global $teams;
  foreach($teams as $team){
  if ($team["id"] == $id) {
    return $team["name"];
  }
}
  return 'This id does not exist';
}

function exists($varname){
  return isset($_GET[$varname]) && strlen(trim($_GET[$varname])) > 0;
}
session_start();

$teamID = '';
if (exists('id')) {
    $teamID = $_GET['id'];
} else{
    $teamID = 'teamid1';
}

$myTeam = [
    'id' => '',
    'name' => '',
    'country' => ''
];

foreach ($teams as $team ) {
    if ($team["id"] == $teamID) {
        $myTeam["id"] = $team["id"];
        $myTeam["name"] = $team["name"];
        $myTeam["country"] = $team["country"];
      }
}
$usersStorage = new UsersStorage();
$users = $usersStorage->findAll();
$commentsStorage = new CommentsStorage();
$comments = $commentsStorage->findAll();
//new comment
$newcommenttext ='';
$post = $_POST;


$error ='';

if ((sizeof($post) > 0)) {

  if (!isset($post['newcomment'])) {
    $error = 'If you want to send a comment, you should write something first :)';
  }
  else if (trim($post['newcomment']) === '') {
    $error = 'If you want to send a comment, you should write something first :)';
  }
  else {
    $newcommenttext= $post['newcomment'];
  }


  if(file_exists('comments.json') && strlen($error) == 0)
  {

    $userid = '';
    foreach ($users as $user) {
        if ($user['username'] == $_SESSION['username']) {
            $userid = $user['id'];
        }
    }


      $newcomment = [
        "author"=> $userid,
        "text"=> $newcommenttext,
        "teamid"=> $teamID,
        "date"=> date("Y-m-d")
      ];

      $commentsStorage->add($newcomment);

      header('Location: teams.php?id='.$teamID);
  }


}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $myTeam['name'] ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<button type="button" onclick="window.location.href='index.php'">Home</button>
<button type="button" class="log" onclick="window.location.href='<?= isset($_SESSION['username']) ? 'logout.php' : 'login.php' ?>'"><?= isset($_SESSION['username']) ? "Log out" : "Log In" ?></button>
    <button type="button" class="log" onclick="window.location.href='register.php'"><?= isset($_SESSION['username']) ? "Hello ".$_SESSION['username'] : "Register" ?></button>


<h1><?= $myTeam['name'] ?></h1>
<h4><?= $myTeam['country'] ?></h4>


<h1> Matches </h1>

<table>

<?php 
    foreach($matches as $match) :
    if ($match["home"]["id"]==$myTeam["id"] || $match["away"]["id"]==$myTeam["id"]) : ?>
        <tr>
      <td class= "date"> <?= $match['date'] ?> </td>
        </tr>
        <tr>
      <td class= <?= $match['home']['score'] == $match['away']['score'] ? "draw" : ($match['home']['score'] > $match['away']['score'] ? "winner" : "loser")?>> <?= teambyID($match['home']['id']) ?> </td>
      <td class= <?= $match['home']['score'] == $match['away']['score'] ? "draw" : ($match['home']['score'] > $match['away']['score'] ? "winner" : "loser")?> > <?= $match['home']['score'] ?> </td>
      <td class=<?= $match['home']['score'] == $match['away']['score'] ? "draw" : ($match['home']['score'] < $match['away']['score'] ? "winner" : "loser")?>> <?= $match['away']['score'] ?> </td>
      <td class= <?= $match['home']['score'] == $match['away']['score'] ? "draw" : ($match['home']['score'] < $match['away']['score'] ? "winner" : "loser")?>> <?= teambyID($match['away']['id']) ?> </td>
      </tr>

    <?php endif ?>
<?php endforeach ?>

</table>

   
<section>
    <div class="container">
        <div class="row">
            <div>
                <h1>Comments</h1>
<?php if (isset($_SESSION['username'])) : ?>
                <div class="comment"> 
                    <h4>new comment</h4><br>
                    <form class="form-comment" action="teams.php?id=<?= $myTeam['id'] ?>" method="post">
                    <input type="text" name="newcomment" class = "newcomment" placeholder="Write your comment here!" value="<?= $newcommenttext ?>">
                    <button type="submit" class="commentbtn">send comment</button> <span class="error"><?= $error ?></span>
                    </form>
                </div>
<?php endif ?>
                <?php foreach($comments as $comment) : 
                    if ($comment["teamid"]==$myTeam["id"]) : ?>
                
                <div class="comment"> <img src="pictures/profile.jpg" alt="" class="rounded-circle" width="40" height="40">
                    <h4><?= $usersStorage -> findById($comment['author'])['username'] ?></h4> <span >  <?= $comment['date'] ?></span> <br>
                    <p><?= $comment['text'] ?></p>
                </div>

                <?php endif ?>
        
                <?php endforeach ?>

                
            </div>
            
        </div>
    </div>
</section>


</body>
</html>

