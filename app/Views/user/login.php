<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login User</title>
</head>

<body>
    <form action="/user/auth" method="post">
        Username : <input type="text" name="username">
        Password : <input type="password" name="password">
        <button type="submit">Login</button>
    </form>
</body>

</html>