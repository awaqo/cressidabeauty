<?php

require_once("../config/config.php");

if(isset($_POST['submit'])){
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (name, username, email, password) 
            VALUES (:name, :username, :email, :password)";
    $stmt = $db->prepare($sql);

    $params = array(
        ":name" => $name,
        ":username" => $username,
        ":password" => $password,
        ":email" => $email
    );

    $saved = $stmt->execute($params);

    if($saved) {
        $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

        $sql = "SELECT * FROM users WHERE username=:username OR email=:email";
        $stmt = $db->prepare($sql);
 
        $params = array(
            ":username" => $username,
            ":email" => $username
        );

        $stmt->execute($params);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if($user){
            if(password_verify($password, $user["password"])){
                session_start();
                $_SESSION["user"] = $user;
                echo "
                    <script>
                        alert('You are successfully registered');
                        location='../pages/home.php';
                    </script>
                ";
            } 
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Cressida Beauty</title>
    <link rel="icon" href="../assets/img/logo.png">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,300;0,400;0,700;0,900;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/sb-admin.css">
    <link rel="stylesheet" href="../assets/slick/slick.css">
    <link rel="stylesheet" href="../assets/slick/slick-theme.css"/>
</head>


<body>
    <div class="container py-5">
        <div class="col-md-5 mt-3 mx-auto">
            <a href="../pages/home.php" class="text-decoration-none">
                <div class="row d-flex justify-content-center">
                    <svg width="31" height="23" viewBox="0 0 31 23" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M21 23V19C21 20.6 23.3333 22.3333 24.5 23H21Z" fill="#825348"/>
                        <path d="M22.0182 16C19.6182 13.6 22.0182 11 23.5182 10C23.0181 14 25.0182 13.5 29.0182 17C32.2182 19.8 28.6849 22.1667 26.5182 23C29.5182 19.5 25.0182 19 22.0182 16Z" fill="#805146"/>
                        <path d="M16.7201 0.423439V8.42344C14.3201 0.423423 9.72011 0.423433 7.72009 1.42344C-1.27994 6.42342 3.72009 14.9235 6.72009 16.9235C9.12009 18.5235 12.3868 16.9235 13.7201 15.9235C16.6467 12.7823 23.4 6.6 27 7C30 7 31.5 9.5 30.5 11.5C29.5 13.5 26.5 13 27 11C27.3333 10.6667 27.8 9.6 27 8C26.2 6.4 20.8134 10.2823 18.2201 12.4235L14.2201 16.4235C14.0534 16.9235 12.7201 18.0235 8.72012 18.4235C3.72012 18.9235 -3.27988 10.9234 1.72012 3.92344C5.72012 -1.67656 12.3868 -0.0765601 15.2201 1.42344L16.7201 0.423439Z" fill="#805146"/>
                        <path d="M15 12L18 9V20C18 20.8 18.6667 21.6667 19 22H13C14.2 21.6 14.5 20.5 14.5 20V14C14.5 13.2 14.5 13 15 12Z" fill="#805146"/>
                        <circle cx="28.5" cy="10.5" r="2.5" fill="#805146"/>
                    </svg>
                    <h4 class="login-title mx-2">Cressida Beauty</h4>
                </div>
            </a>
            <h5 class="login-form-text-color text-center mt-3">Register User</h5>
            <hr class="hr-login-form-title mx-auto">
            <div class="text-dark mt-4">Already have account? <a href="login.php" class="login-form-text-color reglog-text">Login</a></div>
            <form action="" method="POST" class="mt-3">
                <div class="form-group">
                    <input type="text" name="name" class="form-control rounded-0" placeholder="Full Name" required>
                </div>

                <div class="form-group">
                    <input type="email" name="email" class="form-control rounded-0" placeholder="E-mail" required>
                </div>

                <div class="form-group">
                    <input type="text" name="username" class="form-control rounded-0" placeholder="Username" required>
                </div>

                <div class="form-group">
                    <input type="password" name="password" class="form-control rounded-0" placeholder="Password" required>
                </div>

                <div class="form-group">
                    <input type="password" name="confirm_password" class="form-control rounded-0" placeholder="Confirm password" required>
                </div>
                <label class="checkbox text-dark mt-4">
                    <input type="checkbox" value="remember-me" id="remember_me"> I have read and agree to Cressida Beauty's <a href="#" class="login-form-text-color reglog-text">Terms of Use</a> and <a href="#" class="login-form-text-color reglog-text">Privacy Policy</a>
                </label>
                <div class="form-group">
                    <input type="submit" name="submit" class="btn btn-block btn-brown-login rounded-0 mt-2" value="Register">
                </div>
                <!-- <a href="../config/config.php" class="btn btn-block btn-brown-login rounded-0 mt-2">Register</a> -->
                <!-- <button class="btn btn-block btn-brown-login rounded-0 mt-3">Login</button> -->
            </form>
        </div>
    </div>
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
    <script type="text/javascript" src="../assets/slick/slick.min.js"></script>
    <script type="text/javascript" src="../assets/js/main.js"></script>
</body>
</html>