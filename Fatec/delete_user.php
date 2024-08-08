<?php
session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login_admin.php");
    exit;
}

if (isset($_GET['id'])) {
    $userid = $_GET['id'];

    $host = "localhost";
    $user = "root";
    $password = "";
    $dbname = "fatec";
    $conn = new mysqli($host, $user, $password, $dbname);

    if ($conn->connect_error) {
        die("Falha na conexão: " . $conn->connect_error);
    }

    $sql = "DELETE FROM tb_login WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userid);
    if($stmt->execute()) {
        echo "Usuário deletado com sucesso.";
    } else {
        echo "Erro ao deletar usuário: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}

header("Location: admin_page.php");
exit;
?>