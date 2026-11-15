<?php
session_start();
if (!isset($_SESSION['alunoId'])) {
    header('location:../index.php');
    die();
}
require '../includes/funcoesBD.php';
$alunos = listarAlunos();
$titulo = 'Cadastrar treino';
require '../includes/cabecalho.php';
?>
<h2 class="titulo-pagina">Cadastrar treino</h2>
<p class="texto-suave">
    Cadastre um treino e associe ao aluno desejado.
</p>
<?php
if (isset($_GET['sucesso'])) {
    echo '<div class="aviso aviso-sucesso">Treino cadastrado com sucesso!</div>';
}
if (isset($_GET['erro'])) {
    echo '<div class="aviso aviso-erro">Não foi possível cadastrar. Confira os dados e o arquivo PDF.</div>';
}
?>
<form class="formulario"
      method="POST"
      action="../logica/processaCadastroTreino.php"
      enctype="multipart/form-data">
    <div class="campo">
        <label for="alunoId">Aluno</label>
        <select name="alunoId" id="alunoId" required>
            <option value="">Selecione o aluno</option>
            <?php
            while ($aluno = mysqli_fetch_array($alunos)) {
                echo '<option value="' . $aluno['id'] . '">';
                echo htmlspecialchars($aluno['nome']);
                echo '</option>';
            }
            ?>
        </select>
    </div>
    <div class="campo">
        <label for="nome">Nome do treino</label>
        <input type="text"
               name="nome"
               id="nome"
               placeholder="Ex.: Treino A - Peito e Tríceps"
               maxlength="100"
               required>
    </div>
    <div class="campo">
        <label for="descricao">Descrição do treino</label>
        <textarea name="descricao"
                  id="descricao"
                  rows="5"
                  placeholder="Informe os exercícios, séries e repetições."></textarea>
    </div>
    <div class="campo">
        <label for="arquivo">Anexar treino (PDF)</label>
        <input type="file"
               name="arquivo"
               id="arquivo"
               accept=".pdf,application/pdf"
               required>
        <small class="texto-suave">
            Arquivo PDF de até 5 MB.
        </small>
    </div>
    <button class="botao botao-largo" type="submit">
        Cadastrar treino
    </button>
</form>
<?php require '../includes/rodape.php'; ?>