<?php
session_start();


require './connect/config.php';

$pdo = new mypdo();

$search = "";
$loc_id = "";
$cat_id = "";
$color = "";


$locs = $pdo->get_all("SELECT * FROM locations");
$cats = $pdo->get_all("SELECT * FROM categories");

$wheres = [];
$values = [];

$page = 0;

$prds = [];

$qry_n = "";

if (isset($_GET['search']) && trim($_GET['search']) != "") {

    $search = trim(plain_validate($_GET['search']));
    //$wheres[] = "MATCH(a.prd_name, a.prd_desc) AGAINST(? IN NATURAL LANGUAGE MODE)";
    $wheres[] = "a.prd_name LIKE ?";

    $values[] = '%'.$search.'%';
    $qry_n .= "search=" . urlencode($search);

    if (isset($_GET['loc_id']) && trim($_GET['loc_id']) != "") {
        $loc_id = intval($_GET['loc_id']);
        $wheres[] = "a.loc_id = ?";
        $values[] = $loc_id;
        $qry_n .= "&loc_id=" . $loc_id;
    }
    if (isset($_GET['cat_id']) && trim($_GET['cat_id']) != "") {
        $loc_id = intval($_GET['cat_id']);
        $wheres[] = "a.cat_id = ?";
        $values[] = $cat_id;
        $qry_n .= "&cat_id=" . $cat_id;
    }
    if (isset($_GET['color']) && trim($_GET['color']) != "") {
        $color = trim(plain_validate($_GET['color']));
        $wheres[] = "a.color = ?";
        $values[] = $color;
        $qry_n .= "&color=" . urlencode($color);
    }

    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $page = ($page < 1) ? 1 : $page;

    $offset = ($page - 1) * search_per_page;


    $qry = "SELECT a.*, c.name AS cat_name, d.name AS loc_name FROM items a LEFT JOIN categories c ON a.cat_id = c.cat_id LEFT JOIN locations d ON a.loc_id = d.loc_id";

    $qry .= " WHERE " . implode(" AND ", $wheres);

    $qry .= " LIMIT $offset, " . search_per_page;

    $prds = $pdo->get_all_var($qry, $values);
}





$cur_page_id = "search";
$page_name = "Search - " . glob_site_name;


?>
<?php require_once("templates/header.php"); ?>

<style>
    body {
        background-color: #FFF;
    }
</style>

<header class="main_header">
    <h1> Search </h1>
</header>

<!-- Page content-->
<main>


    <section>

        <div class="container text-center">
            <form class="search_form py-5" method="GET">
                <input value="<?php echo $search; ?>" class="search" name="search" required placeholder="Search ">
                <details>
                    <summary>Filter Search</summary>
                    <div>
                        <div class="form-group">
                            <label>Item Category </label>
                            <select class="form-control" name="cat_id" id="cat_id">
                                <option></option>
                                <?php foreach ($cats as $cat) {
                                    echo '<option ' . (($cat_id == $cat['cat_id']) ? 'selected' : '') . ' value="' . $cat['cat_id'] . '">' . $cat['name'] . '</option>';
                                } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>location/City </label>
                            <select class="form-control" name="loc_id" id="loc_id">
                                <option></option>
                                <?php foreach ($locs as $loc) {
                                    echo '<option ' . (($loc_id == $loc['loc_id']) ? 'selected' : '') . ' value="' . $loc['loc_id'] . '">' . $loc['name'] . '</option>';
                                } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Color </label>
                            <select class="form-control" name="color" id="color">
                                <option></option>
                                <?php foreach (glob_colors as $ccolor) {
                                    echo '<option ' . (($color == $ccolor) ? 'selected' : '') . '  value="' . $ccolor . '">' . $ccolor . '</option>';
                                }
                                ?>
                            </select>
                        </div>

                    </div>
                </details>
                <div class="text-right"><button class="btn btn-secondary"> Search</button></div>
            </form>

            <?php if (count($prds) == 0 && $search != "") { ?>

                <div class="alert alert-info py-5">No result found for this search<br><br>Refine your search and filter for better result</div>


            <?php } elseif (count($prds) == 0 && $search == "") { ?>

                <div class="alert alert-info py-5"> Use the box above to search for items</div>

            <?php   } else { ?>

                <div class="row">

                    <?php

                    foreach ($prds as $prd) { ?>
                        <div class="col-12 col-md-4 col-lg-3">
                            <div class="prd_item">
                                <span><?php echo $prd['cat_name']; ?></span>
                                <a href="item.php?prd_id=<?php echo $prd['prd_id']; ?>">
                                    <img src="./uploads/<?php echo $prd['prd_image']; ?>">
                                </a>
                                <h3><a href="item.php?prd_id=<?php echo $prd['prd_id']; ?>"><?php echo $prd['prd_name']; ?></a></h3>
                                <div><span>Price:</span> &dollar;<?php echo number_format($prd['price'], 2); ?></div>
                                <div><span>Location:</span> <?php echo $prd['loc_name']; ?></div>
                            </div>
                        </div>
                    <?php } ?>

                </div>

            <?php }

            echo '<div class="pagination py-3">';

            if ($page > 1) { ?>
                <a href="search.php?<?php echo $qry_n . '&page=' . ($page - 1); ?>">Previous Page</a>

            <?php }
            if (count($prds) == search_per_page) {
            ?>
                <a href="search.php?<?php echo $qry_n . '&page=' . ($page + 1); ?>">Next Page</a>
            <?php } ?>
        </div>
        <br><br><br>
        </div>
    </section>
</main>


<?php require_once("templates/footer.php"); ?>