<?php
session_start();
if (!isset($_SESSION['alunoId'])) {
    header('location:../index.php');
    die();
}
require '../includes/funcoesBD.php';

if (
    empty($_POST['aula_id']) ||
    empty($_POST['academia_id']) ||
    empty($_POST['data_aula'])
) {
    header('location:../paginas/academias.php');
    die();
}

$alunoId = $_SESSION['alunoId'];
$aulaId = (int)$_POST['aula_id'];
$academiaId = (int)$_POST['academia_id'];

$aula = buscarAula($aulaId);

if (
    !$aula ||
    (int)$aula['academiaId'] != $academiaId ||
    !academiaLiberada($alunoId, $academiaId)
) {
    header('location:../paginas/academias.php');
    die();
}

$dataAula = proximaDataDaAula($aula['diaSemana'], $aula['horario']);

if ($_POST['data_aula'] !== $dataAula) {
    header('location:../paginas/academias.php?erro=data');
    die();
}

if (contarAgendamentos($aulaId, $dataAula) >= $aula['vagas']) {
    header('location:../paginas/academias.php?erro=lotada');
    die();
}

if (alunoJaAgendou($alunoId, $aulaId, $dataAula)) {
    header('location:../paginas/academias.php?erro=ja');
    die();
}

registrarAgendamento($alunoId, $aulaId, $dataAula);
header('location:../paginas/academias.php?sucesso=1');
die();