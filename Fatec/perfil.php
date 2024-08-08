<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.html");
    exit;
}

$host = "localhost";
$user = "root";
$password = "";
$dbname = "fatec";
$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

$id_usuario = $_SESSION['id_usuario'];

$sql = "SELECT usuario, email, nome, endereco, cpf, data_nascimento, complemento, bairro, cidade, uf, fone FROM tb_login WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $dados_usuario = $result->fetch_assoc();
} else {
    echo "Nenhum usuário encontrado";
    exit;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Perfil do Usuário</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>Perfil do Usuário</h1>
        <form>
            <button type="button" onclick="mostrarPerfil()">Visualizar Meu Perfil</button>
        </form>
        <div id="dadosPerfil" style="display:none;">
            <p>Usuário: <?php echo $dados_usuario['usuario']; ?></p>
            <p>Email: <?php echo $dados_usuario['email']; ?></p>
            <p>Nome: <?php echo $dados_usuario['nome']; ?></p>
            <p>CPF: <?php echo $dados_usuario['cpf']; ?></p>
            <p>Data de Nascimento: <?php echo $dados_usuario['data_nascimento']; ?></p>
            <p>Fone: <?php echo $dados_usuario['fone']; ?></p>
            <p>Endereço: <?php echo $dados_usuario['endereco']; ?></p>
            <p>Bairro: <?php echo $dados_usuario['bairro']; ?></p>
            <p>Complemento: <?php echo $dados_usuario['complemento']; ?></p>
            <p>Cidade: <?php echo $dados_usuario['cidade']; ?></p>
            <p>UF: <?php echo $dados_usuario['uf']; ?></p>
            
        </div>
    </div>

    <script>
        function mostrarPerfil() {
            var x = document.getElementById("dadosPerfil");
            if (x.style.display === "none") {
                x.style.display = "block";
            } else {
                x.style.display = "none";
            }
        }
    </script>
</body>
</html>
