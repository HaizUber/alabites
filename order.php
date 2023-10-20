<style>
/* The Modal (background) */
.modal {
    display: none;
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.7);
    text-align: center;
    display: flex;
    justify-content: center;
    align-items: center;
}

/* Modal Content */
.modal-content {
  position: relative;
  background-color: #fefefe;
  margin: auto;
  top: 20;
  padding: 20px;
  border: 2px solid #444; /* Add border for a receipt-like look */
  border-radius: 10px; /* Rounded edges */
  width: 80%;
  max-width: 400px; /* Smaller box */
  box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
  animation-name: animatetop;
  animation-duration: 0.4s;
}

/* Add Animation */
@keyframes animatetop {
  from { top: -300px; opacity: 0; }
  to { top: 0; opacity: 1; }
}

/* The Close Button */
.close {
  color: black;
  float: right;
  font-size: 28px;
  font-weight: bold;
}

.close:hover,
.close:focus {
  color: green;
  text-decoration: none;
  cursor: pointer;
}

/* Modal Header */
.modal-header {
  padding: 2px 16px;
  background-color: #5cb85c;
  color: white;
}

/* Modal Body */
.modal-body {
  padding: 2px 16px;
}

/* Modal Footer */
.modal-footer {
  padding: 2px 16px;
  background-color: #5cb85c;
  color: white;
}

/* Modal Title */
.modal-title {
  font-size: 24px;
  color: #333;
  margin-bottom: 10px;
}

/* Modal Reference Number */
.modal-reference {
  font-size: 18px;
  color: #444;
}

@media (max-width: 768px) {
  .modal-content {
    max-width: 90%;
  }
  .modal-title {
    font-size: 20px;
  }
  .modal-reference {
    font-size: 16px;
  }
}

.btn.btn-primary {
  background-color: #5cb85c;
  color: white;
  font-size: 16px;
  border: none;
  padding: 10px 20px;
  border-radius: 5px;
  cursor: pointer;
  transition: background-color 0.2s;
}

.btn.btn-primary:disabled {
  background-color: gray;
  cursor: not-allowed;
  pointer-events: none; /* Disable hover when button is disabled */
}

.btn.btn-primary:not(:disabled):hover {
  background-color: #449d44; /* Color change on hover only when enabled */
}
</style>


<?php
// Check if the website is closed based on time
$timezone = new DateTimeZone('Asia/Manila');
$currentDateTime = new DateTime(null, $timezone);
$startTime = 00; // 9am (09:00:00)
$endTime = 23; // 5pm (17:00:00)

$currentHour = (int) $currentDateTime->format('H'); // Get the current hour in 24-hour format

$websiteClosed = ($currentHour < $startTime || $currentHour >= $endTime);

if ($websiteClosed) {
    // Display the closed banner
    echo '<div style="background-color: #ff0000; color: #ffffff; text-align: center; padding: 10px;">
            <h3>Website Closed</h3>
            <p>We apologize for the inconvenience. But all the Stalls are closed for now. Please come back between 9am and 5pm.</p>
          </div>';
} else {
    // Website is open, display regular content
    include('partials-front/menu.php');

    // Rest of your code goes here...
?>

<!-- Your HTML content here -->

<?php
    // End of the website open condition
}
?>


 <?php
// Check whether food id is set or not
if (isset($_GET['food_id'])) {
    // Get the Food id and details of the selected food
    $food_id = $_GET['food_id'];

    // Get the Details of the Selected Food
    $sql = "SELECT * FROM tbl_food WHERE id=$food_id";
    // Execute the Query
    $res = mysqli_query($conn, $sql);
    // Count the rows
    $count = mysqli_num_rows($res);
    // Check whether the data is available or not
    if ($count == 1) {
        // We have data
        // Get the Data from Database
        $row = mysqli_fetch_assoc($res);

        $title = $row['title'];
        $price = $row['price'];
        $image_name = $row['image_name'];
        $Stocks = $row['Stocks'];
        $sold = $row['sold'];
    } else {
        // Food not available
        // Redirect to Home Page
        header('location:' . SITEURL);
        exit(); // Add this line to stop executing the rest of the code
    }
} else {
    // Redirect to homepage
    header('location:' . SITEURL);
    exit(); // Add this line to stop executing the rest of the code
}
?>

