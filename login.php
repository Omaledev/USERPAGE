<?php

session_start();
include ('db.config.php');
$email = $password = ""; 
$emailErr = $passwordErr = $invalidErr = "";  


function sanitize_input($data){
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);

    return $data;
}


// using post to adhere to requirements of the form
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = sanitize_input($_POST['email']);
    $password = $_POST['password'];

    if (empty($email)) {
        $emailErr = "Email is required!";
    }

    if (empty($password)) {
        $passwordErr = "Password is required!";
    }

    // only proceeds if no errors
    if (empty($emailErr) && empty($passwordErr)) {
        try {
            // Use prepared statements to prevent SQL injection
            $stmt = $dbconnection->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($user) {
                // var_dump($user);
                // echo '<br>' . $user['password'] . 'br';
                // echo $password. '<br>';
                // verify password matches with email           
                if ($password === $user['password']) {
                    $_SESSION['id'] = $user['id'];
                    header('Location: dashboard.php');
                    exit;
                } else {
                    $invalidErr = "Invalid credentials!";
                }
            } else {
                $invalidErr = "User does not exist! Please sign up";
            }  
        } catch(PDOException $e) {
            $invalidErr = "Error:" . $e->getMessage();  // Fixed variable name
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="main_container">
        <h2 class="header">Login</h2>
        <p class="error" id="invalidErr" style="color:red;"><?php echo $invalidErr ?></p>
        <form id="login-form" action="<?php trim($_SERVER["PHP_SELF"]) ?>" method="POST">
            <div class="container">
                <label for="email">Email</label>
                <input type="text" name="email" id="email" value="<?php echo $email ?>">
                <span class="error" style="color:red;"><?php echo $emailErr ?></span>
            </div>
            <div class=" container">
                <label for="password">Password</label>
                <input type="text" name="password" id="password" value="<?php echo $password ?>">
                <span class="error" style="color:red;"><?php echo $passwordErr ?> </span>
            </div>
            <div class="container">
                <button type="submit">Login</button>
            </div>
            <p class="register">Don't have an account? <a href="index.php">Register now</a></p>
        </form>
    </div>
</body>
</html>