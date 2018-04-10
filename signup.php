<?php
// Add our database connection script
include_once 'resource/Database.php';

include_once 'resource/utilities.php';

// Process the form
if (isset($_POST['signupBtn'])) {
    // Initialize an array to store any error message from the form
    $form_errors = array();

    // Form validation
    $required_fields_array = array("email", "username", "password");

    // Call the function to check empty field and merge the return data form_error array
    $form_errors = array_merge($form_errors, check_empty_fields($required_fields_array));

    // Fields that require checking for minimum length
    $field_to_check_length = array('username' => 4, 'password' => 6);

    // Call the function to check minimum required length and merge the return data into form_error array
    $form_errors = array_merge($form_errors, check_min_length($field_to_check_length));

    // Email validation/merge the return data into form_error array
    $form_errors = array_merge($form_errors, check_email($_POST));

    //Check if error array is empty, if yes process form data and insert record
    if (empty($form_errors)) {
        // Collect form data and store in variables
        $email = $_POST['email'];
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Hashing the password.
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        try {
            // Create SQL INSERT statement.
            $sqlInsert = "INSERT INTO users(username, email, password, join_date)"
                    . " VALUES (:username, :email, :password, now())";
            // Use PDO prepared to sanitize data.
            $statement = $db->prepare($sqlInsert);

            // Add the data into  the database
            $statement->execute(array(':username' => $username, ':email' => $email, ':password' => $hashed_password));

            // Check if one new row was created.
            if ($statement->rowCount() == 1) {
                $result = flashMessage("Registration Successful","Pass");
            }
        } catch (PDOException $ex) {
            $result = flashMessage("An error has occured!! " . $ex->getMessage());

        }
    } else {
        if (count($form_errors) == 1) {
            $result = flashMessage("There was an error in the form.");
        } else {
            $result = flashMessage("There were " . count($form_errors) . " errors in the form");
        }
    }
}
?>
<!DOCTYPE html>
<html>
    <head lang="en">
        <meta charset="UTF-8">
        <title>Register Page</title>
    </head>
    <body>


        <h2>User Authentication System</h2><hr>
        <h3>Registration Form</h3>

        <?php
        if (isset($result)) {
            echo $result;
        }
        if (!empty($form_errors)) {
            echo show_errors($form_errors);
        }
        ?>
        <form method="post" action="">
            <table>
                <tr>
                    <td>
                        Email:
                    </td>
                    <td>
                        <input type="email" value="" name="email">
                    </td>
                </tr>
                <tr>
                    <td>
                        Username:
                    </td>
                    <td>
                        <input type="text" value="" name="username">
                    </td>
                </tr>
                <tr>
                    <td>
                        Password:
                    </td>
                    <td>
                        <input type="password" value="" name="password">
                    </td>
                </tr>
                <tr>
                    <td>

                    </td>
                    <td>
                        <input style="float: right" type="submit" name="signupBtn" value="Signup">
                    </td>
                </tr>
            </table>
        </form>
        <p>
            <a href="index.php">Back</a>
        </p>
    </body>
</html>
