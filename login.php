<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        form {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
        }

        h1 {
            margin-bottom: 20px;
            color: #333;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #3498db;
            color: #fff;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #2980b9;
        }

        button {
            background-color: #e74c3c;
            color: #fff;
            cursor: pointer;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
        }

        button:hover {
            background-color: #c0392b;
        }

        .message {
            color: #e74c3c;
            margin-top: 10px;
        }
    </style>
    <script>
        function cancella() {
            document.getElementById("username").value = "";
            document.getElementById("password").value = "";
        }
    </script>
</head>
<body>
    <?php 
        if(isset($_GET["msg"])){
            echo '<div class="message">' . $_GET["msg"] . '</div>';
        }
    ?>

    <form action="checkLogin.php" method="post">
        <h1>Login</h1>
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" value="" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" value="" required>

        <input type="submit" value="Login" name="login">
        <button type="button" onclick="cancella();">Cancella</button>
    </form>
</body>
</html>
