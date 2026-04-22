<?php

require_once '/vendor/autoload.php';


session_start();
function login():void 
{
    $error = [];
if(isset($_POST['login'])){
    $email = $_POST['email'];
    $password = $_POST['password'];
    $db = new \App\DB();
    if(empty($email)){
        array_push($error, "email is required");
    }
    if(empty($password)){
        array_push($error, "Password is required");
    }
    if(count($error) == 0){
    $query = "SELECT * FROM users WHERE email=?";
    $prepareStmt = $db->connection->prepare($query);
    $prepareStmt->bind_param('s', $email);
    $prepareStmt->execute();
    $resultobj = $prepareStmt->get_result();
    if($resultobj->num_rows == 0){
    \App\Alert::printMessage("The email you entered is incorrect","danger");
    return;
    }
    $rowArr= $resultobj->fetch_assoc();
    $hashedPassword = $rowArr['password'];
        if (!password_verify($password, $hashedPassword)) {
                \App\Alert::printMessage("Wrong password", "danger");
                return;
        }
        $name = $rowArr['name'];
        $_SESSION['userID'] = $rowArr['id'];
        $_SESSION['userName'] = $name;
        \App\Alert::printMessage("Welcome you are logged in, $name", "success");
        header('Location: index.php');
        exit;
}
}
}
