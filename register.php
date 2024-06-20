<?php
include('config.php');

$message = ''; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validation checks
    $errors = [];
    
    if (!preg_match("/^[a-zA-Z]+$/", $username)) {
        $errors['username'] = "Username should contain only letters.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format.";
    }

    if (!preg_match("/^[0-9]+$/", $password)) {
        $errors['password'] = "Password should contain only numbers.";
    }

    if (empty($errors)) {
        $password = password_hash($password, PASSWORD_BCRYPT);

        // Check if the email already exists
        $checkEmailSql = "SELECT email FROM users WHERE email = ?";
        $checkEmailStmt = $conn->prepare($checkEmailSql);
        $checkEmailStmt->bind_param("s", $email);
        $checkEmailStmt->execute();
        $checkEmailStmt->store_result();

        if ($checkEmailStmt->num_rows > 0) {
            $message = "This email ID already exists";
        } else {
            // Proceed with the registration
            $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $username, $email, $password);

            if ($stmt->execute()) {
                $message = "Registration successful!";
            } else {
                $message = "Error: " . $stmt->error;
            }
        }

        $checkEmailStmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
<section class="vh-100" style="background-color: #508bfc;">
  <div class="container py-5 h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col-12 col-md-8 col-lg-6 col-xl-5">
        <div class="card shadow-2-strong" style="border-radius: 1rem;">
          <div class="card-body p-5 text-center">
            <h3 class="mb-5">Sign in</h3>
            <form method="post" onsubmit="return validateForm();">
              <div data-mdb-input-init class="form-outline mb-4 text-start">
                <label class="form-label" for="username">Username</label>
                <input type="text" id="username" class="form-control form-control-lg" name="username" onkeyup="validateForm();" required />
                <span id="usernameError" style="color:red"></span><br>
              </div>
              <div data-mdb-input-init class="form-outline mb-4 text-start">
                <label class="form-label" for="email">Email</label>
                <input type="email" id="email" class="form-control form-control-lg" name="email" onkeyup="validateForm(); checkEmailExistence(this.value);" required />
                <span id="emailError" style="color:red"></span><br>
              </div>
              <div data-mdb-input-init class="form-outline mb-4 text-start">
                <label class="form-label" for="password">Password</label>
                <input type="password" id="password" class="form-control form-control-lg" name="password" onkeyup="validateForm();" required/>
                <span id="passwordError" style="color:red"></span><br>
              </div>
              <button data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-lg btn-block" type="submit" >Register</button>
            </form>
            <p class="mt-3"><a href="login.php" class="text-primary ">Go to login page</a></p>
            <?php if ($message): ?>
                <div class="mt-3 alert alert-info"><?php echo $message; ?></div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
<script>
        function validateForm() {
            var username = document.getElementById("username").value;
            var email = document.getElementById("email").value;
            var password = document.getElementById("password").value;
            var error = false;

            // Clear previous error messages
            document.getElementById("usernameError").innerText = "";
            document.getElementById("emailError").innerText = "";
            document.getElementById("passwordError").innerText = "";

            // Validate username
            if (!/^[a-zA-Z]+$/.test(username)) {
                document.getElementById("usernameError").innerText = "Username should contain only letters.";
                error = true;
            }

            // Validate email
            if (!/^\S+@\S+\.\S+$/.test(email)) {
                document.getElementById("emailError").innerText = "Invalid email format.";
                error = true;
            }

            // Validate password
            if (!/^[0-9]+$/.test(password)) {
                document.getElementById("passwordError").innerText = "Password should contain only numbers.";
                error = true;
            }

            return !error; // Prevent form submission if there's an error
        }

        function checkEmailExistence(email) {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "check_email.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    if (xhr.responseText === "exists") {
                        document.getElementById("emailError").innerText = "This email ID already exists.";
                    }
                }
            };
            xhr.send("email=" + encodeURIComponent(email));
        }
    </script>
</body>
</html>
<!-- <form method="post">
    Username: <input type="text" name="username" required><br>
    Email: <input type="email" name="email" required><br>
    Password: <input type="password" name="password" required><br>
    <button type="submit">Register</button>
</form> -->
