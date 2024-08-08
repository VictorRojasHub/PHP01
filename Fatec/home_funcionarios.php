<?php
session_start();

$host = "localhost";
$user = "root";
$password = "";
$dbname = "folha_pagto";

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['registro'])) {
    $n_registros = (int)$_POST['n_registros'];
    $nome_funcionario = $_POST['nome_funcionario'];
    $data_admissao = $_POST['data_admissao'];
    $cargo = $_POST['cargo'];
    $qtde_salarios = (float)$_POST['qtde_salarios'];
    $salario_bruto = $qtde_salarios * 1420.00;
    $desconto_inss = 0.11;
    $inss = 0;
    if ($salario_bruto >= 1550.00){
        $inss = $salario_bruto * $desconto_inss;
        $salario_liquido = $salario_bruto - $inss;
    } else {
        $salario_liquido = $salario_bruto;
    }
    
    $sql = "SELECT * FROM tb_funcionarios WHERE n_registros = ? OR nome_funcionario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $n_registros, $nome_funcionario);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $_SESSION['mensagem'] = "Erro: numero de registro ou nome já cadastrados!";
    } else {

        $stmt = $conn->prepare("INSERT INTO tb_funcionarios (n_registros, nome_funcionario, data_admissao, cargo, qtde_salarios, salario_bruto, inss, salario_liquido) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssdddd", $n_registros, $nome_funcionario, $data_admissao, $cargo, $qtde_salarios, $salario_bruto, $inss, $salario_liquido);
        
        if ($stmt->execute()) {
            $_SESSION['mensagem'] = "Registro inserido com sucesso!";
            header('Location: gravar.php');
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
    <title>Página Inicial</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>CADASTRO DE FUNCIONÁRIOS</h1>
        <?php
    if (isset($_SESSION['mensagem'])) {
        echo "<p>" . $_SESSION['mensagem'] . "</p>";
        unset($_SESSION['mensagem']);
    }
    ?>
        <form name="form1" method="post" action="home_funcionarios.php">
        <label for="n_registros">Nº REGISTRO</label><br>
        <input type="number" id="n_registros" name="n_registros" required><br>
        <label for="nome_funcionario">NOME DO FUNCIONÁRIO</label><br>
        <input type="text" id="nome_funcionario" name="nome_funcionario" required><br>
        <label for="data_admissao">DATA DE ADMISSÃO</label><br>
        <input type="date" id="data_admissao" name="data_admissao" required><br>
        <label for="cargo">CARGO</label><br>
        <select id="cargo" name="cargo" required><br>
                <option value="escolha">Escolha</option>
                <option value="operador_de_loja">Operador de Loja</option>
                <option value="encarregado">Encarregado</option>
                <option value="gerente">Gerente</option>
                <option value="supervisor">Supervisor</option>
                <option value="diretor">Diretor</option>
                <option value="ceo">CEO</option>
        </select>
        <label for="qtde_salarios">QTDE DE SALÁRIOS MÍNIMOS</label>
        <input type="text" id="qtde_salarios" name="qtde_salarios" required>
        <br><br>
        <input type="submit" name="registro" value="Registrar"><br><br>
        <a href="listagem.php">VISUALIZAR DEMONSTRATIVOS DE PAGAMENTOS</a><br>
        <a href="admin_page.php">Voltar</a>
        </form>
    </div>
</body>
</html>
