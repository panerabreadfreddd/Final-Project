<?php
session_start();


require './connect/config.php';


if (!isset($_GET['prd_id'])) {
    header('Location: ./');
    exit();
}

$prd_id = intval($_GET['prd_id']);


$pdo = new mypdo();

$prd = $pdo->get_one("SELECT a.*, b.fname, b.phone, b.address, c.name AS cat_name, d.name AS loc_name FROM items a LEFT JOIN user b ON a.user_id = b.user_id LEFT JOIN categories c ON a.cat_id = c.cat_id LEFT JOIN locations d ON a.loc_id = d.loc_id WHERE a.prd_id = ?", $prd_id);

if ($prd == null) {
    header('Location: ./');
    exit();
}



$cur_page_id = "item";
$page_name = $prd['prd_name'];


?>
<?php require_once("templates/header.php"); ?>

<style>
    body {
        background-color: #FFF;
    }
</style>

<header class="main_header">
    <h1> Item - <?php echo $prd['prd_name']; ?> </h1>
</header>

<!-- Page content-->
<main>

    <section>
        <div class="container text-center">
            <div class="row">
            <div class="col-12 col-md-2">
            </div>    
            <div class="col-12 col-md-8 align-self-center">
                    <table class="prd_item table table-striped">
                        <tr>
                            <td colspan="2">
                                <img src="./uploads/<?php echo $prd['prd_image']; ?>">
                            </td>
                        </tr>
                        <tr>
                            <th>Price: </th>
                            <td>&dollar;<?php echo number_format($prd['price'], 2); ?></td>
                        </tr>
                        <tr>
                            <th>Category</th>
                            <td><?php echo $prd['cat_name']; ?></td>
                        </tr>
                        <tr>
                            <th>Item Location</th>
                            <td><?php echo $prd['loc_name']; ?></td>
                        </tr>
                        <tr>
                            <th>Color</th>
                            <td><?php echo $prd['color']; ?></td>
                        </tr>
                        <tr>
                            <th>Size</th>
                            <td><?php echo $prd['size']; ?></td>
                        </tr>

                    </table>
                    <h4>Item Description:</h4>
                    <div style=" white-space:pre-wrap; text-align:left "><?php echo $prd['prd_desc']; ?></div>
                    <br>
                    <h4>Seller Details</h4>
                    <table class="prd_item table table-striped">
                        <tr>
                            <th>Seller name: </th>
                            <td><?php echo $prd['fname']; ?></td>
                        </tr>
                        <tr>
                            <th>Phone number</th>
                            <td><?php echo $prd['phone']; ?></td>
                        </tr>
                        <tr>
                            <th>Address</th>
                            <td><?php echo $prd['address']; ?></td>
                        </tr>
                  
                    </table>
                </div>

            </div>
            <br><br><br>
        </div>
    </section>
</main>


<?php require_once("templates/footer.php"); ?>