<?php include('partials-front/menu.php'); ?>

<?php 
    if(isset($_GET['category_id']))
    {
        $category_id = $_GET['category_id'];
        $sql = "SELECT title FROM tbl_category WHERE id=$category_id";
        $res = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($res);
        $category_title = $row['title'];
    }
    else if(isset($_POST['search']))
    {
        $search = mysqli_real_escape_string($conn, $_POST['search']);
        $category_title = "Your Search: \"$search\"";
    }
    else
    {
        // Handle the case where no category_id is set. You may want to redirect or show an error message.
    }
?>

<section class="food-search text-center">
    <div class="container">
       <?php
        // Check the value of $restaurant_id and display the corresponding image or text
        if ($category_id == 1) {
            echo '<img src="r2k.jpg" alt="R2K" class="img-responsive img-curve" style="max-width: 40%">';
        } elseif ($category_id == 2) {
            echo '<img src="Graciously.jpg" alt="Graciously" class="img-responsive img-curve" style="max-width: 40%">';
        } elseif ($category_id == 3) {
            echo '<img src="cia.jpg" alt="Graciously" class="img-responsive img-curve" style="max-width: 40%">';
        } else {
            // Default text when $restaurant_id doesn't match any of the conditions
            echo 'No Image Available';
        }
        ?>
        <form action="<?php echo SITEURL; ?>food-search.php" method="POST">
            <input type="search" name="search" id="food-search-input" placeholder="Search for Food.." required>
            
        </form>
        <div id="food-search-results"></div>
    </div>
</section>

<section class="food-menu">
    <div class="container">
        <h2 class="text-center">Food Menu</h2>

        <div id="animation-container">
            <div id="food-menu-container"> <!-- Container for food menu -->
            <?php
            // Display all food items initially
            $search = isset($_POST['search']) ? mysqli_real_escape_string($conn, $_POST['search']) : '';
            $sql2 = "SELECT * FROM tbl_food WHERE category_id=$category_id AND (stocks > 0 OR title LIKE '%$search%')";
            $res2 = mysqli_query($conn, $sql2);
            $count2 = mysqli_num_rows($res2);

            if ($count2 > 0) {
                while ($row2 = mysqli_fetch_assoc($res2)) {
                    $id = $row2['id'];
                    $title = $row2['title'];
                    $price = $row2['price'];
                    $description = $row2['description'];
                    $image_name = $row2['image_name'];
                    $stocks = $row2['Stocks'];

                    $isAvailable = $stocks > 0;
                    ?>

                    <div class="food-menu-box <?php echo $isAvailable ? '' : 'unavailable'; ?>">
                        <div class="food-menu-img">
                            <?php
                            if ($image_name == "") {
                                echo "<div class='error'>Image not Available.</div>";
                            } else {
                                ?>
                                <img src="<?php echo SITEURL; ?>images/<?php echo $image_name; ?>"
                                    alt="<?php echo $title; ?>" class="img-responsive img-curve">
                                <?php
                            }
                            ?>
                        </div>

                        <div class="food-menu-desc">
                            <h4><?php echo $title; ?></h4>
                            <p class="food-price">₱<?php echo $price; ?></p>
                            <p class="food-detail">
                                <?php echo $description; ?>
                            </p>
                            <br>

                            <?php if ($isAvailable) { ?>
                                <a href="<?php echo SITEURL; ?>order.php?food_id=<?php echo $id; ?>" class="btn btn-primary">Order Now</a>
                            <?php } else { ?>
                                <span class="unavailable-label">Not Available</span>
                            <?php } ?>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo "<div class='error'>Food not found.</div>";
            }
            ?>
        </div>
        <div class="clearfix"></div>
    </div>
</section>

<style>
    /* Add this CSS to your stylesheet */
    .unavailable {
        filter: grayscale(100%); /* Gray out the image */
    }

    .unavailable .unavailable-label {
        background: rgba(0, 0, 0, 0.7); /* Semi-transparent background for label */
        color: white;
        padding: 5px;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        border-radius: 5px;
        pointer-events: none; /* Prevent interactions with the label */
    }

    /* CSS Animation for Fade-in */
@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

#animation-container {
    animation: fadeIn 0.5s ease-in-out; /* Adjust the animation duration as needed */
    opacity: 0;
}

</style>

<script>
    const searchInput = document.getElementById('food-search-input');
    const foodMenuContainer = document.getElementById('food-menu-container');

    searchInput.addEventListener('input', function () {
        const searchText = this.value.trim();
        const category_id = <?php echo $category_id; ?>;

        // Check if there is a search text
        if (searchText !== '') {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'filter-food.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    foodMenuContainer.innerHTML = xhr.responseText;
                }
            };
            xhr.send('search=' + searchText + '&category_id=' + category_id);
        } else {
            // Display all food items when the search input is empty
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'filter-food.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    foodMenuContainer.innerHTML = xhr.responseText;
                }
            };
            xhr.send('search=&category_id=' + category_id);
        }
    });

</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const animationContainer = document.getElementById('animation-container');
        animationContainer.style.opacity = '1';
    });
</script>


<?php include('partials-front/footer.php'); ?>
