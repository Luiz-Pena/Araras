<?php
    include 'crud.php';
    include 'ultimosEventos.php';
    include 'topicosRecentes.php';
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Regras - Fórum Araras</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link rel="stylesheet" href="style.css">

    <link rel="icon" href="92020.png" type="image/png">
</head>

<body>
    <?php
        include 'cabeçalho.php';
        renderHeader('eventos');
    ?>

    <div class="container conteudo-pagina">
        <main class="conteudo-principal">
            <div class="caixa-info">
                <h2 class="caixa-info-titulo">Regras do Fórum</h2>
                <ul class="caixa-info-lista">
                    <li>
                        <strong>1. Respeite os outros membros.</strong>
                        <span>Comentários ofensivos, discriminatórios ou de cunho sexual não serão tolerados.</span>
                    </li>
                    <li>
                        <strong>2. Mantenha a organização.</strong>
                        <span>Publique tópicos na categoria correta e evite spam.</span>
                    </li>
                    <li>
                        <strong>3. Evite postagens duplicadas.</strong>
                        <span>Use a ferramenta de busca para verificar se sua pergunta já foi respondida.</span>
                    </li>
                    <li>
                        <strong>4. Não compartilhe informações pessoais.</strong>
                        <span>Mantenha sua privacidade e a dos outros membros.</span>
                    </li>
                    <li>
                        <strong>5. Proibido plágio.</strong>
                        <span>Dê os devidos créditos a fontes externas quando necessário.</span>
                    </li>
                </ul>
            </div>
        </main>

        <aside class="barra-lateral">
            
            <?php
                renderEventosCaixa($conn);

                renderizarCategorias($conn);
            ?>
            
        </aside>
    </div>

    <div class="modal fade" id="modalLogin" tabindex="-1" aria-labelledby="modalLoginLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" id="formLogin">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLoginLabel">Login</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="loginUsuario" class="form-label">Usuário</label>
                        <input type="text" class="form-control" id="loginUsuario" required>
                    </div>
                    <div class="mb-3">
                        <label for="loginSenha" class="form-label">Senha</label>
                        <input type="password" class="form-control" id="loginSenha" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Entrar</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const logoDiv = document.querySelector('.logo');
        if (logoDiv) {
            logoDiv.addEventListener('click', function () {
                window.location.href = 'index.html';
            });
        }
    });</script>
</body>

</html>