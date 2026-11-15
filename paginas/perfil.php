<?php
session_start();
if (!isset($_SESSION['alunoId'])) {
    header('location:../index.php');
    die();
}

require '../includes/funcoesBD.php';
$alunoId = $_SESSION['alunoId'];
$aluno = buscarAlunoPorId($alunoId);
$disponibilidade = listarDisponibilidadeAluno($alunoId);
$treinos = listarTreinosDoAluno($alunoId);
$diasSelecionados = [];
while ($dia = mysqli_fetch_array($disponibilidade)) {
    $diasSelecionados[] = $dia['diaSemana'];
}
$titulo = 'Meu perfil';
require '../includes/cabecalho.php';
?>

<h2 class="titulo-pagina">Meu perfil</h2>

<?php
if (isset($_GET['atualizado'])) {
    echo '<div class="aviso aviso-sucesso">Perfil atualizado com sucesso!</div>';
}
if (isset($_GET['erro'])) {
    echo '<div class="aviso aviso-erro">Não foi possível atualizar o perfil. Confira os dados.</div>';
}
?>
<div class="cartao">
    <h3>Dados pessoais</h3>
    <p><strong>Nome:</strong>
        <?php echo htmlspecialchars($aluno['nome']); ?>
    </p>
    <p><strong>E-mail:</strong>
        <?php echo htmlspecialchars($aluno['email']); ?>
    </p>
    <p><strong>CPF:</strong>
        <?php echo htmlspecialchars($aluno['cpf']); ?>
    </p>
    <p><strong>Data de nascimento:</strong>
        <?php echo date('d/m/Y', strtotime($aluno['dataNascimento'])); ?>
    </p>
    <p><strong>Idade:</strong>
        <?php echo $aluno['idadeAtual']; ?> anos
    </p>
</div>
<form class="formulario" method="POST"
      action="../logica/processaPerfil.php">
    <h3>Informações físicas e disponibilidade</h3>
    <div class="linha">
        <div class="coluna campo">
            <label for="altura">Altura (metros)</label>
            <input type="number" name="altura" id="altura"
                   step="0.01" min="0.50" max="2.50"
                   value="<?php echo htmlspecialchars($aluno['altura'] ?? ''); ?>">
        </div>
        <div class="coluna campo">
            <label for="peso">Peso (kg)</label>
            <input type="number" name="peso" id="peso"
                   step="0.1" min="1" max="500"
                   value="<?php echo htmlspecialchars($aluno['peso'] ?? ''); ?>">
        </div>
    </div>
    <div class="campo">
        <label for="condicoesSaude">
            Doenças, lesões ou limitações
        </label>
        <textarea name="condicoesSaude"
                  id="condicoesSaude"
                  rows="4"><?php echo htmlspecialchars($aluno['condicoesSaude'] ?? ''); ?></textarea>
    </div>
    <div class="campo">
        <label>Dias disponíveis para treinar</label>
        <?php
        $dias = [
            'segunda' => 'Segunda-feira',
            'terca' => 'Terça-feira',
            'quarta' => 'Quarta-feira',
            'quinta' => 'Quinta-feira',
            'sexta' => 'Sexta-feira',
            'sabado' => 'Sábado',
            'domingo' => 'Domingo'
        ];
        foreach ($dias as $valor => $nome) {
            $marcado = in_array($valor, $diasSelecionados)
                ? 'checked' : '';
            echo '<label class="marcacao">';
            echo '<input type="checkbox" name="disponibilidade[]" value="' . $valor . '" ' . $marcado . '>';
            echo $nome;
            echo '</label>';
        }
        ?>
    </div>
    <button class="botao botao-largo" type="submit">
        Salvar alterações
    </button>
</form>
<h3>Meus treinos</h3>
<?php
if (mysqli_num_rows($treinos) == 0) {
    echo '<div class="cartao">';
    echo '<p class="texto-suave">Você ainda não possui treinos cadastrados.</p>';
    echo '</div>';
} else {
    while ($treino = mysqli_fetch_array($treinos)) {
        echo '<div class="cartao">';
        echo '<h3>' . htmlspecialchars($treino['nome']) . '</h3>';
        echo '<p>' . nl2br(htmlspecialchars($treino['descricao'])) . '</p>';
        echo '<p class="texto-suave">';
        echo 'Cadastrado em: ';
        echo date('d/m/Y', strtotime($treino['dataCadastro']));
        echo '</p>';
        echo '<a class="botao" href="../logica/baixarTreino.php?id=' . $treino['id'] . '">Visualizar treino PDF</a>';
        echo '</div>';
    }
}
?>
<?php require '../includes/rodape.php'; ?>