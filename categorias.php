<?php
include 'crud.php';
include 'ultimosEventos.php';

session_start();

// Processamento do formulário de criação de tópico (substitua a lógica do index.php aqui)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tituloTopico'])) {
    if (!isset($_SESSION['user_id'])) {
        // Redireciona se o usuário não estiver logado

        header("Location: Pagina de login.php");
        exit;
    }
    
    $titulo = $conn->real_escape_string($_POST['tituloTopico']);
    $conteudo = $conn->real_escape_string($_POST['conteudoTopico']);
    $categoria_nome = $conn->real_escape_string($_POST['categoriaTopico']);
    $midia = $_POST['midiaTopico'] ?? null;
    $user_id = $_SESSION['user_id'];
    
    $sql_categoria = "SELECT id FROM categorias WHERE nome = '$categoria_nome'";
    $result_categoria = $conn->query($sql_categoria);
    
    if ($result_categoria && $result_categoria->num_rows > 0) {
        $row = $result_categoria->fetch_assoc();
        $categoria_id = $row['id'];
        
        $sql = "INSERT INTO topicos (titulo, conteudo, user_id, categoria_id, midia) VALUES (?, ?, ?, ?, ?)";
        $stmt_topico = $conn->prepare($sql);
        $stmt_topico->bind_param("ssiis", $titulo, $conteudo, $user_id, $categoria_id, $midia);
        
        if ($stmt_topico->execute()) {
            header("Location: categorias.php?curso=" . urlencode($categoria_nome));
            exit;
        } else {
            echo "Erro ao criar tópico: " . $conn->error;
        }
        $stmt_topico->close();
    } else {
        echo "Erro: Categoria não encontrada.";
    }
}

// Lógica de navegação baseada nos parâmetros da URL
$curso_selecionado = $_GET['curso'] ?? null;
$categoria_selecionada = $_GET['cat'] ?? null;

// Funções de Renderização
function renderizarCursos($conn) {
    $sql = "SELECT id, nome, descricao FROM categorias ORDER BY nome ASC";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<div class="caixa-info mb-3">';
            echo '    <h4 class="caixa-info-titulo"><a href="categorias.php?curso=' . urlencode($row['nome']) . '">' . htmlspecialchars($row['nome']) . '</a></h4>';
            echo '    <p class="text-muted">' . htmlspecialchars($row['descricao'] ?? '') . '</p>';
            echo '</div>';
        }
    } else {
        echo '<div class="p-4 text-center text-muted">Nenhum curso encontrado.</div>';
    }
}

function renderizarOpcoesCategorias($conn, $selecionada = null) {
    $sql = "SELECT id, nome FROM categorias ORDER BY nome ASC";
    $result = $conn->query($sql);
    
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $selected = ($selecionada == $row['nome']) ? 'selected' : '';
            echo '<option value="' . htmlspecialchars($row['nome']) . '" ' . $selected . '>' . htmlspecialchars($row['nome']) . '</option>';
        }
    } else {
        echo '<option value="">Nenhuma categoria encontrada</option>';
    }
}

