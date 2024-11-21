<?php
session_start();
require_once 'functions.php'; // Include external functions

// Redirect to dashboard if already logged in


// Handle login form submission
$email = $password = ""; // Initialize variables
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $email = postData('email');
    $password = postData('password');
    login($email, $password); // Attempt to login
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Login</title>
</head>

<body class="bg-light">
    <div class="d-flex align-items-center justify-content-center vh-100">
        <div class="col-md-4">
            <!-- Validation and alert messages -->
            <?php if (!empty($errors)) echo displayErrors($errors); ?>

            <div class="card shadow-sm">
                <div class="card-body">
                    <h1 class="h3 mb-4 text-center">Login</h1>
                    <form method="post" novalidate>
                        <div class="form-floating mb-3">
                            <input 
                                type="text" 
                                class="form-control" 
                                id="email" 
                                name="email" 
                                placeholder="user@example.com" 
                                value="<?php echo htmlspecialchars($email); ?>" 
                                required>
                            <label for="email">Email address</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input 
                                type="password" 
                                class="form-control" 
                                id="password" 
                                name="password" 
                                placeholder="Password" 
                                value="<?php echo htmlspecialchars($password); ?>" 
                                required>
                            <label for="password">Password</label>
                        </div>
                        <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>