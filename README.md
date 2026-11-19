StrongHub

## Funcionalidades

- Login do aluno com validação no banco de dados
- Recuperação de senha (confirmação de e-mail e CPF, com troca de senha)
- Criação de conta com plano escolhido
- Cadastros de planos, academias, aulas e alunos
- Listagens de alunos, planos, academias e aulas
- Academias liberadas conforme o plano do aluno
- Check-in do aluno em academia (uma vez por dia)
- Agendamento de aulas com controle de vagas e cancelamento
- Perfil do aluno com disponibilidade de dias
- Sugestão de treino gerada pela IA do Google Gemini

## Como executar

1. Instale o XAMPP e inicie o Apache e o MySQL.
2. Copie a pasta do projeto para `C:\xampp\htdocs\StrongHub`.
3. No phpMyAdmin, importe o arquivo `database/wellhub.sql`.
4. Acesse `http://localhost/StrongHub/`.

## Configuração da IA (Gemini)

A chave da API não está no projeto. Crie a pasta `C:\xampp\secrets` e, dentro dela, o arquivo `wellhub-gemini.php` com este conteúdo, trocando pela sua chave:

```php
<?php
return 'SUA_CHAVE_DO_GEMINI';
```

A chave pode ser gerada gratuitamente no Google AI Studio (aistudio.google.com).
