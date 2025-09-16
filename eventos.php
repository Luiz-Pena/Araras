<?php
include 'crud.php';
include 'topicosRecentes.php';
session_start();

// Buscar eventos
$eventos = [];
$sql = "SELECT id, nome, descricao, DATE_FORMAT(data_evento, '%d/%m/%Y %H:%i') as data_formatada, local FROM eventos ORDER BY data_evento DESC";
$res = $conn->query($sql);
if ($res && $res->num_rows > 0) {
    while ($row = $res->fetch_assoc()) {
        $eventos[] = $row;
    }
}

// Criar novo evento
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tituloEvento'])) {
    $nome = $conn->real_escape_string($_POST['tituloEvento']);
    $descricao = $conn->real_escape_string($_POST['descricaoEvento']);
    $data_input = $_POST['dataEvento'];
    $local = $conn->real_escape_string($_POST['localEvento']);
    
    // Converte a data do formato "d/m/Y H:i" para um objeto DateTime
    $data_evento_obj = DateTime::createFromFormat('d/m/Y H:i', $data_input);

    // Verifica se a conversão foi bem-sucedida
    if ($data_evento_obj) {
        // Formata a data para o formato aceito pelo MySQL (YYYY-MM-DD HH:MM:SS)
        $data_evento = $data_evento_obj->format('Y-m-d H:i:s');
        
        // Insere no banco de dados
        $sql = "INSERT INTO eventos (nome, descricao, data_evento, local) VALUES ('$nome', '$descricao', '$data_evento', '$local')";
        
        if ($conn->query($sql)) {
            header("Location: eventos.php");
            exit;
        } else {
            echo "Erro na inserção: " . $conn->error;
        }
    } else {
        // A data fornecida não está no formato esperado
        echo "Erro: Formato de data e hora inválido. Use o formato 'dd/mm/aaaa hh:mm'.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eventos - Fórum Araras</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link rel="stylesheet" href="style.css">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous">
    </script>

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
                <h2>Próximos Eventos</h2>
                <button class="botao botao-primario btn" data-bs-toggle="modal" data-bs-target="#modalNovoEvento">
                    Criar Novo Evento
                </button>
            </div>
            <div class="caixa-info">
                <div class="lista-eventos" id="listaEventos">
                    <?php if (count($eventos) > 0): ?>
                        <ul class="list-group">
                        <?php foreach ($eventos as $evento): ?>
                            <li class="list-group-item">
                                <strong><?= htmlspecialchars($evento['nome']) ?></strong>
                                <span><?= htmlspecialchars($evento['data_formatada']) ?></span><br>
                                <span><?= htmlspecialchars($evento['descricao']) ?></span>
                                <?php if (!empty($evento['local'])): ?>
                                    <br><small>Local: <?= htmlspecialchars($evento['local']) ?></small>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p>Nenhum evento cadastrado.</p>
                    <?php endif; ?>
                </div>
            </div>
        </main>
        <aside class="barra-lateral">
            
            <?php
                renderizarCategorias($conn);
            ?>
            
        </aside>
    </div>

    <div class="modal fade" id="modalNovoEvento" tabindex="-1" aria-labelledby="modalNovoEventoLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content" id="formNovoEvento" method="post" action="eventos.php">
            <div class="modal-header">
                <h5 class="modal-title" id="modalNovoEventoLabel">Criar Novo Evento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="tituloEvento" class="form-label">Título do Evento</label>
                    <input type="text" class="form-control" id="tituloEvento" name="tituloEvento" required>
                </div>
                <div class="mb-3">
                    <label for="dataEvento" class="form-label">Data e Hora</label>
                    <input type="text" class="form-control" id="dataEvento" name="dataEvento" placeholder="Ex: 28/09/2024 19:00" required>
                </div>
                <div class="mb-3">
                    <label for="descricaoEvento" class="form-label">Descrição</label>
                    <textarea class="form-control" id="descricaoEvento" name="descricaoEvento" rows="3" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="localEvento" class="form-label">Local do Evento</label>
                    <input type="text" class="form-control" id="localEvento" name="localEvento">
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Publicar Evento</button>
            </div>
        </form>
    </div>
</div>

</body>
</html>

        

    


            