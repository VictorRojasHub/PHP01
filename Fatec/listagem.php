<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "folha_pagto";
$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$search = isset($_GET['search']) ? $_GET['search'] : '';
$searchQuery = "SELECT * FROM tb_funcionarios WHERE n_registros LIKE ? OR nome_funcionario LIKE ?";
$stmt = $conn->prepare($searchQuery);
$searchTerm = "%" . $search . "%";
$stmt->bind_param("ss", $searchTerm, $searchTerm);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Administração - Usuários</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .search-box {
            margin-bottom: 20px;
        }
        .search-box input[type="text"], .search-box input[type="submit"] {
            padding: 8px;
        }
    </style>
</head>
<body>

<h2>Administração - Pesquisar Usuários</h2>
<div class="search-box">
    <form action="" method="GET">
        <input type="text" name="search" placeholder="Pesquisar por nº de registro ou nome" value="<?php echo $search; ?>">
        <input type="submit" value="Filtrar">
    </form>
    <a href="admin_page.php"><button class btn>Voltar</button></a>
</div>
<table>
    <thead>
        <tr>
            <th>NºREGISTRO</th>
            <th>NOME</th>
            <th>DATA ADMISSAO</th>
            <th>CARGO</th>
            <th>QTD SALARIOS</th>
            <th>SALARIO BRUTO</th>
            <th>INSS</th>
            <th>SALARIO LIQUIDO</th>
            <th>Apagar</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['n_registros']; ?></td>
            <td><?php echo $row['nome_funcionario']; ?></td>
            <td><?php echo $row['data_admissao']; ?></td>
            <td><?php echo $row['cargo']; ?></td>
            <td><?php echo $row['qtde_salarios']; ?></td>
            <td><?php echo $row['salario_bruto']; ?></td>
            <td><?php echo $row['inss']; ?></td>
            <td><?php echo $row['salario_liquido']; ?></td>
            <td><?php echo " <a href='excluir.php?n_registros=".$row["n_registros"]."'>Deletar</a><br>";?></td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>
</body>
</html>