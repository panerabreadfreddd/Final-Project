<?php
session_start();

if (isset($_SESSION['uid'])) {
    header('Location: ./');
}

require './connect/config.php';

$email = "";
$phone = "";
$fname = "";
$address = "";

$errors = [];


if (isset($_POST['email']) && isset($_POST['phone'])) {

    $email = trim($_POST['email']);
    $fname = trim($_POST['fname']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);

    $password = trim($_POST['password']);
    $password2 = trim($_POST['password2']);


    if ($email == "") {
        $errors[] = ["email" => "Please provide email address"];
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = ["email" => "Please provide a valid email address"];
    }

    if (strlen($fname) < 3) {
        $errors[] = ["fname" => "Please enter a proper Full Name"];
    }

    if (strlen($address) < 10) {
        $errors[] = ["address" => "Please enter a proper address"];
    }

    if (strlen($phone) > 13  || strlen($phone) < 9) {
        $errors[] = ["phone" => "Please enter a valid phone number"];
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


    $pdo = new mypdo();
    // Check if email address exist
    $user = $pdo->get_one("SELECT * FROM user WHERE email = ?", $email);
    if ($user != null) {
        $errors[] = ["email" => "This Email address already exist"];
    }

    // Insert to database if there is no error
    if (count($errors) == 0) {

        $email = plain_validate($email);
        $fname = plain_validate($fname);
        $phone = plain_validate($phone);
        $address = plain_validate($address);
        $timestamp = date("Y-m-d H:i:s");

        // Hash the password
        $password = password_hash($password, PASSWORD_DEFAULT);

        $user_id = $pdo->new_user($email, $phone, $fname, $password, 0, $address,  $timestamp);

        $_SESSION['msg'] = "reg_success";
        header("Location: sign-up.php");
        exit();
    }
    else{

        $_SESSION["data"] = [
            "email" => $email,
            "fname" => $fname,
            "phone" => $phone,
            "address" => $address,
        ];
        $_SESSION["errors"] = $errors;
        header("Location: sign-up.php");
        exit();
    }

}

if(isset($_SESSION['errors'])){
    $errors = $_SESSION['errors'];
    //die(print_r($errors));
    unset($_SESSION['errors']);
}
if(isset($_SESSION['data'])){
    $data = $_SESSION['data'];
    unset($_SESSION['data']);

    $email = $data['email'];
    $phone = $data['phone'];
    $fname = $data['fname'];
    $address = $data['address'];

}

$cur_page_id = "signup";
$page_name = "Sign Up - " . glob_site_name;


?>
<?php require_once("templates/header.php"); ?>

<style>
    body {
        background-color: lightgrey;

    }
</style>

<header class="main_header">
    <h1> Sign Up </h1>
</header>

<!-- Page content-->
<main>
    <section>

        <div class="container">
            <div class="cform">
                <div class="cform_wrapper">
                    <?php
                    if (isset($_SESSION['msg']) &&  $_SESSION['msg'] == "reg_success") {
                    ?>
                        <div class="alert alert-info py-5">
                            <h2>Registration was successfull</h2>
                            <p>You can now login to your account</p>
                        </div>

                    <?php
                        unset($_SESSION['msg']);
                    } else {   ?>
                        <form id="sign_form" action="" method="POST">
                            <span class="aformError"><?php echo getError('form', $errors); ?></span>
                            <div class="form-group">
                                <label for="email"><b>Email</b></label>
                                <input value="<?php echo $email; ?>" required class="form-control" type="email" placeholder="Enter Email" id="email" name="email" type="email" required>
                                <span class="formError" id="emailError"><?php echo getError('email', $errors); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="fname"><b>Phone Number</b></label>
                                <input value="<?php echo $phone; ?>" required class="form-control" placeholder="Phone number" id="phone" name="phone" required>
                                <span class="formError" id="phoneError"><?php echo getError('phone', $errors); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="fname"><b>Full Name</b></label>
                                <input value="<?php echo $fname; ?>" required class="form-control" placeholder="Enter Full namel" id="fname" name="fname" required>
                                <span class="formError" id="fnameError"><?php echo getError('fname', $errors); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="address"><b>Address</b></label>
                                <input value="<?php echo $address; ?>" required class="form-control" placeholder="Enter Address" id="address" name="address" required>
                                <span class="formError" id="addressError"><?php echo getError('address', $errors); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="password"><b>Password</b></label>
                                <div id="password_strength" class="badge displayBadge">Weak</div>
                                <input required class="form-control" type="password" placeholder="Enter Password" id="password" name="password" required>
                                <span class="formError" id="passwordError"><?php echo getError('password', $errors); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="password2"><b>Repeat Password</b></label>
                                <input required class="form-control" type="password" placeholder="Repeat Password" id="password2" name="password2" required>
                                <span class="formError" id="password2Error"><?php echo getError('password2', $errors); ?></span>
                            </div>
                            <div class="form-group" id="sbutton">
                                <button type="submit" class="btn btn-block btn-lg btn-primary">Sign Up</button>
                            </div>

                        </form>
                    <?php } ?>
                </div>

            </div>

        </div>
    </section>


</main>

<?php require_once("templates/footer.php"); ?>