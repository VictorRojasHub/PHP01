<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login Admin</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h2>Login Admin</h2>
        <form action="login_admin.php" method="post">
            <label for="usuario">Usuário:</label><br>
            <input type="text" id="usuario" name="usuario" required><br>
            <label for="password">Senha:</label><br>
            <input type="password" id="password" name="password" required><br><br>
            <input type="submit" name="login" value="Entrar">
        </form>
    </div>
</body>
</html>
<?php
session_start();

if (isset($_POST['login'])) {
    $username = $_POST['usuario'];
    $password = $_POST['password'];

    if ($username === "admin" && $password === "admin") {
        // se conectou
        $_SESSION['admin_logged_in'] = true;
        header("Location: admin_page.php");
        exit;
    } else {
        echo "<p>Usuário ou senha inválidos!</p>";
    }
}
?>