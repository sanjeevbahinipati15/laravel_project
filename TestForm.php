<?php


//variable initialization
$error=[];
$success=false;
$name = '';
$email = '';
$phone = '';
$message = '';

//database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "test";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}



 if($_SERVER['REQUEST_METHOD'] == 'POST'){
        //echo "Form Submitted";

        //sanitize & trim raw input to prevent sql injection
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $phone = trim($_POST['phone']);
        $message = trim($_POST['message']);

        if(empty($name)){
            $error['name'] = 'Name is required';
        }

        if(empty($email)){
            $error['email'] = 'Email is required';
        }elseif(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
            $error['email'] = 'Invalid email';
        }

        if(empty($phone)){
            $error['phone'] = 'Phone is required';
        }
        elseif(!preg_match('/^[0-9]{10}$/', $phone)){
            $error['phone'] = 'phone must be 10 digits';
        }

        if(empty($message)){
            $error['message'] = 'Message is required';
        }

        // $name = $_POST['name'];
        // $email = $_POST['email'];
        // $phone = $_POST['phone'];
        // $message = $_POST['message'];

        // echo "Name: $name";
        // echo "Email: $email";
        // echo "Phone: $phone";
        // echo "Message: $message";

        if(empty($error)){
            $success=true;

            $sql = "INSERT INTO contacts (name, email, phone, message) VALUES ('$name', '$email', '$phone', '$message')";
            if($conn->query($sql) === TRUE){
                echo "New record created successfully";
            }else{
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    }

?>

<!Doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

    <?php
    if($success){ ?>
        <div style="color: green;">Form Submitted Successfully</div>
        <strong>Name: </strong><?php echo htmlspecialchars($name); ?><br>
        <strong>Email: </strong><?php echo htmlspecialchars($email); ?><br>
        <strong>Phone: </strong><?php echo $_POST['phone']; ?><br>
        <strong>Message: </strong><?php echo $_POST['message']; ?><br>

    <?php
    }
    ?>

    <form action="" method="POST">
        <div style="margin-bottom: 10px; margin-top: 10px;">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($name); ?>">
            <?php
            if(!empty($error['name'])){
                echo '<div style="color: red;">'.$error['name'].'</div>';
            }
            ?>
        </div>
        <div style="margin-bottom: 10px;">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" value="<?php echo $email; ?>">
            <?php
            if(!empty($error['email'])){
                echo '<div style="color: red;">'.$error['email'].'</div>';
            }
            ?>

        </div>
        <div style="margin-bottom: 10px;">
            <label for="phone">Phone</label>
            <input type="tel" name="phone" id="phone" value="<?php echo $phone; ?>">
            <?php
            if(!empty($error['phone'])){
                echo '<div style="color: red;">'.$error['phone'].'</div>';
            }
            ?>

        </div>
        <div style="margin-bottom: 10px;">
            <label for="message">Message</label>
            <textarea name="message" id="message" rows="5" cols="50"><?php echo $message; ?></textarea>
            <?php
            if(!empty($error['message'])){
                echo '<div style="color: red;">'.$error['message'].'</div>';
            }
            ?>
        </div>
        <div>
            <button type="submit">Submit</button>
            <a href="TestForm.php" id="reset">Reset</a>
        </div>
    </form>

</body>
</html>

