<?php
session_start();

if (isset($_SESSION['uid'])) {
    header('Location: ./');
}


require './connect/config.php';

$errors = [];

$pdo = new mypdo();

if (isset($_POST['password']) && isset($_POST['password2'])) {

    $password = trim($_POST['password']);
    $password2 = trim($_POST['password2']);
    $token =  $_POST['token'];

    if ($token == "") {
        $errors[] = ["form" => "There is an error "];
    }

    if ($password == "") {
        $errors[] = ["password" => "Please enter a valid password"];
    } else {
        if (!validPassword($password)) {
            $errors[] = ["password" => "Password strength is poor"];
        } elseif ($password != $password2) {
            $errors[] = ["password2" => "Password not matched"];
        }
    }
    require './connect/crypto.php';

    try {
        $raw_data = decrypt($token);
        $data = explode("___", $raw_data);
        //$timec."___".$email."___".$fname."___".$timec
    } catch (Exception $sc) {
        die("Wrong token");
    }
    $timec = intval($data[0]);
    if ($data[0] != $data[3]) { // timestamp must be equal
        $errors[] = ["form" => "There is an error "];
    }
    if ((time() - $timec) > 7200) {  // expires in 2 hours
        $errors[] = ["form" => "Oops! This link has expired"];
    }

    if (count($errors) == 0) {
        $email = $data[1];
        // Get the user id from paasowrd recover table
        $user = $pdo->get_one("SELECT * FROM password_reset WHERE email = ? AND timec = '$timec'", $email);

        if ($user != null) {
            $password = password_hash($password, PASSWORD_DEFAULT);
            $pdo->update_user_password($email, $password);
            $pdo->exec_query("DELETE FROM password_reset WHERE email = ?", $email);
            $_SESSION['msg'] = 'success'; 
            header("Location: reset-password.php?pl=");
            exit();
        }
    }
}




if(!isset($_GET['pl'])){
    header("Location: ./"); exit();
}

$token = $_GET['pl'];


$cur_page_id = "reset_password";
$page_name = "Reset password - " . glob_site_name;


?>
<?php require_once("templates/header.php"); ?>

<style>
    body {
        background-color: #FFF;

    }
</style>

<header class="main_header">
    <h1> Reset Password </h1>
</header>

<!-- Page content-->
<main>



    <section>

        <div class="container">
            <div class="cform">
                <div class="cform_wrapper">
                    <?php
                    if ((isset($_SESSION['msg']) &&  $_SESSION['msg'] == "success")) {
                    ?>
                        <div class="alert alert-info py-5">
                            <h2>
                                <p class="alert alert-success"> Password Reset successfully</p>
                            </h2>
                        </div>

                    <?php
                        unset($_SESSION['msg']);
                    } else {   ?>
                        <form id="sign_form" action="" method="POST">
                        <span class="aformError"><?php echo getError('form', $errors); ?></span>
                            <div class="form-group">
                                <label for="password"><b>New Password</b></label>
                                <div id="password_strength" class="badge displayBadge">Weak</div>
                                <input required class="form-control" type="password" placeholder="Enter Password" id="password" name="password" required>
                                <span class="formError" id="passwordError"><?php echo getError('password', $errors); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="password2"><b>Retype New Password</b></label>
                                <input required class="form-control" type="password" placeholder="Repeat Password" id="password2" name="password2" required>
                                <span class="formError" id="password2Error"><?php echo getError('password2', $errors); ?></span>
                            </div>
                            <input name="token" type="hidden" value="<?php echo $token; ?>">
                            <div class="form-group" id="sbutton">
                                <button type="submit" class="btn btn-block btn-primary">Submit</button>
                            </div>

                        </form>
                    <?php } ?>
                </div>

            </div>

        </div>
    </section>


</main>


<?php require_once("templates/footer.php"); ?>