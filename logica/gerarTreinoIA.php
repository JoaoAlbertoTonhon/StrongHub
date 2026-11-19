<?php

session_start();

if (!isset($_SESSION['alunoId'])) {
    header('location:../index.php');
    die();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('location:../paginas/perfil.php');
    die();
}

if (
    !isset($_POST['consentimentoGemini']) ||
    $_POST['consentimentoGemini'] !== '1'
) {
    $_SESSION['erroGemini'] = 'É necessário autorizar o envio dos dados.';
    header('location:../paginas/perfil.php');
    die();
}

require '../includes/funcoesBD.php';

$aluno = buscarAlunoPorId($_SESSION['alunoId']);

if (!$aluno) {
    $_SESSION['erroGemini'] = 'Aluno não encontrado.';
    header('location:../paginas/perfil.php');
    die();
}

$resultadoDias = listarDisponibilidadeAluno($_SESSION['alunoId']);

$dias = [];

while ($dia = mysqli_fetch_array($resultadoDias)) {
    $dias[] = $dia['diaSemana'];
}

$caminhoChave = 'C:/xampp/secrets/wellhub-gemini.php';

if (!is_file($caminhoChave)) {
    $_SESSION['erroGemini'] = 'Arquivo da chave Gemini não encontrado.';
    header('location:../paginas/perfil.php');
    die();
}

$apiKey = require $caminhoChave;

if (!is_string($apiKey) || trim($apiKey) === '') {
    $_SESSION['erroGemini'] = 'A chave da API não foi configurada.';
    header('location:../paginas/perfil.php');
    die();
}

$dadosAluno = [
    'idade' => $aluno['idadeAtual'],
    'altura' => $aluno['altura'],
    'peso' => $aluno['peso'],
    'objetivo' => $aluno['objetivo'],
    'nivel' => $aluno['nivel'],
    'dias_disponiveis' => $dias,
    'condicoes_e_limitacoes' => $aluno['condicoesSaude']
];

$prompt = "Crie uma sugestão de organização de treino para um aluno de academia.\n\nConsidere os seguintes dados:\n\n" . json_encode($dadosAluno, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . "\n\nOrganize a resposta com:\n\n1. Resumo do objetivo do aluno.\n2. Divisão dos treinos durante a semana.\n3. Exercícios sugeridos para cada dia.\n4. Séries e repetições sugeridas, quando apropriado.\n5. Intervalos de descanso.\n6. Cuidados e observações importantes.\n\nRespeite os dias disponíveis do aluno.\n\nNão invente informações que não foram fornecidas.\n\nNão utilize altura e peso para diagnosticar condições de saúde.";

$instrucao = "Você é um assistente informativo de apoio a uma academia.\n\nVocê não é médico, fisioterapeuta nem profissional que avaliou pessoalmente o aluno.\n\nNão faça diagnósticos, não prescreva tratamentos e não prometa que um exercício é seguro.\n\nConsidere cuidadosamente as doenças, lesões e limitações informadas.\n\nQuando houver uma condição que exija avaliação ou liberação profissional, não apresente uma ficha específica como segura. Explique que é necessária uma avaliação de um profissional de saúde e de educação física habilitado.\n\nNão recomende exercícios que possam agravar uma limitação conhecida.\n\nNão invente informações sobre a saúde do aluno.\n\nNão recomende medicamentos, suplementos ou tratamentos.\n\nQuando apresentar sugestões gerais, deixe claro que elas precisam ser avaliadas e aprovadas por um profissional antes de serem utilizadas.";

$dadosRequisicao = [
    'model' => 'gemini-3.1-flash-lite',
    'store' => false,
    'system_instruction' => $instrucao,
    'input' => $prompt,
    'generation_config' => [
        'thinking_level' => 'low'
    ]
];

$opcoes = [
    'http' => [
        'method' => 'POST',
        'header' => "x-goog-api-key: " . trim($apiKey) . "\r\nContent-Type: application/json\r\n",
        'content' => json_encode($dadosRequisicao, JSON_UNESCAPED_UNICODE),
        'timeout' => 90,
        'ignore_errors' => true
    ]
];

$contexto = stream_context_create($opcoes);

$resposta = @file_get_contents(
    'https://generativelanguage.googleapis.com/v1beta/interactions',
    false,
    $contexto
);

$codigoHTTP = 0;

if (isset($http_response_header[0]) && preg_match('/\s(\d{3})\s/', $http_response_header[0], $m)) {
    $codigoHTTP = (int)$m[1];
}

$erroCurl = ($resposta === false) ? 'Falha na conexão' : '';

if (
    $resposta === false ||
    $codigoHTTP < 200 ||
    $codigoHTTP >= 300
) {
    error_log(
        'Erro Gemini HTTP ' . $codigoHTTP . ': ' .
        $erroCurl . ' ' . $resposta
    );
    $_SESSION['erroGemini'] = 'Não foi possível gerar o treino. Confira a conexão, a chave e a cota da API.';
    header('location:../paginas/perfil.php');
    die();
}

$respostaJSON = json_decode($resposta, true);
$sugestao = '';

if (isset($respostaJSON['steps'])) {
    foreach ($respostaJSON['steps'] as $etapa) {
        if (($etapa['type'] ?? '') === 'model_output') {
            foreach ($etapa['content'] ?? [] as $conteudo) {
                if (($conteudo['type'] ?? '') === 'text') {
                    $sugestao .= $conteudo['text'] . "\n";
                }
            }
        }
    }
}

if (trim($sugestao) === '') {
    $_SESSION['erroGemini'] = 'O Gemini não retornou uma sugestão de treino.';
    header('location:../paginas/perfil.php');
    die();
}

$_SESSION['sugestaoTreinoIA'] = trim($sugestao);

header('location:../paginas/perfil.php#sugestaoIA');
die();