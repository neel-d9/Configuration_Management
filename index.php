<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bokor&display=swap" rel="stylesheet">
</head>

<body>
    <div class="login-form-container">
        <h1 class="index-name">Alpine Technologies</h1>
        <div class="small-container" id="signIn">
            <h1 class="form-title">Sign In</h1>
            <form method="post" action="register.php">
                <div class="input-group">
                    <i class="fa-solid fa-user"></i>
                    <input type="username" name="username" id="username" placeholder="Username" required> 
                </div>
                <br>
                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" id="password" placeholder="Password" required>
                </div>
                <br>
                <input type="submit" class="btn" value="Sign In" name="signIn">
            </form>
        </div>
    </div>
    <script src="script.js"></script>
</body>

</html>