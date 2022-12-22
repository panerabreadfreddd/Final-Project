<?php
session_start();

if (isset($_GET['logout'])) {
    if (isset($_SESSION['uid']) || isset($_SESSION['uidx'])) {
        //unset the seesion
        session_unset();
        session_destroy();
        header('Location: ./');
    }
}

if (isset($_SESSION['uid'])) { // Already login
    header('Location: ./');
}


require './connect/config.php';

$pdo = new mypdo();

$email = "";
$errors = [];



if (isset($_POST['email']) && isset($_POST['password'])) {



    $email = trim($_POST['email']);
    $password = trim($_POST['password']);


    // We will give less details for security sake
    if ($email == "" || $password == "") {
        $errors[] = ["form" => "Wrong login details"];
    } else {

        $user = $pdo->get_one("SELECT * FROM user WHERE email = ?", $email);
        if ($user == null) {
            $errors[] = ["form" => "Wrong login details"];
        } else {
            // Verify email
            if (!password_verify($password, $user['password'])) {
                $errors[] = ["form" => "Wrong login details"];
            } else { // Login successfull
                $_SESSION['email'] = $user['email'];
                $_SESSION['uid'] = $user['user_id'];
                $_SESSION['role'] = $user['role'];

                header("Location: ./");
                exit();
            }
        }
    }

    // If here, there is an error
    $_SESSION["data"] = [
        "email" => $email,
    ];
    $_SESSION["errors"] = $errors;
    header("Location: login.php");
    exit();
}



if (isset($_SESSION['errors'])) {
    $errors = $_SESSION['errors'];
    unset($_SESSION['errors']);
}
if (isset($_SESSION['data'])) {
    $data = $_SESSION['data'];
    unset($_SESSION['data']);

    $email = $data['email'];
}



$cur_page_id = "login";
$page_name = "Signin - " . glob_site_name;


?>
<?php require_once("templates/header.php"); ?>

<style>
    body {
        background-color: #FFF;
    }
</style>

<header class="main_header">
        <h1> Sign in </h1>
</header>

<!-- Page content-->
<main>


    <section>

        <div class="container">
            <div class="cform">
                <div class="cform_wrapper">

                    <form method="POST">
                        <span class="aformError"><?php echo getError('form', $errors); ?></span>
                        <div class="form-group">
                            <label for="email"><b>Email</b></label>
                            <input value="<?php echo $email; ?>" required class="form-control" type="email" placeholder="Enter Email" id="email" name="email" type="email" required>
                            <span class="formError" id="emailError"><?php echo getError('email', $errors); ?></span>
                        </div>
                        <div class="form-group">
                            <label for="password"><b>Password</b></label>
                            <input required class="form-control" type="password" placeholder="Enter Password" id="password" name="password" required>
                        </div>
                        <div class="form-group" id="sbutton">
                            <button type="submit" class="btn btn-block btn-primary">Login</button>
                            <a style="float: right;" href="forgot-password.php">Forgot password?</a>
                        </div>
                        <label>
                            <input type="checkbox" checked="checked" name="remember"> Remember me
                        </label>

                        <div style="float:right">
                            <span>Don't Have An account? <a href="sign-up"> Sign Up Here</a></span>
                        </div>
                    </form>
                </div>

            </div>

        </div>
    </section>
</main>


<?php require_once("templates/footer.php"); ?>