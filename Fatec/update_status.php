<?php
session_start();

$host = "localhost";
$user = "root";
$password = "";
$dbname = "fatec";
$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE tb_login SET status_conta = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $id);
    if ($stmt->execute()) {
        $_SESSION['mensagem'] = "Status atualizado com sucesso!";
    } else {
        $_SESSION['mensagem'] = "Erro ao atualizar status: " . $conn->error;
    }
    $stmt->close();
    $conn->close();
    header('Location: admin_page.php'); // Redirecionar de volta para a página de administração
    exit();
}
?>
