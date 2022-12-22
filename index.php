<?php

session_start();


require './connect/config.php';

$cur_page_id = "home";
$page_name = glob_site_name;

$pdo = new mypdo();

$prds =  $pdo->get_all("SELECT a.*, b.fname, c.name AS cat_name, d.name AS loc_name FROM items a LEFT JOIN user b ON a.user_id = b.user_id LEFT JOIN categories c ON a.cat_id = c.cat_id LEFT JOIN locations d ON a.loc_id = d.loc_id ORDER BY a.added_date DESC LIMIT 20");


?>
<?php require_once("templates/header.php"); ?>

<style>
	body {
		background-color: #FFF !important;
	}
</style>
<!-- carousel section -->
<!-- Header Carousel -->
<section style="position: relative;">

	<!-- Carousel -->
	<div id="demo" class="carousel slide" data-bs-ride="carousel">

		<!-- Indicators/dots -->
		<div class="carousel-indicators">
			<button type="button" data-bs-target="#demo" data-bs-slide-to="0" class="active"></button>
			<button type="button" data-bs-target="#demo" data-bs-slide-to="1"></button>
			<button type="button" data-bs-target="#demo" data-bs-slide-to="2"></button>
		</div>

		<!-- The slideshow/carousel -->
		<div class="carousel-inner">
			<div class="carousel-item active">
				<div class="carousel-item-wrapper">
					<div>
						<h1>Best Website to Sell Used Items at Kean University</h1>
						<p>Easy meet up with fellow students for their old items</p>
						<a href="search.php">Search </a>
					</div>
				</div>
				<img class="d-block w-100" src="img/img1.png" alt="First slide">
			</div>
			<div class="carousel-item">
				<div class="carousel-item-wrapper">
					<div>
						<h1>Make some extra money by selling your old items</h1>
						<p>Explore the values of your old items which you no loger used</p>
						<a href="sign-up.php">Sign Up </a>
					</div>
				</div>
				<img class="d-block w-100" src="img/img2.jpg" alt="Second slide">
			</div>
			<div class="carousel-item">
				<div class="carousel-item-wrapper">
					<div>
						<h1>Used! But still in good, perfect condition</h1>
						<p></p>
						<a href="search.php">Search </a>
					</div>
				</div>
				<img class="d-block w-100" src="img/img3.jpg" alt="Third slide">
			</div>
		</div>

		<!-- Left and right controls/icons -->
		<button class="carousel-control-prev" type="button" data-bs-target="#demo" data-bs-slide="prev">
			<span class="carousel-control-prev-icon"></span>
		</button>
		<button class="carousel-control-next" type="button" data-bs-target="#demo" data-bs-slide="next">
			<span class="carousel-control-next-icon"></span>
		</button>
	</div>


</section>

<!-- end carousel section -->

<!-- Page content-->
<main>

	<div class="container page-section ">


		<h2>Recently added Items</h2>

		<div class="row">

			<?php foreach ($prds as $prd) { ?>
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


	</div>


</main>

<?php require_once("templates/footer.php"); ?>