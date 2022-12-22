<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title> <?php echo $page_name; ?></title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="img/favicon.ico" />
    <!-- Core  Bootstrap-->
    <link href="css/bootstrap.min.css" rel="stylesheet" />

    <link href="css/font-awesome.min.css" rel="stylesheet" />


    <!-- Custom -->
    <link href="css/custom.css" rel="stylesheet" />

</head>

<body>
    <!-- Responsive navbar-->
    <nav class="navbar navbar-expand-lg top-nav">
        <div class="container">
            <a class="navbar-brand" href="./"><img class="top_logo" src="img/logo.jpg"><?php echo glob_site_name; ?></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="fa fa-bars navbar-toggler-icon text-white"></span></button>


            <div class="collapse navbar-collapse" id="navbarSupportedContent">
              
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link <?php echo ($cur_page_id == "home") ? 'active' : ''; ?>" aria-current="page" href="./">Home</a></li>
                    <li class="nav-item"><a class="nav-link <?php echo ($cur_page_id == "search") ? 'active' : ''; ?>" aria-current="page" href="./search.php">Search</a></li>

                    <?php if (isset($_SESSION['uid'])) {  ?>
                        <li class="nav-item"><a class="nav-link <?php echo ($cur_page_id == "user") ? 'active' : ''; ?>" href="user"> user Area</a></li>
                    <?php } else { ?>
                        <li class="nav-item"><a class="nav-link <?php echo ($cur_page_id == "login") ? 'active' : ''; ?>" href="login.php"> Login</a></li>
                        <li class="nav-item"><a class="nav-link <?php echo ($cur_page_id == "sign-up") ? 'active' : ''; ?>" href="sign-up.php"> Sign Up</a></li>
                    <?php } ?>


                </ul>
            </div>
        </div>
    </nav>