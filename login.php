<?php
include('usersstorage.php');

function exists($varname){
    return isset($_GET[$varname]) && strlen(trim($_GET[$varname])) > 0;
}

function validatelogin($post, &$data, &$errors, $users) {
    //name
    if (!isset($post['name'])) {
        $errors['name'] = 'Username is required';
    }
    else if (trim($post['name']) === '') {
        $errors['name'] = 'Username is required';
    }
    else {
        $data['name'] = $post['name'];
    }
    //password
    if (!isset($post['password'])) {
        $errors['password'] = 'Password is required';
    }
    else if (trim($post['password']) === '') {
        $errors['password'] = 'Password is required';
    }
    else {
        $data['password'] = $post['password'];
    }
  $validpassword = false;
  $found = false;
  //username - password
    if ($errors['name'] =='') {
        $counter = 0;
        foreach ($users as $user) {
            if ($user['username'] == $data['name']) {
                $found = true;
            }
            if ($found && $errors['password'] =='') {
                $validpassword = password_verify($data['password'], $user['password']);
                break;
            }
        }
        if (!$found) {
            $errors['name'] = 'This username does not exist.';
        } elseif (!$validpassword) {
            $errors['password'] = 'This password does not valid.';
            }   
    }
    return ($found && $validpassword);
}

session_start();

function login($data){
$_SESSION['username'] = $_POST['name'];
    header('Location: index.php');
}
$errors=[
    'name'=> '',
    'password'=> ''
];
$data=[
    'name'=> '',
    'password' => ''
];
$post = $_POST;

$usersStorage = new UsersStorage();
$users = $usersStorage->findAll();
$succesfulregistration = exists('succesfulregistration');

if ((sizeof($post) > 0)) {
    $succes = validatelogin($post, $data, $errors, $users);
    if ($succes) {
        login($data);
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <button type="button" onclick="window.location.href='index.php'">Home</button>
    <h2><?= $succesfulregistration ? "Your registration was succesful. Please log in!" : "" ?></h2>
    <form class="form-login" novalidate action="login.php" method="post">
        <h1>Log In</h1>
        
        <div class="card px-5 py-5">
        <div class="form-input"> <input type="text" class="form-control" placeholder="user name" name="name" value="<?= $data['name'] ?>"><span class ="error"><?= $errors['name'] ?></span></div>
        <div class="form-input"> <input type="password" class="form-control" placeholder="password" name="password" value="<?= $data['password'] ?>"><span class ="error"><?= $errors['password'] ?></span></div>

        <button type="submit" class="send" >log in</button>
        <div class="text-center mt-4"> <span>Not a member?</span> <a href="register.php" class="text-decoration-none">Register here</a> </div>
                    
        </div>
    </form>

</body>
</html>