<?php
include('usersstorage.php');
function teambyID($id){   
    global $teams;
    foreach($teams as $team){
    if ($team["id"] == $id) {
      return $team["name"];
    }
  }
    return 'This id does not exist';
}
function validateregister($post, &$data, &$errors) {
    //name
    if (!isset($post['name'])) {
        $errors['name'] = 'Username is required';
    } else if (trim($post['name']) === '') {
        $errors['name'] = 'Username is required';
    } else {
        $data['name'] = $post['name'];
    }
    //email
    if (!isset($post['email'])) {
        $errors['email'] = 'Email address is required';
    } else if (trim($post['email']) === '') {
        $errors['email'] = 'Email address is required';
    } else if (strpos($post['email'], "@")) { 
        $errors['email'] = 'The email address is not valid';
    } else{
        $data['email'] = $post['email'];
    }
    //password 1
    $validpsw1 = false;
    if (!isset($post['password1'])) {
        $errors['password1'] = 'Password is required';
    } else if (trim($post['password1']) === '') {
        $errors['password1'] = 'Password is required';
    } else {
        $data['password1'] = $post['password1'];
        $validpsw1 = true;
    } 
    //password 2
    if ($validpsw1) {
        if (!isset($post['password2'])) {
            $errors['password2'] = 'Confirm the password';
        }
        else if (trim($post['password2']) === '') {
            $errors['password2'] = 'Confirm the password';
        }
        else if ($post['password1'] == $post['password2']) {
            $data['password2'] = $post['password2'];
            $data['password'] = password_hash($post['password1'], PASSWORD_DEFAULT);
        } else {
            $errors['password2'] = 'Passwords do not match';
            $data['password2'] = $post['password2'];
        }    
    }
}

function register($data, &$usersStorage){
    $usersStorage->add($data);
    header('Location: login.php?succesfulregistration=true');
}
$errors=[
    'name'=> '',
    'email'=> '',
    'password1'=> '',
    'password2'=> ''
];
$data=[
    'name'=> '',
    'email'=> '',
    'password1'=> '',
    'password2'=> '',
    'password' => ''
];
$usersStorage = new UsersStorage();
$users = $usersStorage->findAll();
$post = $_POST;
$sucess = false;
if ((sizeof($post) > 0)) {
    validateregister($post, $data, $errors);
    $err = $errors['name'] . $errors['email'] . $errors['password1'] . $errors['password2'];
    $sucess = (file_exists('users.json') && strlen($err) == 0);
    if ($sucess) {
        register(['id'=> 'userid'.sizeof($users),
                    'username'=> $data['name'],
                    'email'=> $data['email'],
                    'password'=> $data['password']],
                     $usersStorage);            
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
    <form class="form-login" action="register.php" method="post" novalidate>
        <h1>Register</h1>
        <div class="card px-5 py-5">
            <div class="form-input"> <input type="text" class="form-control" placeholder="user name" name="name" value="<?= $data['name'] ?>"> <span class ="error"><?= $errors['name'] ?></span></div>
            <div class="form-input"> <input type="text" class="form-control" placeholder="email" name="email" value="<?= $data['email'] ?>"> <span class ="error"><?= $errors['email'] ?></span></div>
            <div class="form-input"> <input type="password" class="form-control" placeholder="password" name="password1" value="<?= $data['password1'] ?>"><span class ="error"><?= $errors['password1'] ?></span></div>
            <div class="form-input"> <input type="password" class="form-control" placeholder="password again" name="password2" value="<?= $data['password2'] ?>"><span class ="error"><?= $errors['password2'] ?></span></div>

            <button type="submit" class="send" >register</button>
            <div class="text-center mt-4"> <span>Already a member?</span> <a href="login.php" class="text-decoration-none">Log in here</a> </div>           
        </div>
    </form>
</body>
</html>