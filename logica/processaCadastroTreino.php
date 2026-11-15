<?php
session_start();
if (!isset($_SESSION['alunoId'])) {
    header('location:../index.php');
    die();
}
require '../includes/funcoesBD.php';
$alunoId = (int)($_POST['alunoId'] ?? 0);
$nome = trim($_POST['nome'] ?? '');
$descricao = trim($_POST['descricao'] ?? '');
if (
    !$alunoId ||
    $nome === '' ||
    strlen($nome) > 100 ||
    !buscarAlunoPorId($alunoId) ||
    !isset($_FILES['arquivo']) ||
    $_FILES['arquivo']['error'] !== UPLOAD_ERR_OK
) {
    header('location:../paginas/cadastroTreino.php?erro=1');
    die();
}
$arquivo = $_FILES['arquivo'];
if ($arquivo['size'] > 5 * 1024 * 1024) {
    header('location:../paginas/cadastroTreino.php?erro=1');
    die();
}
$extensao = strtolower(pathinfo($arquivo['name'], PATHINFO_EXTENSION));
if ($extensao !== 'pdf' || !function_exists('finfo_open')) {
    header('location:../paginas/cadastroTreino.php?erro=1');
    die();
}
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$tipo = finfo_file($finfo, $arquivo['tmp_name']);
finfo_close($finfo);
if ($tipo !== 'application/pdf') {
    header('location:../paginas/cadastroTreino.php?erro=1');
    die();
}
$diretorio = dirname($_SERVER['DOCUMENT_ROOT'])
    . DIRECTORY_SEPARATOR . 'wellhub_uploads'
    . DIRECTORY_SEPARATOR . 'treinos';
if (!is_dir($diretorio)) {
    mkdir($diretorio, 0775, true);
}
$nomeArquivo = bin2hex(random_bytes(16)) . '.pdf';
$caminho = $diretorio . DIRECTORY_SEPARATOR . $nomeArquivo;
if (!move_uploaded_file($arquivo['tmp_name'], $caminho)) {
    header('location:../paginas/cadastroTreino.php?erro=1');
    die();
}
$resultado = cadastrarTreino(
    $alunoId,
    $nome,
    $descricao,
    $nomeArquivo
);
if (!$resultado) {
    unlink($caminho);
    header('location:../paginas/cadastroTreino.php?erro=1');
    die();
}
header('location:../paginas/cadastroTreino.php?sucesso=1');
die();