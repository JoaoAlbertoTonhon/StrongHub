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

$prompt = "Crie uma sugestão de organização de treino para um aluno de academia.

Considere os seguintes dados:

" . json_encode($dadosAluno, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . "

Organize a resposta com:

1. Resumo do objetivo do aluno.
2. Divisão dos treinos durante a semana.
3. Exercícios sugeridos para cada dia.
4. Séries e repetições sugeridas, quando apropriado.
5. Intervalos de descanso.
6. Cuidados e observações importantes.

Respeite os dias disponíveis do aluno.

Não invente informações que não foram fornecidas.

Não utilize altura e peso para diagnosticar condições de saúde.";

$instrucao = "Você é um assistente informativo de apoio a uma academia.

Você não é médico, fisioterapeuta nem profissional que avaliou pessoalmente o aluno.

Não faça diagnósticos, não prescreva tratamentos e não prometa que um exercício é seguro.

Considere cuidadosamente as doenças, lesões e limitações informadas.

Quando houver uma condição que exija avaliação ou liberação profissional, não apresente uma ficha específica como segura. Explique que é necessária uma avaliação de um profissional de saúde e de educação física habilitado.

Não recomende exercícios que possam agravar uma limitação conhecida.

Não invente informações sobre a saúde do aluno.

Não recomende medicamentos, suplementos ou tratamentos.

Quando apresentar sugestões gerais, deixe claro que elas precisam ser avaliadas e aprovadas por um profissional antes de serem utilizadas.";

$dadosRequisicao = [
    'model' => 'gemini-3.8-flash',
    'store' => false,
    'system_instruction' => $instrucao,
    'input' => $prompt,
    'generation_config' => [
        'temperature' => 0.3
    ]
];

if (!function_exists('curl_init')) {
    $_SESSION['erroGemini'] = 'A extensão cURL do PHP não está habilitada.';
    header('location:../paginas/perfil.php');
    die();
}

$ch = curl_init(
    'https://generativelanguage.googleapis.com/v1beta/interactions'
);

curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => [
        'x-goog-api-key: ' . trim($apiKey),
        'Content-Type: application/json'
    ],
    CURLOPT_POSTFIELDS => json_encode(
        $dadosRequisicao,
        JSON_UNESCAPED_UNICODE
    ),
    CURLOPT_CONNECTTIMEOUT => 15,
    CURLOPT_TIMEOUT => 90
]);

$resposta = curl_exec($ch);

$erroCurl = curl_error($ch);

$codigoHTTP = curl_getinfo($ch, CURLINFO_HTTP_CODE);

curl_close($ch);

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

