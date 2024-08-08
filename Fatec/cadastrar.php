<?php
session_start();

$host = "localhost";
$user = "root";
$password = "";
$dbname = "fatec";

// conecta com o banco de dados
$conn = new mysqli($host, $user, $password, $dbname);

// ve se tá concetado
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ve se os dados foram enviados
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['registro'])) {
    $usuario = $_POST['usuario'];
    $cpf = $_POST['cpf'];

    // Verifica se o user ou o cpf já existe
    $sql = "SELECT id FROM tb_login WHERE usuario = ? OR cpf = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $usuario, $cpf);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $_SESSION['mensagem'] = "Erro: Usuário ou CPF já cadastrado!";
    } else {
        $email = $_POST['email'];
        $senha = md5($_POST['senha']); // Criptografia em MD5 ;)
        $nome = $_POST['nome'];
        $endereco = $_POST['endereco'];
        $data_nascimento = $_POST['data_nascimento'];
        $complemento = $_POST['complemento'];
        $bairro = $_POST['bairro'];
        $cidade = $_POST['cidade'];
        $uf = $_POST['uf'];
        $fone = $_POST['fone'];
        $cpf = $_POST['cpf'];

        // Prepara a inserção
        $stmt = $conn->prepare("INSERT INTO tb_login (usuario, email, senha, nome, endereco, cpf, data_nascimento, complemento, bairro, cidade, uf, fone) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssssssss", $usuario, $email, $senha, $nome, $endereco, $cpf, $data_nascimento, $complemento, $bairro, $cidade, $uf, $fone);
        
        if ($stmt->execute()) {
            header('Location: sucesso.php');
            exit();
        } else {
            $_SESSION['mensagem'] = "Erro ao cadastrar: " . $conn->error;
        }
    }

    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Contas - PHP</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php
    if (isset($_SESSION['mensagem'])) {
        echo "<p>" . $_SESSION['mensagem'] . "</p>";
        unset($_SESSION['mensagem']);
    }
    ?>

    <form name="form1" method="post" action="cadastrar.php">
        <label for="usuario">Usuário:</label><br>
        <input type="text" id="usuario" name="usuario" required><br>
        <label for="nome">Nome:</label><br>
        <input type="text" id="nome" name="nome" required><br>
        <label for="cpf">CPF:</label><br>
        <input type="text" id="cpf" name="cpf" required><br>
        <label for="data_nascimento">Data de Nascimento:</label><br>
        <input type="date" id="data_nascimento" name="data_nascimento" required><br>
        <label for="endereco">Endereço:</label><br>
        <input type="text" id="endereco" name="endereco" required><br>
        <label for="complemento">Complemento:</label><br>
        <input type="text" id="complemento" name="complemento" required><br>
        <label for="bairro">Bairro:</label><br>
        <input type="text" id="bairro" name="bairro" required><br>
        <label for="cidade">Cidade:</label><br>
        <input type="text" id="cidade" name="cidade" required><br>
        <label for="uf">UF:</label><br>
        <select id="uf" id="uf" name="uf" required><br>
                <option value="AC">AC</option>
                <option value="SP">SP</option>
                <option value="PA">PA</option>
        </select>
        <label for="fone">Fone:</label><br>
        <input type="text" id="fone" name="fone" required><br>
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br>
        <label for="senha">Senha:</label><br>
        <input type="password" id="senha" name="senha" required><br><br>
        <input type="submit" name="registro" value="Registrar">
    </form>
</body>
</html>