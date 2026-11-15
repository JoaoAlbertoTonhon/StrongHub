
<?php

require '../includes/funcoesBD.php';

if (
    empty($_POST['nome']) ||
    empty($_POST['cpf']) ||
    empty($_POST['dataNascimento']) ||
    empty($_POST['endereco']) ||
    empty($_POST['email']) ||
    empty($_POST['senha']) ||
    empty($_POST['planoId'])
) {
    header('location:../cadastroAluno.php?erro=1');
    die();
}

$dataNascimento = DateTime::createFromFormat(
    '!Y-m-d',
    $_POST['dataNascimento']
);

if (
    !$dataNascimento ||
    $dataNascimento->format('Y-m-d') !== $_POST['dataNascimento'] ||
    $dataNascimento > new DateTime('today')
) {
    header('location:../cadastroAluno.php?erro=data');
    die();
}

$hoje = new DateTime('today');

$_POST['idade'] = $dataNascimento->diff($hoje)->y;

$alunoId = cadastrarAluno($_POST);

if (!$alunoId) {
    header('location:../cadastroAluno.php?erro=1');
    die();
}

salvarDisponibilidadeAluno(
    $alunoId,
    $_POST['disponibilidade'] ?? []
);

cadastrarAssinatura($alunoId, $_POST['planoId']);

header('location:../index.php?cadastrado=1');
die();

