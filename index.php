
<?php include('partials-front/menu.php'); ?>
<style>
 .categories {
        margin-top: 50px;
    }

    .box-3 {
        margin-bottom: 30px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease-in-out;
    }

    .box-3:hover {
        transform: scale(1.05);
    }

    .box-3 img {
        width: 100%;
        height: auto; /* Let the height adjust proportionally */
        object-fit: cover;
    }

    .box-3 .float-text {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: #fff;
        font-size: 24px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.6);
    }

   /* Banner styles */
   .banner {
        background-image: url(images/bg.jpg);
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        color: #fff;
        text-align: center;
        padding: 100px 0;
    }

    .banner h1 {
        font-size: 36px;
    }

    .banner p {
        font-size: 18px;
    }



/* Update the styles for the Features Section to display in a row-like structure */
.features {
    background: #f4f4f4;
    padding: 40px 0;
}

.features .row {
    display: flex;
    justify-content: space-between;
}

.features .col-md-4 {
    flex-basis: calc(33.33% - 20px);
    padding: 0 10px;
}

.features .feature-icon {
    margin: 0 auto;
    width: 60px;
    height: 60px;
    background: #357a38;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.features .feature-icon img {
    width: 100px;
    height: 100px;
}

.features h3 {
    margin-top: 20px;
    font-size: 20px;
}

/* Updated CSS for the About Stall section */
.about-stall {
    background: #f4f4f4;
    padding: 40px 0;
    text-align: center;
}

.card-container {
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap; /* Allow cards to wrap to the next row if needed */
}

.card {
    flex: 1;
    width: calc(30% - 20px); /* Adjust the width as needed */
    margin: 10px;
    padding: 20px;
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    text-align: center;
}

.card img {
    max-width: 100%;
    height: auto;
}

.card h3 {
    font-size: 18px;
    margin-top: 10px;
}

.card p {
    margin-top: 10px;
    font-size: 14px;
}
    
/* Add a common fade-in animation */
.fade-in {
    opacity: 0;
    transition: opacity 0.6s ease-in;
}

/* Apply the animation to sections with the "in-viewport" class */
.in-viewport {
    opacity: 1;
    transform: translateY(0);
    animation: fade 1s ease-in; /* Apply the fade-in animation to elements with the "in-viewport" class */
}

@keyframes fade {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}



/* CSS for the Order button */
.order-button {
    display: inline-block;
    margin-top: 20px;
    padding: 15px 30px;
    background-color: #ff6600;
    color: #fff;
    text-decoration: none;
    font-weight: bold;
    border-radius: 5px;
    transition: background-color 0.3s ease;
}

.order-button:hover {
    background-color: #ff9900;
}

/* CSS for the pulse animation */
@keyframes pulse {
    0%, 100% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.2);
    }
}

.pulse {
    animation: pulse 2s infinite;
}



</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const animateSections = document.querySelectorAll('.features, .categories, .about-stall');

        animateSections.forEach(function (section) {
            section.classList.add('in-viewport');
        });
    });
</script>

<?php 
    if (isset($_SESSION['order'])) {
        echo $_SESSION['order'];
        unset($_SESSION['order']);
    }
?>

<!-- Banner Section -->
<div class="banner">
    <div class="container text-center">
        <img src="images/logo.png" width="250" height="150" alt="Logo">
        <h1>Order food through Alabites</h1>
        <p>Have your R2K, CIA, Graciously Favorites quickly</p>

        <!-- Add the Order button with a unique ID for styling and scrolling -->
        <a href="#categories" class="order-button" id="scrollButton">Order Now</a>
    </div>
</div>


<!-- Features Section -->
<section id="categories"  class="features fade-in">
    <div class="container">
        <div class="row">
            <!-- 1st Feature Div -->
            <div class="col-md-4">
                <div class="feature-box text-center">
                    <!-- Icon for App Download -->
                    <div class="feature-icon">
                        <img src="images/download.png" alt="Download Icon">
                    </div>
                    <h3>1.Download the App</h3>
                </div>
            </div>
            <!-- 2nd Feature Div -->
            <div class="col-md-4">
                <div class="feature-box text-center">
                    <!-- Icon for Order -->
                    <div class="feature-icon">
                        <img src="images/online-order.png" alt="Order Icon">
                    </div>
                    <h3>2.Order Favorite Food</h3>
                </div>
            </div>
            <!-- 3rd Feature Div -->
            <div class="col-md-4">
                <div class="feature-box text-center">
                    <!-- Icon for Pickup -->
                    <div class="feature-icon">
                        <img src="images/food-stall.png" alt="Pickup Icon">
                    </div>
                    <h3>3.Pickup at Stall</h3>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Categories Section Starts Here -->
