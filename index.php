<?php 
// start session
session_start();
include ('db.config.php');
$name = $email = $password = $contact = $address=""; 
$nameErr = $emailErr = $passwordErr = $contactErr = $addressErr = "" ;


function sanitize_input($data){

    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);

    return $data;
}

// email, password, and contact validation
function validate_email($email) {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
        return false;
    }else {
        return true;
    }
}
    
function validate_password($password) {
        if (strlen($password)< 8) {
            return false;
        }else {
            return true;
        }
    }

function validate_contact($contact) {
    if (strlen($contact) <> 11 ) {
        return false;
    }else {
        return true;
    }
}

// using post to adhere to requirements of the form
    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        if (empty($_POST['name'])) {
              $nameErr = "Name is required!";
            } else {
              $name = sanitize_input(data: $_POST['name']);
            }
    
        if (empty($_POST['email'])) {
                $emailErr = "Email field is required!";
              } else {
                $email = sanitize_input(data: $_POST ['email']);
                if (!validate_email($email)) {
                    $emailErr = "Invalid email format";
                }
              }
        
        if (empty($_POST['password'])) {
              $passwordErr = "Password is required!";
            } else {
              $password = sanitize_input(data: $_POST['password']);
              if (!validate_password($password)) {
                $passwordErr = "Password must be at least 8 characters long";
              }
            }
        
        if (empty($_POST['contact'])) {
              $contactErr = "insert your contact";
            } else {
              $contact = sanitize_input(data: $_POST['contact']);
              if (!validate_contact($contact)) {
                $contactErr = "contact must be 11 digit";
              }
            }
            

        if (empty($_POST['address'])) {
              $addressErr = "Address is required";
        }else {
                $address = sanitize_input(data: $_POST['address']);
            }
        
        
    

// Inserting data into the `users` table
    if (empty($nameErr)  && empty($emailErr) && empty($passwordErr) && empty($contactErr) && empty($addressErr)) {
        $stmt = $dbconnection->prepare("INSERT INTO users (name, email, password, contact, address) 
        VALUES (:name, :email, :password, :contact, :address)");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':contact', $contact);
        $stmt->bindParam(':address', $address);
        $stmt->execute();
     
        $_SESSION['id'] = $dbconnection->lastInsertId();
     
        $dbconnection = null;
     
        header("location:dashboard.php");
        exit();
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
        <h2 class="header">Signup Form</h2>
        <form action="<?php trim($_SERVER["PHP_SELF"]) ?>" method="POST">
            <div class="container">
                <label for="name">Name</label> 
                <input type="text" name="name" id="name" value="<?php echo $name ?>">
                <span class="error" style="color:red;"> <?php echo $nameErr ?></span>
            </div>
            <div class="container">
                <label for="email">Email</label>
                <input type="text" name="email" id="email" value="<?php echo $email ?>">
                <span class="error" style="color:red;"><?php echo $emailErr ?></span>
            </div>
            <div class=" container">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" value="<?php echo $password ?>">
                <span class="error" style="color:red;"><?php echo $passwordErr ?> </span>
            </div>
            <div class="container">
                <label for="contact">Contact</label>
                <input type="text" name="contact" id="contact" value="<?php echo $contact ?>">
                <span class="error" style="color:red;"> <?php echo $contactErr ?></span>
            </div>
            <div class="container">
                <label for="address">Address</label>
                <input type="text" name="address" id="address" value="<?php echo $address ?>">
                <span class="error" style="color:red;"> <?php echo $addressErr ?></span>
            </div>
            <div class="container">
                <button type="submit">Sign Up</button>
            </div>
           <p class="login">Already have an account? <a href="login.php">Login now</a></p>
        </form>
    </div>
</body>
</html>