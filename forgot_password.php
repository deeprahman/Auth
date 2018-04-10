<?php
// Add your database connection script.
include_once 'resource/Database.php';
include_once 'resource/utilities.php';

// Process the form if the reset password button is clicked.
if (isset($_POST['passwordResetBtn'])) {
    // Initialize an array to store any error message from the form
    $form_errors = array();
    // Form vaidation
    $required_fields = array('email', 'new_password', 'confirm_password');

    // Call the functon to check empty fields and merge the return data into $form_errors array.

    $form_errors = array_merge($form_errors, check_empty_fields($required_fields));

    // Fields that require checking for the minimum length
    $fields_to_check_length = array('new_password' => 6, 'confirm_password' => 6);

    // Call the function to check minimum required length and merge the return data into $form_errors array
    $form_errors = array_merge($form_errors, check_min_length($fields_to_check_length));

    // Email validation/merge the return data into $form_errors array.
    $form_errors = array_merge($form_errors, check_email($_POST));

    // Check if the error array is empty, if yes, process form data and insert record.
    if (empty($form_errors)) {
        // Collect form data and store in variables
        $email = $_POST['email'];
        $password1 = $_POST['new_password'];
        $password2 = $_POST['confirm_password'];
        // Check if the new password and the conformed password are the same
        if ($password1 != $password2) {
            $result = "<p style='padding:20px;border:1px solid gray; color:red'>
      The new password and the confirm password are not the same</p>";
        } else {
            try {
                // Check SQL SELECT statement to varify if email address input exist in the database.
                $sqlQuery = "SELECT email FROM users WHERE email=:email";

                // Use the PDO prepare statement to sanitize the data
                $statement = $db->prepare($sqlQuery);
                // Execute the query
                $statement->execute(array(':email' => $email));

                // Check if the record exists.
                if ($statement->rowCount() == 1) {
                    // Hash the passwordResetBtn
                    $hashed_password = password_hash($password1, PASSWORD_DEFAULT);

                    // SQL staemetn to update password
                    $sqlUpdate = "UPDATE users SET password=:password WHERE email=:email";

                    // Use PDO prepare to sanitize SQL statement
                    $statement = $db->prepare($sqlUpdate);

                    // Execute the statement
                    $statement->execute(array(':password' => $hashed_password, ':email' => $email));
                    $result = "<p style='padding:20px;border;1px solid gray; color:green;'>Password Reset Successful</p>";
                } else {
                    $result = "<p style='padding:20px; border:1px solid gray;color:green;'>
            The email address provided does not exists in our database, please try again.
          </p>";
                }
            } catch (PDOException $ex) {
                $result = "<p style='padding:20px;border:1px solid gray; color:red;'>An error occured: " . $ex->getMessage() . "</p>";
            }
        }
    } else {
        if (count($form_errors) == 1) {
            $result = "<p style='color:red'>There was an error in the form</p>";
        } else {
            $result = "<p style='color:red;'>There were " . count($form_errors) . " errors in the form</p>";
        }
    }
}
?>
<!DOCTYPE html>
<html>
    <head lang="en">
        <meta charset="utf-8">
        <title>Password Reset Page</title>
    </head>
    <body>
        <h2>User Authentication System</h2><hr>
        <h3>Password Reset Form</h3>
        <?php
        if (isset($result)) {
            echo $result;
        }
        ?>
        <?php
        if (!empty($form_errors)) {
            echo show_errors($form_errors);
        }
        ?>
        <form class="" action="" method="post">
            <table>
                <tr>
                    <td>Email:</td>
                    <td><input type="text" value="" name="email"></td>
                </tr>
                <tr>
                    <td>New Password:</td>
                    <td><input type="password" value="" name="new_password"></td>
                </tr>
                <tr>
                    <td>Confirm Password</td>
                    <td><input type="password" value="" name="confirm_password"></td>
                </tr>
                <tr>
                    <td></td>
                    <td><input style="float:right" type="submit" name="passwordResetBtn" value="Reset Password"></td>
                </tr>
            </table>
        </form>
        <p>
            <a href="index.php">Back</a>
        </p>
    </body>
</html>
