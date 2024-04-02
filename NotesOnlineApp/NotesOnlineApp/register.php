<?php

$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "noteapp"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $firstname = $_POST["first-name"];
    $lastname = $_POST["last-name"];
    $username = $_POST["user-name"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    



    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO logintbl (f_name, l_name, user_name, l_email, pass_word) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $firstname, $lastname, $username, $email, $hashed_password);

    if ($stmt->execute()) {
        // Registration successful
        echo '<script>alert("You are successfully registered!");</script>';
        // Redirect to login page
        echo '<script>window.location.href = "login.php";</script>';
        exit(); // Stop further execution
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}
?>  

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ONLINE NOTES APPLICATION</title>
    <link rel="stylesheet" href="register.css"> 
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'> 
</head>
<body>
    <div class="container">
        <header>
            <h1 id="notelt-title"><span class="do">Do</span><span class="note">Note!</span></h1>
            <nav class="navigation">
                <a href="index.php">HOME</a>
                <a href="register.php">REGISTER</a>
                <a href="login.php">LOGIN</a>
            </nav>
        </header>

        <div id="notelt-container">
            <h1>Register</h1>

            <form id="registration-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div>
                    <label for="first-name">First Name:</label>
                    <input type="text" id="first-name" name="first-name" required>
                </div>
               
                <div>
                    <label for="last-name">Last Name:</label>
                    <input type="text" id="last-name" name="last-name" required>
                </div>
               
                <div>
                    <label for="user-name">Username:</label>
                    <input type="text" id="user-name" name="user-name" required>
                </div>
               
                <div>
                    <label for="email">Email Address:</label>
                    <input type="email" id="email" name="email" required>
                </div>
               
                <div>
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <div>
                    <label for="con-password">Confirm Password:</label>
                    <input type="password" id="con-password" name="con-password" required>
                </div>  
                
               
                <button type="button" id="sign-in-button" onclick="validateForm()" style = "background-color:pink ;color: black; margin-top: 5px;margin-bottom: 10px; padding: 5px 50px; border-radius: 40px;font-size: 16px; align-items: center; display : block; margin: auto;">REGISTER</button>
            </form>
        </div>

        <script>
            function validateForm() {
                var firstname = document.getElementById("first-name").value;
                var lastname = document.getElementById("last-name").value;
                var username = document.getElementById("user-name").value;
                var email = document.getElementById("email").value;
                var password = document.getElementById("password").value;
                var confirmPassword = document.getElementById("con-password").value;

                if (firstname.trim() == "") {
                    alert("Please enter your first name");
                    return false;
                }
                
                if (lastname.trim() == "") {
                    alert("Please enter your last name");
                    return false;
                }
                if (username.trim() == "") {
                    alert("Please enter your username");
                    return false;
                }

                if (email.trim() == "") {
                    alert("Please enter your email");
                    return false;
                }

                // Check if the email ends with "@gmail.com"
                if (!email.trim().toLowerCase().endsWith("@gmail.com")) {
                    alert("Invalid Email!");
                    return false;
                }

                if (password.trim() == "") {
                    alert("Please enter your password");
                    return false;
                }

                if (confirmPassword.trim() == "") {
                    alert("Please confirm your password");
                    return false;
                }

                if (password !== confirmPassword) {
                    alert("Passwords do not match");
                    return false;
                }

                // If all validations pass, submit the form
                document.getElementById("registration-form").submit();
            }

            

        </script>
    </div>
</body>
</html>

<style>
    @import url('https://fonts.googleapis.com/css?family=Poppins:400,700,900');

* {
    font-family: "Poppins", sans-serif;
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

.container {
    max-width: 1200px; 
    margin: 0 auto; 
    padding: 20px; 
}

header{
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    padding: 10px 100px;
    background: pink;
    display: flex;
    justify-content: space-between;
    align-items: center;
    z-index: 99;
}

.logo{
    font-size: 2em;
    color: black;
    user-select: none;
}

.navigation a{
    position: relative;
    font-size: 1.2em;
    color: black;
    text-decoration: none;
    font-weight: 400;
    margin-left: 10px;
}

.navigation a::after{
    content: '';
    position: absolute;

}

.navigation login{
    width: 130px;
    height: 50px;
    background: transparent;
    border: 2px solid white;
    outline: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 1.1em;
    color: white;
    font-weight: 500;
    margin-left: 40px;
    transition: .5s;
}

.navigation .button_login:hover{
    background: white;
    color: black;
}




#notelt-container {
    width: 700px;
    padding: 50px;
    background-color: white;
    text-align: center;
    border: 1px solid #ccc;
    box-shadow: 20px 20px 15px 10px rgba(0,0,0,0.1);
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    margin-top: 40px;
}
#notelt-title {
    line-height: 50px;
    margin-bottom: 10px;
}

#notelt-title span.do {
    color: rgb(233, 109, 130); /* Change color of "Do" to pink */
}

#notelt-title span.note {
    color: black; /* Change color of "Note" to pink */
}

  #notelt-logo {
    width: 150px;
    height: auto;
    margin-bottom: 20px;
  }
  
  #notelt-description {
    font-size: 20px;
    line-height: 30px;
    color: black;
    margin-bottom: 20px;
  }


  #button {
    background-color:pink ;
    color: black;
    margin-top: 5px;
    margin-bottom: 10px;
    padding: 5px 50px;
    border-radius: 40px;
    font-size: 16px;
    align-items: center;

  }
  #registration-form {
    width: 600px; 
    height: auto;
    margin-top: 50px;
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between; 
    color: black;
}

#registration-form div {
    width: 48%; 
}

#registration-form label {
    display: block;
    text-align: left; /* Align label text to the right */
    padding-bottom: 10px;
}

#registration-form input {
    width: calc(100% - 10px); /* Adjust input width to account for padding */
    padding: 5px;
    text-align: left; /* Align input text to the left */
    margin-bottom: 30px;
    border: 2px solid;
    border-radius: 0px;
    color: black;
}
</style>