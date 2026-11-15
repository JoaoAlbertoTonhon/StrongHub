<?php
session_start();
if (!isset($_SESSION['alunoId'])) {
    header('location:../index.php');
    die();
}
require '../includes/funcoesBD.php';
$treinoId = (int)($_GET['id'] ?? 0);
$treino = buscarTreinoDoAluno(
    $treinoId,
    $_SESSION['alunoId']
);
if (!$treino) {
    http_response_code(404);
    die('Treino não encontrado.');
}
$diretorio = dirname($_SERVER['DOCUMENT_ROOT'])
    . DIRECTORY_SEPARATOR . 'wellhub_uploads'
    . DIRECTORY_SEPARATOR . 'treinos';
$arquivo = $diretorio . DIRECTORY_SEPARATOR
    . basename($treino['arquivo']);
if (!is_file($arquivo)) {
    http_response_code(404);
    die('Arquivo não encontrado.');
}
header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="treino-' . $treinoId . '.pdf"');
header('Content-Length: ' . filesize($arquivo));
header('X-Content-Type-Options: nosniff');
readfile($arquivo);
exit;