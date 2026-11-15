<?php
session_start();
if (!isset($_SESSION['alunoId'])) {
    header('location:../index.php');
    die();
}
require '../includes/funcoesBD.php';
$altura = trim($_POST['altura'] ?? '');
$peso = trim($_POST['peso'] ?? '');
if (
    ($altura !== '' && (!is_numeric($altura) || $altura < 0.50 || $altura > 2.50)) ||
    ($peso !== '' && (!is_numeric($peso) || $peso < 1 || $peso > 500))
) {
    header('location:../paginas/perfil.php?erro=1');
    die();
}
$dados = [
    'altura' => $altura,
    'peso' => $peso,
    'condicoesSaude' => $_POST['condicoesSaude'] ?? ''
];
$resultado = atualizarPerfilAluno($_SESSION['alunoId'], $dados);
if (!$resultado) {
    header('location:../paginas/perfil.php?erro=1');
    die();
}
salvarDisponibilidadeAluno(
    $_SESSION['alunoId'],
    $_POST['disponibilidade'] ?? []
);
header('location:../paginas/perfil.php?atualizado=1');
die();