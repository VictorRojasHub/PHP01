<?php
session_start();
header("Location: home_funcionarios.php");
if (isset($_GET['n_registros'])) {
    $userid = $_GET['n_registros'];

    $host = "localhost";
    $user = "root";
    $password = "";
    $dbname = "folha_pagto";
    $conn = new mysqli($host, $user, $password, $dbname);

    if ($conn->connect_error) {
        die("Falha na conexão: " . $conn->connect_error);
    }

    $sql = "DELETE FROM tb_funcionarios WHERE n_registros = ?";
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

header("Location: listagem.php");
exit;
?>