<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title><?php echo $page_name; ?></title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="../css/bootstrap.min.css" rel="stylesheet" />

    <!-- Font Awesome -->
    <link href="../css/font-awesome.min.css" rel="stylesheet" />

    <link href="../css/custom.css" rel="stylesheet" />
</head>

<body>
    <div class="d-flex" id="wrapper">
        <!-- Sidebar-->
        <div class="border-end side_nav" id="sidebar-wrapper">
            <div class="sidebar-heading border-bottom bg-light">User Area</div>
            <div class="list-group list-group-flush">
                <a class="list-group-item list-group-item-action list-group-item-light p-3" href="./">Dashboard</a>
                <a class="list-group-item list-group-item-action list-group-item-light p-3" href="add-item.php">Add item</a>
                <a class="list-group-item list-group-item-action list-group-item-light p-3" href="my-items.php">My Items</a>
                <?php if ($_SESSION['role'] == 1) { ?>
                    <a class="list-group-item list-group-item-action list-group-item-light p-3" href="items.php">All Items</a>
                    <a class="list-group-item list-group-item-action list-group-item-light p-3" href="categories.php">Item categories</a>
                    <a class="list-group-item list-group-item-action list-group-item-light p-3" href="locations.php">Locations</a>
                <?php } ?>
            </div>
        </div>
        <!-- Page content wrapper-->
        <div id="page-content-wrapper">
            <!-- Top navigation-->
            <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom top-nav">
                <div class="container-fluid">
                    <button class="btn btn-primary" id="sidebarToggle">Menu</button>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon text-white" style="color:#FFF !important"></span></button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav ms-auto mt-2 mt-lg-0">
                            <li class="nav-item active"><a class="nav-link" href="../">Home</a></li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo $_SESSION['email']; ?></a>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="update-profile.php">Update profile</a>
                                    <a class="dropdown-item" href="change-password.php">Change Password</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="../login.php?logout=1">Logout</a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>