<!-- fOOD sEARCH Section Starts Here -->
<section class="food-search">
    <div class="container">
        <!-- Rest of the code... -->
        <h2 class="text-center text-white">Fill this form to confirm your order.</h2>

        <form action="" method="POST" class="order">
            <fieldset>
                <legend>Selected Food</legend>

                <div class="row">
                    <div class="col-md-6">
                        <div class="food-menu-img">
                            <?php
                            // Check whether the image is available or not
                            if ($image_name == "") {
                                // Image not Available
                                echo "<div class='error'>Image not Available.</div>";
                            } else {
                                // Image is Available
                                ?>
                                <img src="<?php echo SITEURL; ?>images/<?php echo $image_name; ?>" alt="food" class="img-responsive img-curve">
                            <?php
                            }
                            ?>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="food-menu-desc">
                            <h3><?php echo $title; ?></h3>
                            <input type="hidden" name="food" value="<?php echo $title; ?>">
                            <div class="order-label">Stocks</div>
                            <p><?php echo $Stocks; ?></p>
                            <p class="food-price">₱<?php echo $price; ?></p>
                            <input type="hidden" name="price" value="<?php echo $price; ?>">

                            <div class="order-label">Quantity</div>
                            <input type="number" name="qty" class="input-responsive" value="1" min="1" max="<?php echo $Stocks; ?>" required>
                            <input type="hidden" name="sold" value="<?php echo $sold; ?>">
                            <div class="order-total">Total: ₱<span id="total"></span></div>
                        </div>
                    </div>
                </div>

                <script>
                    // Get the elements
                    const priceElement = document.querySelector('.food-price');
                    const qtyElement = document.querySelector('input[name="qty"]');
                    const totalElement = document.getElementById('total');

                    // Retrieve the price from the HTML
                    const price = parseFloat(priceElement.textContent.replace('₱', ''));

                    // Function to calculate and display the total
                    function calculateTotal() {
                        const qty = parseInt(qtyElement.value);
                        const total = price * qty;
                        totalElement.textContent = total.toFixed(2); // Display total with 2 decimal places
                    }

                    // Calculate the initial total
                    calculateTotal();

                    // Add event listener to recalculate the total whenever the quantity changes
                    qtyElement.addEventListener('input', calculateTotal);
                </script>

            </fieldset>

            <fieldset>
                <legend>Order Details</legend>

                <?php
                // Check whether food id is set or not
                if (isset($_GET['food_id'])) {
                    // Get the Food id and details of the selected food
                    $food_id = $_GET['food_id'];

                    // Get the Details of the Selected Food
                    $sql = "SELECT * FROM tbl_food WHERE id=$food_id";
                    // Execute the Query
                    $res = mysqli_query($conn, $sql);
                    // Count the rows
                    $count = mysqli_num_rows($res);
                    // Check whether the data is available or not
                    if ($count == 1) {
                        // We have data
                        // Get the Data from Database
                        $row = mysqli_fetch_assoc($res);

                        $restaurant_id = $row['category_id'];
                    } else {
                        // Food not available
                        // Redirect to Home Page
                        header('location:' . SITEURL);
                        exit(); // Add this line to stop executing the rest of the code
                    }
                } else {
                    // Redirect to homepage
                    header('location:' . SITEURL);
                    exit(); // Add this line to stop executing the rest of the code
                }
                ?>

                <!-- which stall section... -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="order-label">Stall GCASH Number</div>
                        <select name="restaurant_id2" class="input-responsive" required disabled>
                            <option value="">Select a Stall</option>
                            <option value="1" <?php if ($restaurant_id == 1) echo "selected"; ?>>R2K [09477102348] </option>
                            <option value="3" <?php if ($restaurant_id == 3) echo "selected"; ?>>CIA[09455001603]</option>
                            <option value="2" <?php if ($restaurant_id == 2) echo "selected"; ?>>Graciously FoodHub[09175019624]</option>
                        </select>
                        <div class="col-md-6">

                        <div class="food-menu-img">
    <br>
    <div id="qrCodeImage">
        <?php
        // Check the value of $restaurant_id and display the corresponding image or text
        if ($restaurant_id == 1) {
            echo '<img src="r2kgcashqrcode.jpg" alt="R2K GCash QR Code" class="img-responsive img-curve">';
            echo '<a href="r2kgcashqrcode.jpg" download="r2kgcashqrcodee.jpg">Download QR Code</a>';
        } elseif ($restaurant_id == 2) {
            echo '<img src="graciouslygcashqrcode.jpg" alt="Graciously GCash QR Code" class="img-responsive img-curve">';
            echo '<a href="graciouslygcashqrcode.jpg" download="graciouslygcashqrcodee.jpg">Download QR Code</a>';
        } else {
            // Default text when $restaurant_id doesn't match any of the conditions
            echo 'No QR Code Available';
        }
        ?>
    </div>
