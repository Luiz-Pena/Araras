<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fórum Araras</title>

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
                <a href="index.html" class="nav-link">Início</a>
                <a href="categorias.html" class="nav-link">Categorias</a>
                <a href="regras.html" class="nav-link">Regras</a>
                <a href="membros.html" class="nav-link">Membros</a>
                <a href="eventos.html" class="nav-link active">Eventos</a>
            </nav>
            <div class="cabecalho-acoes">
                <a href="Pagina de login.html" class="botao botao-login me-2">Login</a>
                <a href="Pagina de perfil.html" class="botao botao-registrar">Perfil</a>
            </div>
        </div>
    </header>

    <div class="container conteudo-pagina">
        <main class="conteudo-principal">
            <div class="topicos-cabecalho">
                <h2>Tópicos Recentes</h2>
                <!-- Botão abre modal para criar novo tópico -->
                <button class="botao botao-primario btn" data-bs-toggle="modal" data-bs-target="#modalNovoTopico">Criar
                    Novo Tópico</button>
            </div>

            <div class="lista-topicos" id="listaTopicos">
                <!-- Tópicos serão inseridos dinamicamente aqui -->
            </div>
        </main>

        <aside class="barra-lateral">
            <div class="caixa-info">
                <h4 class="caixa-info-titulo">Categorias</h4>
                <ul class="caixa-info-lista">
                    <li><a href="#">WEB <span class="contador">42</span></a></li>
                    <li><a href="#">ED2<span class="contador">21</span></a></li>
                    <li>
                        <a href="#">POO <span class="contador">35</span></a>
                    </li>
                    <li><a href="#">Tópicos Gerais <span class="contador">18</span></a></li>
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

    <div class="modal fade" id="modalNovoTopico" tabindex="-1" aria-labelledby="modalNovoTopicoLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" id="formNovoTopico">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalNovoTopicoLabel">Criar Novo Tópico</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="tituloTopico" class="form-label">Título</label>
                        <input type="text" class="form-control" id="tituloTopico" required>
                    </div>
                    <div class="mb-3">
                        <label for="categoriaTopico" class="form-label">Categoria</label>
                        <select class="form-select" id="categoriaTopico" required>
                            <option value="WEB">WEB</option>
                            <option value="ED2">ED2</option>
                            <option value="POO">POO</option>
                            <option value="Tópicos Gerais">Tópicos Gerais</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="conteudoTopico" class="form-label">Conteúdo</label>
                        <textarea class="form-control" id="conteudoTopico" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Publicar</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let usuarioAtual = null;
        let usuarios = [
            { nome: "Ricardo", avatar: "ImagemTeste01.jpg", usuario: "ricardo", senha: "123" },
            { nome: "Cleber", avatar: "ImagemTeste02.jpg", usuario: "cleber", senha: "123" }
        ];
        let topicos = [
            {
                titulo: "Dúvidas sobre o trabalho de Web",
                autor: usuarios[0],
                categoria: "WEB",
                respostas: 12,
                ultimaResposta: "Hoje às 14:30"
            },
            {
                titulo: "Busco grupo para o projeto de Poo",
                autor: usuarios[1],
                categoria: "POO",
                respostas: 5,
                ultimaResposta: "Ontem às 21:07"
            }
        ];
        function renderTopicos() {
            const lista = document.getElementById('listaTopicos');
            lista.innerHTML = '';
            topicos.forEach(topico => {
                lista.innerHTML += `
            <div class="cartao-topico">
                <div class="topico-autor-avatar">
                    <img src="${topico.autor.avatar}" alt="Avatar do autor" class="rounded-circle">
                </div>
                <div class="topico-detalhes">
                    <h3 class="topico-titulo"><a href="#">${topico.titulo}</a></h3>
                    <div class="topico-info">
                        Iniciado por <a href="#" class="nome-autor">${topico.autor.nome}</a> em <a href="#" class="topico-categoria">${topico.categoria}</a>
                        <span class="topico-estatisticas">
                            ${topico.respostas} Respostas
                        </span>
                    </div>
                </div>
                <div class="topico-ultima-postagem">
                    <span>Última resposta</span>
                    <span>${topico.ultimaResposta}</span>
                </div>
            </div>
            `;
            });
        }
        renderTopicos();
        document.getElementById('formNovoTopico').addEventListener('submit', function (e) {
            e.preventDefault();
            if (!usuarioAtual) {
                alert('Você precisa estar logado para criar um tópico.');
                return;
            }
            const titulo = document.getElementById('tituloTopico').value;
            const categoria = document.getElementById('categoriaTopico').value;
            const conteudo = document.getElementById('conteudoTopico').value;
            topicos.unshift({
                titulo,
                autor: usuarioAtual,
                categoria,
                respostas: 0,
                ultimaResposta: "Agora"
            });
            renderTopicos();
            document.getElementById('formNovoTopico').reset();
            bootstrap.Modal.getInstance(document.getElementById('modalNovoTopico')).hide();
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous">
    </script>
    
    <script>
         document.addEventListener('DOMContentLoaded', function () {
        const logoDiv = document.querySelector('.logo');
        if (logoDiv) {
            logoDiv.addEventListener('click', function () {
                window.location.href = 'index.html';
            });
        }
    });
    </script>
        
</body>

</html>