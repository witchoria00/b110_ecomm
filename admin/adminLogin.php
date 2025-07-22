<?php 
// login from button name 
require_once "dbconnect.php";

if(isset($_POST['login'])) // $_POST is super global array
{
    $email = $_POST['email'];  // retrieve email value of user 
    $password = $_POST['password']; // retrieve password of user
    
    // echo "email is $email and password is $password."; (debugging)
    $sql = "SELECT * FROM admin WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$email]);
    $adminInfo = $stmt->fetch(); // single row returns

    // $adminInfo ['id'], $adminInfo ['email'], $adminInfo ['password'], $adminInfo ['remark']

    if($adminInfo)
    {
        if(password_verify($password, $adminInfo['password'])) // checks password and hash match
        {
            echo "login success!!";
        }
        else
        {
            $errorMessage = "Email or Password might be incorrect!";
        }
    }
    else
    {
        $errorMessage = "Email or Password might be incorrect!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <?php 
            require_once ("navbarcopy.php");
            ?>
        </div>
        <div class="row">
            <div class="col-md-6 mx-auto py-5">
                <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
                    <?php 
                    if(isset($errorMessage)){
                        echo "<p class='alert alert-danger'>$errorMessage</p>";
                    }
                    ?>
                    <div class="mb-3">
                        <label for="" class="form-label">Email</label>
                        <input type="email" class="form-control" name="email">
                    </div>
                    <div class="mb-3">
                        <label for="">Password</label>
                        <input type="password" class="form-control" name="password">
                    </div>
                    <button type="submit" class="btn btn-outline-primary" name="login">
                        Login
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
