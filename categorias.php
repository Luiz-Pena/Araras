<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categorias - Fórum Araras</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link rel="stylesheet" href="style.css">

    <link rel="icon" href="92020.png" type="image/png">
</head>

<body>
    
    <header class="cabecalho-site">
        <div class="container d-flex justify-content-between align-items-center">
            <div class="logo d-flex align-items-center">
                <img src="92020.png" alt="Logo de Arara" class="logo-imagem">
                <h1>Araras</h1>
            </div>
            <nav class="navegacao-principal d-flex">
                <a href="index.php" class="nav-link">Início</a>
                <a href="categorias.php" class="nav-link active">Categorias</a>
                <a href="regras.html" class="nav-link">Regras</a>
                <a href="membros.php" class="nav-link">Membros</a>
                <a href="eventos.php" class="nav-link">Eventos</a>
            </nav>
            <div class="cabecalho-acoes">
                <a href="Pagina de login.php" class="botao botao-login me-2">Login</a>
                <a href="Pagina de perfil.php" class="botao botao-registrar">Perfil</a>
            </div>
        </div>
    </header>

    <div class="container conteudo-pagina">
        <main class="conteudo-principal">
            <div class="topicos-cabecalho">
                <h2 id="tituloPrincipal">Cursos UFU Monte Carmelo</h2>
            </div>

            <div class="lista-principal" id="listaPrincipal">
            </div>
        </main>

        <aside class="barra-lateral">
            <div class="caixa-info">
                <h4 class="caixa-info-titulo">Categorias</h4>
                <ul class="caixa-info-lista">
                    <li><a href="categorias.php?curso=Agronomia">Agronomia</a></li>
                    <li><a href="categorias.php?curso=Engenharia%20de%20Agrimensura%20e%20Cartogr%C3%A1fica">Engenharia
                            de Agrimensura e Cartográfica</a></li>
                    <li><a href="categorias.php?curso=Engenharia%20Florestal">Engenharia Florestal</a></li>
                    <li><a href="categorias.php?curso=Geologia">Geologia</a></li>
                    <li><a href="categorias.php?curso=Sistemas%20de%20Informa%C3%A7%C3%A3o">Sistemas de Informação</a>
                    </li>
                </ul>
            </div>
            <div class="caixa-info">
                <h4 class="caixa-info-titulo">Eventos da Faculdade</h4>
                <ul class="caixa-info-lista lista-eventos">
                    <li>
                        <strong>Vem pra ufu</strong>
                        <span>12 a 16 de Julho</span>
                    </li>
                    <li>
                        <strong>Palestra: IA no Mercado</strong>
                        <span>28 de Setembro, 19:00</span>
                    </li>
                </ul>
            </div>
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


    <script src="categorias.js" defer></script>
</body>

</html>