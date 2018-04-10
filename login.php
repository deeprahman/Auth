<?php
include_once 'resource/session.php';
include_once 'resource/Database.php';
include_once 'resource/utilities.php';

if (isset($_POST['loginBtn'])) {
    // Array to hold errors
    $form_errors = array();
    // Validate
    $required_fields = array('username', 'password');
    $form_errors = array_merge($form_errors, check_empty_fields($required_fields));

    if (empty($form_errors)) {
        //Collect form data
        $user = $_POST['username'];
        $password = $_POST['password'];

        // Check if user exist in database
        $sqlQuery = "SELECT * FROM users WHERE username = :username";
        $statement = $db->prepare($sqlQuery);
        $statement->execute(array(':username' => $user));
        while ($row = $statement->fetch()) {
            $id = $row['id'];
            $hashed_password = $row['password'];
            $username = $row['username'];
            if (password_verify($password, $hashed_password)) {
                $_SESSION['id'] = $id;
                $_SESSION['username'] = $username;
                header("location:index.php");
            } else {
                $result = flashMessage("Invalid username or password");
            }
        }
    } else {
        if(count($form_errors) == 1){
            $result = flashMessage("There was an error in the form");
        }else{
            $result = flashMessage("There were " .count($form_errors). " error in the form");
        }
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Login Page</title>
    </head>
    <body>
        <h2>User Authentication System</h2>
        <h3>Login Form</h3>
<?php if (isset($result)) echo $result; ?>
<?php if (!empty($form_errors)) echo show_errors($form_errors); ?>
        <form method="post" action="">
            <table>
                <tr><td>Username:</td><td><input type="text" value="" name="username"></td></tr>
                <tr><td>Password:</td><td><input type="password" value="" name="password"></td></tr>
                <tr><td><a href="forgot_password.php">Forgot Password?</a></td><td><input type="submit" style="float:right;" name="loginBtn" value="Signin"></td></tr>
            </table>
        </form>
        <p><a href="index.php">Back</a></p>
    </body>
</html>