<section class="categories fade-in">
    <div class="container">
        <h2 class="text-center">Pick a Stall</h2>

        <div class="row">

            <?php 
            // Create SQL Query to Display Categories from Database
            $sql = "SELECT * FROM tbl_category WHERE active='Yes' AND featured='Yes' LIMIT 3";
            // Execute the Query
            $res = mysqli_query($conn, $sql);
            // Count rows to check whether the category is available or not
            $count = mysqli_num_rows($res);

            if ($count > 0) {
                // Categories Available
                while ($row = mysqli_fetch_assoc($res)) {
                    // Get the Values like id, title, image_name
                    $id = $row['id'];
                    $title = $row['title'];
                    $image_name = $row['image_name'];
                    ?>
                    
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <a href="<?php echo SITEURL; ?>category-foods.php?category_id=<?php echo $id; ?>">
                            <div class="box-3 float-container">
                                <?php 
                                // Check whether Image is available or not
                                if ($image_name == "") {
                                    // Display Message
                                    echo "<div class='error'>Image not Available</div>";
                                } else {
                                    // Image Available
                                    ?>
                                    <img src="<?php echo SITEURL; ?>images/category/<?php echo $image_name; ?>" alt="<?php echo $title; ?>" class="img-fluid img-curve">
                                    <?php
                                }
                                ?>
                                <h3 class="float-text text-white"><?php echo $title; ?></h3>
                            </div>
                        </a>
                    </div>

                    <?php
                }
            } else {
                // Categories not Available
                echo "<div class='error'>Category not Added.</div>";
            }
            ?>

        </div>
        <div class="clearfix"></div>
    </div>
</section>
<!-- Categories Section Ends Here -->

<!-- About Stall Section Starts Here -->
<section class="about-stall fade-in">
    <div class="container">
        <h2>About the Stalls</h2>

        <div class="card-container">
            <!-- R2K Stall -->
            <div class="card">
                <img src="images/r2kresize.jpg" alt="R2K Logo">
                <h3>R2K</h3>
                <p>R2K Stall specializes in a wide range of delicious cuisines, leaving you craving for more. Our expert chefs craft each dish with perfection to satisfy your cravings.</p>
            </div>

            <!-- CIA Stall -->
            <div class="card">
                <img src="images/cia.jpg" alt="CIA Logo">
                <h3>Chef In Action</h3>
                <p>CIA Stall is a food concession business that focuses on incorporating delicious western fusion cuisine to their meals, with multiple branches in different universities and schools. Serving delicious meals to students for an affordable price, and still providing an unlimited serving of rice.</p>
            </div>

            <!-- Graciously Stall -->
            <div class="card">
                <img src="images/Graciously.jpg" alt="Graciously Logo">
                <h3>Graciously FoodHub</h3>
                <p>Graciously Stall is a fast-paced food concession business, serving high-quality roasted food to the students of FEU Alabang.</p>
            </div>
        </div>
    </div>
</section>
<!-- About Stall Section Ends Here -->



<?php include('partials-front/footer.php'); ?>

<script>
       document.getElementById('scrollButton').addEventListener('click', function (e) {
        e.preventDefault();

        // Select all elements with the class "box-3 float-container" in the "categories" section
        const boxContainers = document.querySelectorAll('.categories .box-3.float-container');

        // Apply the "pulse" class to each box container
        boxContainers.forEach(function (boxContainer) {
            boxContainer.classList.add('pulse');

            // Remove the "pulse" class after 1.5 seconds (0.5s animation x 3 pulses)
            setTimeout(function () {
                boxContainer.classList.remove('pulse');
            }, 1500); // 1500 milliseconds (1.5 seconds)
        });

        // Scroll to the categories section
        const categoriesSection = document.getElementById('categories');
        categoriesSection.scrollIntoView({ behavior: 'smooth' });
    });
    </script>
