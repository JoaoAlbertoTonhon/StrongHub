<?php
session_start();
if (!isset($_SESSION['alunoId'])) {
    header('location:../index.php');
    die();
}
require '../includes/funcoesBD.php';
$academias = listarAcademiasDoAluno($_SESSION['alunoId']);
$titulo = 'Treinar';
require '../includes/cabecalho.php';
?>

<h2 class="titulo-pagina">Treinar</h2>
<p class="texto-suave">Escolha a academia e agende sua aula com um clique.</p>

<?php
if (isset($_GET['erro'])) {
    if ($_GET['erro'] == 'lotada') {
        echo '<div class="aviso aviso-erro alert alert-danger">Essa aula está lotada.</div>';
    }
    if ($_GET['erro'] == 'ja') {
        echo '<div class="aviso aviso-erro alert alert-danger">Você já está inscrito nessa aula.</div>';
    }
    if ($_GET['erro'] == 'data') {
        echo '<div class="aviso aviso-erro alert alert-danger">A data da aula mudou. Atualize a página.</div>';
    }
}
if (isset($_GET['sucesso'])) {
    echo '<div class="aviso aviso-sucesso alert alert-success">Aula agendada com sucesso!</div>';
}
?>

<div class="lista-academias">
<?php while ($academia = mysqli_fetch_array($academias)) {
    $arquivoImagem = __DIR__ . '/../img/academias/' . $academia['imagem'];
    $temImagem = !empty($academia['imagem']) && is_file($arquivoImagem);
?>
    <div class="cartao-academia card p-3">
        <?php if ($temImagem) { ?>
            <img class="imagem-academia" src="../img/academias/<?php echo htmlspecialchars($academia['imagem']); ?>" alt="<?php echo htmlspecialchars($academia['nome']); ?>">
        <?php } else { ?>
            <div class="imagem-academia sem-imagem">Sem foto</div>
        <?php } ?>

        <div class="cabecalho-academia">
            <div>
                <h3><?php echo htmlspecialchars($academia['nome']); ?></h3>
                <p class="texto-suave">
                    <?php echo htmlspecialchars($academia['endereco']); ?> · <?php echo htmlspecialchars($academia['cidade']); ?>
                </p>
            </div>
            <div class="acoes-academia">
                <span class="selo selo-cinza badge bg-light text-dark border">
                    <?php echo substr($academia['horarioAbertura'], 0, 5); ?> às <?php echo substr($academia['horarioFechamento'], 0, 5); ?>
                </span>
                <a class="botao-secundario btn btn-outline-danger" href="checkin.php?academia=<?php echo $academia['id']; ?>">Fazer check-in</a>
            </div>
        </div>

        <?php
        $aulas = listarAulasDaAcademia($academia['id']);
        if (mysqli_num_rows($aulas) == 0) {
            echo '<p class="lista-vazia">Nenhuma aula cadastrada nesta academia.</p>';
        } else {
            echo '<div class="row row-cols-1 row-cols-md-3 g-3 grade-aulas">';
            while ($aula = mysqli_fetch_array($aulas)) {
                $dataAula = proximaDataDaAula($aula['diaSemana'], $aula['horario']);
                $restantes = $aula['vagas'] - contarAgendamentos($aula['id'], $dataAula);
                $jaAgendou = alunoJaAgendou($_SESSION['alunoId'], $aula['id'], $dataAula);
        ?>
                <div class="col">
                    <div class="cartao-aula h-100">
                        <h4><?php echo htmlspecialchars($aula['modalidade']); ?></h4>
                        <p class="texto-suave">
                            <?php echo ucfirst($aula['diaSemana']); ?> às <?php echo substr($aula['horario'], 0, 5); ?>
                            · <?php echo date('d/m', strtotime($dataAula)); ?>
                        </p>

                        <?php if ($jaAgendou) { ?>
                            <span class="selo badge bg-success">Você já está inscrito</span>
                        <?php } elseif ($restantes > 0) { ?>
                            <span class="selo badge bg-light text-dark border"><?php echo $restantes; ?> vaga(s)</span>
                            <form method="POST" action="../logica/processaAgendamento.php">
                                <input type="hidden" name="aula_id" value="<?php echo $aula['id']; ?>">
                                <input type="hidden" name="academia_id" value="<?php echo $academia['id']; ?>">
                                <input type="hidden" name="data_aula" value="<?php echo $dataAula; ?>">
                                <button class="botao btn btn-danger" type="submit">Agendar</button>
                            </form>
                        <?php } else { ?>
                            <span class="selo selo-cinza badge bg-secondary">Lotada</span>
                        <?php } ?>
                    </div>
                </div>
        <?php
            }
            echo '</div>';
        }
        ?>
    </div>
<?php } ?>
</div>

<?php require '../includes/rodape.php'; ?>