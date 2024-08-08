<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "fatec";
$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}   

$search = isset($_GET['search']) ? $_GET['search'] : '';
$searchQuery = "SELECT * FROM tb_login WHERE nome LIKE ? OR CPF LIKE ?";
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
        <input type="text" name="search" placeholder="Pesquisar por Nome ou CPF" value="<?php echo $search; ?>">
        <input type="submit" value="Pesquisar">
        <a href="listagem.php" class="btn">Folha de Pagamento</a>
        <a href="home_funcionarios.php" class="btn">Cadastrar Folha</a>
        <a href="index.php" class="btn">Sair</a>
    </form>
</div>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Usuário</th>
            <th>Nome</th>
            <th>CPF</th>
            <th>Email</th>
            <th>D/N</th>
            <th>Endereço</th>
            <th>Bairro</th>
            <th>Cidade</th>
            <th>UF</th>
            <th>Apagar</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['usuario']; ?></td>
            <td><?php echo $row['nome']; ?></td>
            <td><?php echo $row['CPF']; ?></td>
            <td><?php echo $row['email']; ?></td>
            <td><?php echo $row['data_nascimento']; ?></td>
            <td><?php echo $row['endereco']; ?></td>
            <td><?php echo $row['bairro']; ?></td>
            <td><?php echo $row['cidade']; ?></td>
            <td><?php echo $row['uf']; ?></td>
            <td><?php echo " <a href='delete_user.php?id=".$row["id"]."'>Deletar</a><br>";?></td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>


<h2>ATIVAR/DESATIVAR USUÁRIOS</h2>
<?php
$sql = "SELECT id, usuario, status_conta FROM tb_login";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table><tr><th>Usuário</th><th>Status</th><th>Ação</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
            <td>{$row['usuario']}</td>
            <td>{$row['status_conta']}</td>
            <td>
                <form action='update_status.php' method='post'>
                    <input type='hidden' name='id' value='{$row['id']}'>
                    <select name='status'>
                        <option value='ativa'" . ($row['status_conta'] == 'ativa' ? ' selected' : '') . ">Ativa</option>
                        <option value='desativada'" . ($row['status_conta'] == 'desativada' ? ' selected' : '') . ">Desativada</option>
                    </select>
                    <input type='submit' value='Atualizar'>
                </form>
            </td>
        </tr>";
    }
    echo "</table>";
} else {
    echo "0 results";
}
$conn->close();
?>

</body>
</html>