function renderizarTopicosDaCategoria($conn, $categoria_nome) {
    $stmt_cat = $conn->prepare("SELECT id FROM categorias WHERE nome = ?");
    $stmt_cat->bind_param("s", $categoria_nome);
    $stmt_cat->execute();
    $result_cat = $stmt_cat->get_result();
    $categoria_id = $result_cat->fetch_assoc()['id'] ?? null;
    $stmt_cat->close();

    if (!$categoria_id) {
        echo '<div class="p-4 text-center text-muted">Categoria não encontrada.</div>';
        return;
    }

    $sql_topicos = "SELECT t.id, t.titulo, t.created_at, t.conteudo, t.user_id,
                    p.nome AS autor_nome, p.avatar AS autor_avatar, 
                    (SELECT COUNT(*) FROM respostas WHERE topico_id = t.id) AS contagem_respostas
                    FROM topicos t 
                    LEFT JOIN perfis p ON t.user_id = p.user_id
                    WHERE t.categoria_id = ?
                    ORDER BY t.created_at DESC";
    $stmt = $conn->prepare($sql_topicos);
    $stmt->bind_param("i", $categoria_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<div class="cartao-topico">';
            echo '    <div class="topico-autor-avatar">';
            echo '        <a href="perfil.php?id=' . htmlspecialchars($row['user_id'] ?? '') . '">';
            echo '            <img src="' . htmlspecialchars($row['autor_avatar'] ?? 'avatar_padrao.jpg') . '" alt="Avatar do autor" class="rounded-circle">';
            echo '        </a>';
            echo '    </div>';
            echo '    <div class="topico-detalhes">';
            echo '        <h3 class="topico-titulo"><a href="topico.php?id=' . htmlspecialchars($row['id']) . '">' . htmlspecialchars($row['titulo']) . '</a></h3>';
            echo '        <div class="topico-info">';
            echo '            Iniciado por <a href="perfil.php?id=' . htmlspecialchars($row['user_id'] ?? '') . '" class="nome-autor">' . htmlspecialchars($row['autor_nome'] ?? 'Usuário Removido') . '</a> em <a href="categorias.php?curso=' . urlencode($categoria_nome) . '" class="topico-categoria">' . htmlspecialchars($categoria_nome) . '</a>';
            echo '            <span class="topico-estatisticas">' . htmlspecialchars($row['contagem_respostas']) . ' Respostas</span>';
            echo '        </div>';
            echo '    </div>';
            echo '    <div class="topico-ultima-postagem">';
            echo '        <span>Última resposta</span>';
            echo '        <span>' . date('d/m/Y H:i', strtotime($row['created_at'])) . '</span>';
            echo '    </div>';
            echo '</div>';
        }
    } else {
        echo '<div class="p-4 text-center text-muted">Nenhum tópico encontrado nesta categoria.</div>';
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categorias - Fórum Araras</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
            <div class="topicos-cabecalho">
                <?php if ($curso_selecionado): ?>
                    <h2 id="tituloPrincipal">Tópicos: <?= htmlspecialchars($curso_selecionado) ?></h2>
                    <button class="botao botao-primario btn" data-bs-toggle="modal" data-bs-target="#modalNovoTopico">Criar Novo Tópico</button>
                <?php else: ?>
                    <h2 id="tituloPrincipal">Cursos UFU Monte Carmelo</h2>
                <?php endif; ?>
            </div>
            <div class="lista-principal" id="listaPrincipal">
                <?php
                    if ($curso_selecionado) {
                        renderizarTopicosDaCategoria($conn, $curso_selecionado);
                    } else {
                        renderizarCursos($conn);
                    }
                ?>
            </div>
        </main>
        <aside class="barra-lateral">
            <?php renderEventosCaixa($conn); ?>
        </aside>
    </div>

    <div class="modal fade" id="modalNovoTopico" tabindex="-1" aria-labelledby="modalNovoTopicoLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" id="formNovoTopico" method="post" action="categorias.php">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalNovoTopicoLabel">Criar Novo Tópico</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="tituloTopico" class="form-label">Título</label>
                        <input type="text" class="form-control" id="tituloTopico" name="tituloTopico" required>
                    </div>
                    <div class="mb-3">
                        <label for="categoriaTopico" class="form-label">Categoria</label>
                        <select class="form-select" id="categoriaTopico" name="categoriaTopico" required>
                            <?php renderizarOpcoesCategorias($conn, $curso_selecionado); ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="conteudoTopico" class="form-label">Conteúdo</label>
                        <textarea class="form-control" id="conteudoTopico" name="conteudoTopico" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="midiaTopico" class="form-label">Mídia (URL)</label>
                        <input type="url" class="form-control" id="midiaTopico" name="midiaTopico" placeholder="Opcional: URL de imagem/vídeo">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Publicar</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>