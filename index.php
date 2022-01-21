<?php
include_once('data.php');

function exists($varname){
    return isset($_GET[$varname]) && strlen(trim($_GET[$varname])) > 0;
}
function teambyID($id){   
    global $teams;
    foreach($teams as $team){
        if ($team["id"] == $id) {
            return $team["name"];
        }
    }
    return 'This id does not exist';
}

$var = ' ';
if (exists('var')) {
    $var = $_GET['var'];
}

usort($matches, function($a1, $a2) {
    $v1 = strtotime($a1['date']);
    $v2 = strtotime($a2['date']);
    return $v2 - $v1;
    });

 session_start();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="widtd=device-widtd, initial-scale=1.0">
    <title>Teams</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <button type="button" id="allmbtn" onclick="window.location.href='?var=allmatches'">All matches</button>
    <button type="button" id="meccsbtn"  onclick="window.location.href='?var=last5'">Last 5 matches</button>
    <button type="button" class="log" onclick="window.location.href='<?= isset($_SESSION['username']) ? 'logout.php' : 'login.php' ?>'"><?= isset($_SESSION['username']) ? "Log out" : "Log In" ?></button>
    <button type="button" class="log" onclick="window.location.href='<?= isset($_SESSION['username']) ? '' : 'register.php' ?>'"><?= isset($_SESSION['username']) ? "Hello ".$_SESSION['username'] : "Registser" ?></button>

    <h1>UEFA Champions League</h1>
    <p class="titilep">An unofficial site of the world's greatest club competition. </p>


    <?php foreach($teams as $team) : ?>
        <button type="button" class="teambtn"  onclick="window.location.href='teams.php?id=<?= $team['id'] ?>'"><?= $team['name'] ?></button>  
    <?php endforeach ?>

    <table>
    <?php if($var == 'last5') :
        $counter = 0;  

        foreach($matches as $match) : 
        $counter += 1;
        if($counter <= 5) : 
    ?>
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
    <?php endif ?>
    </table>

    <table>
        <?php if($var == 'allmatches') : ?>
        <?php foreach($matches as $match) : ?>
            <tr>
                <td class = "date"> <?= $match['date'] ?> </td>
            </tr>
            <tr>
                <td class= <?= $match['home']['score'] == $match['away']['score'] ? "draw" : ($match['home']['score'] > $match['away']['score'] ? "winner" : "loser")?>> <?= teambyID($match['home']['id']) ?> </td>
                <td class= <?= $match['home']['score'] == $match['away']['score'] ? "draw" : ($match['home']['score'] > $match['away']['score'] ? "winner" : "loser")?> > <?= $match['home']['score'] ?> </td>
                <td class=<?= $match['home']['score'] == $match['away']['score'] ? "draw" : ($match['home']['score'] < $match['away']['score'] ? "winner" : "loser")?>> <?= $match['away']['score'] ?> </td>
                <td class= <?= $match['home']['score'] == $match['away']['score'] ? "draw" : ($match['home']['score'] < $match['away']['score'] ? "winner" : "loser")?>> <?= teambyID($match['away']['id']) ?> </td>
            </tr>

        <?php endforeach ?>
        <?php endif ?>
    </table>

</body>
</html>