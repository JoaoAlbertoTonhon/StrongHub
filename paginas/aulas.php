<?php
session_start();
if (!isset($_SESSION['alunoId'])) {
    header('location:../index.php');
    die();
}
require '../includes/funcoesBD.php';
if (!isset($_GET['academia'])) {
    header('location:academias.php');
    die();
}
$academiaId = (int)$_GET['academia'];
$academia = buscarAcademia($academiaId);
if (!$academia || !academiaLiberada($_SESSION['alunoId'], $academiaId)) {
    header('location:academias.php');
    die();
}
$aulas = listarAulasDaAcademia($academiaId);
$titulo = 'Aulas';
require '../includes/cabecalho.php';
?>
<h2 class="titulo-pagina">
    Aulas - <?php echo htmlspecialchars($academia['nome']); ?>
</h2>
<p class="texto-suave">
    Confira os horários das próximas aulas e agende sua vaga.
</p>
<?php
if (isset($_GET['erro'])) {
    if ($_GET['erro'] == 'lotada') {
        echo '<div class="aviso aviso-erro">Essa aula está lotada.</div>';
    }
    if ($_GET['erro'] == 'ja') {
        echo '<div class="aviso aviso-erro">Você já agendou essa aula.</div>';
    }
    if ($_GET['erro'] == 'data') {
        echo '<div class="aviso aviso-erro">A data da aula mudou. Atualize a página.</div>';
    }
}
if (isset($_GET['sucesso'])) {
    echo '<div class="aviso aviso-sucesso">Aula agendada com sucesso!</div>';
}
?>
<div class="grade">
<?php
if (mysqli_num_rows($aulas) == 0) {
    echo '<div class="cartao">';
    echo '<p class="texto-suave">Não existem aulas cadastradas nesta academia.</p>';
    echo '</div>';
} else {
    while ($aula = mysqli_fetch_array($aulas)) {
        $dataAula = proximaDataDaAula(
            $aula['diaSemana'],
            $aula['horario']
        );
        $ocupadas = contarAgendamentos(
            $aula['id'],
            $dataAula
        );
        $restantes = $aula['vagas'] - $ocupadas;
        $jaAgendou = alunoJaAgendou(
            $_SESSION['alunoId'],
            $aula['id'],
            $dataAula
        );
        echo '<div class="cartao">';
        echo '<h3>' . htmlspecialchars($aula['modalidade']) . '</h3>';
        echo '<p class="texto-suave">';
        echo ucfirst(htmlspecialchars($aula['diaSemana']));
        echo ' às ' . substr($aula['horario'], 0, 5);
        echo '</p>';
        echo '<p>Próxima aula: ';
        echo date('d/m/Y', strtotime($dataAula));
        echo '</p>';
        if ($jaAgendou) {
            echo '<span class="selo">Você já está inscrito</span>';
        } elseif ($restantes > 0) {
            echo '<span class="selo">';
            echo $restantes . ' vaga(s) restante(s)';
            echo '</span>';
            echo '<form method="POST" action="../logica/processaAgendamento.php">';
            echo '<input type="hidden" name="aula_id" value="' . $aula['id'] . '">';
            echo '<input type="hidden" name="academia_id" value="' . $academiaId . '">';
            echo '<input type="hidden" name="data_aula" value="' . $dataAula . '">';
            echo '<button class="botao" type="submit">Agendar</button>';
            echo '</form>';
        } else {
            echo '<span class="selo selo-cinza">Lotada</span>';
        }
        echo '</div>';
    }
}
?>
</div>
<?php require '../includes/rodape.php'; ?>