<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/style.css"> 
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <?php
        if (isset($_SESSION['mensagem'])) {
            echo "<p>" . $_SESSION['mensagem'] . "</p>";
            unset($_SESSION['mensagem']);
        }
        ?>
        <form action="login.php" method="post">
            <div class="form-group">
                <label for="usuario">Usuário:</label>
                <input type="text" id="usuario" name="usuario" required>
            </div>
            <div class="form-group">
                <label for="senha">Senha:</label>
                <input type="password" id="senha" name="senha" required>
            </div>
            <button type="submit" name="login">Entrar</button>
        </form>
    </div>
</body>
</html>

<?php
session_start();
$host = "localhost";
$user = "root";
$password = "";
$dbname = "fatec";

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $usuario = $_POST['usuario'];
    $senha = md5($_POST['senha']); 

    $sql = "SELECT * FROM tb_login WHERE usuario = ? AND senha = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $usuario, $senha);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        // Logou
        $user = $result->fetch_assoc();
        $_SESSION['usuario'] = $user['usuario']; 
        $_SESSION['id_usuario'] = $user['id']; 
        header("Location: perfil.php");
        exit();
    } else {
        // Login falhou
        $_SESSION['mensagem'] = "Usuário ou senha inválidos!";
    }

    $stmt->close();
}
$conn->close();
?>