</div>

</div>

                        <select name="restaurant_id" class="input-responsive" required hidden>
                            <option value="">Select a Stall</option>
                            <option value="1" <?php if ($restaurant_id == 1) echo "selected"; ?>>R2K [09477102348]</option>
                            <option value="3" <?php if ($restaurant_id == 3) echo "selected"; ?>>CIA[09455001603]</option>
                            <option value="2" <?php if ($restaurant_id == 2) echo "selected"; ?>>Graciously FoodHub[09175019624]</option>
                        </select>
                    </div>
                </div>

                <!-- end of which stall section... -->

                <br><br><br><br><br><br><br><br><br><br><br><br><br>
                <div class="order-label">1.Name</div>
<input type="text" name="full-name" placeholder="E.g. John Doe" class="input-responsive" required>

<div class="order-label">2.Phone Number</div>
<input type="tel" name="contact" placeholder="E.g. 9843xxxxxx" class="input-responsive" required disabled>

<div class="order-label">3.Email</div>
<input type="email" name="email" placeholder="E.g. 202010924@feualabang.edu.ph" class="input-responsive" required disabled>

<div class="order-label">4.Reference Number (GCASH)</div>
<input type="text" name="address" placeholder="0010596664511" class="input-responsive" required disabled>
<div class="order-label">5.Accept Terms and Conditions</div>
<input type="checkbox" name="terms_acceptance" required>
<label for="terms_acceptance">I accept the <a href="help.php" target="_blank">Terms and Conditions</a></label>

<input type="submit" name="submit" value="Confirm Order" class="btn btn-primary" disabled>

<script>
// Get the elements
const nameInput = document.querySelector('input[name="full-name"]');
const phoneInput = document.querySelector('input[name="contact"]');
const emailInput = document.querySelector('input[name="email"]');
const referenceInput = document.querySelector('input[name="address"]');
const termsCheckbox = document.querySelector('input[name="terms_acceptance"]');
const confirmButton = document.querySelector('input[name="submit"]'); // Updated to select the input element

// Initially disable all fields and the "Order Now" button
phoneInput.setAttribute('disabled', 'true');
emailInput.setAttribute('disabled', 'true');
referenceInput.setAttribute('disabled', 'true');
confirmButton.setAttribute('disabled', 'true'); // Disable the "Confirm Order" input

// Add input event listeners to all fields and the terms checkbox
const inputFields = [nameInput, phoneInput, emailInput, referenceInput, termsCheckbox];

inputFields.forEach((field, index) => {
    field.addEventListener('input', () => {
        if (areAllFieldsFilled() && termsCheckbox.checked) {
            confirmButton.removeAttribute('disabled');
        } else {
            confirmButton.setAttribute('disabled', 'true');
        }

        if (field.value) {
            if (index < inputFields.length - 1) {
                inputFields[index + 1].removeAttribute('disabled');
            }
        } else {
            inputFields.slice(index + 1).forEach((disabledField) => {
                disabledField.setAttribute('disabled', 'true');
            });
        }
    });
});

// Check if all fields are filled and the terms checkbox is checked
function areAllFieldsFilled() {
    return inputFields.every((field) => field.value) && termsCheckbox.checked;
}

</script>



                    <?php
                    // Check whether submit button is clicked or not
                    if (isset($_POST['submit'])) {
                        // Get all the details from the form
                        $food = $_POST['food'];
                        $price = $_POST['price'];
                        $qty = $_POST['qty'];
                        $restaurant_id = $_POST['restaurant_id'];
                        $total = $price * $qty; // total = price x qty 

                        // Check if the quantity exceeds the available stocks
                        if ($qty > ($Stocks)) {
                            echo '<div class="error text-center">Quantity exceeds available stocks. Please enter a lower quantity.</div>';
                            exit; // Stop executing the rest of the code
                        }
                        // Set the desired time zone
                        $timezone = new DateTimeZone('Asia/Manila');
                        $currentDateTime2 = new DateTime(null, $timezone);
                        $order_date = $currentDateTime2->format("Y-m-d H:i:s"); // Convert DateTime to string

                        $status = "Pending Order";  // Ordered, On Delivery, Delivered, Cancelled

                        $customer_name = $_POST['full-name'];
                        $customer_contact = $_POST['contact'];
                        $customer_email = $_POST['email'];
                        $customer_address = $_POST['address'];

                        // Check if the reference number has at least 10 digits
                        if (strlen($customer_address) < 10 || strlen($customer_address) > 13) {
                            echo '<div class="error text-center">Reference Number must have at least 10-13 digits.</div>';
                            exit; // Stop executing the rest of the code
                        }

                        // Check if the mobile number has exactly 11 digits
                        if (strlen($customer_contact) !== 11) {
                            echo '<div class="error text-center">Mobile Number must have exactly 11 digits.</div>';
                            exit; // Stop executing the rest of the code
                        }
                        // Save the Order in Database
                        // Create SQL to save the data
                        $sql2 = "INSERT INTO tbl_order (food, price, qty, total, order_date, status, customer_name, customer_contact, customer_email, customer_address, restaurant_id) VALUES 
                ('$food', $price, $qty, $total, '$order_date', 'Pending', '$customer_name', '$customer_contact', '$customer_email', '$customer_address', '$restaurant_id')
            ";

    // Execute the Query
    $res2 = mysqli_query($conn, $sql2);

    if ($res2) {
        // Get the reference number from the latest entry
        $sql3 = "SELECT customer_address FROM tbl_order ORDER BY order_date DESC LIMIT 1";
        $result = mysqli_query($conn, $sql3);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $referenceNumber = $row['customer_address'];

            // Display the modal after a successful order
            echo '<script>
                window.addEventListener("load", function() {
                    var modal = document.getElementById("myModal");
                    var referenceNumberElement = document.getElementById("referenceNumber");
                    var closeModal = document.getElementById("closeModal");

                    // Show the modal
                    modal.style.display = "block";

                    referenceNumberElement.textContent = "' . $referenceNumber . '";

                    closeModal.addEventListener("click", function() {
                        modal.style.display = "none";
                        window.location.href = "' . SITEURL . '"; // Redirect when the modal is closed
                    });
                });
            </script>';
            // Redirect to the homepage
            echo '<!-- The Modal -->
            <div id="myModal" class="modal">
                <div class="modal-content">
                    <span class="close" id="closeModal">&times;</span>
                    <h2 class="modal-title">Thank You for Your Order</h2>
                    <p>Your order was successful. Please make sure to screenshot this page for verification.</p>
                    <p class="modal-reference">Your reference number is: <span id="referenceNumber"></span></p>
                </div>
            </div>';
        }
        
    } else {
        // Failed to Save Order
        $_SESSION['order'] = "<div class='error text-center'>Failed to Order Food.</div>";
        header('location:' . SITEURL);
        exit; // Terminate the script
    }
}
?>
                    <!-- end of reference number section... -->
                </div>
            </fieldset>
        </form>
        <?php
        include('partials-front/footer.php');
        ?>






      