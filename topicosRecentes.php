<?php

/**
 * Renderiza uma caixa de informações com a lista de categorias e a contagem de tópicos.
 *
 * @param mysqli $conn O objeto de conexão com o banco de dados.
 */
function renderizarCategorias($conn) {
    // Consulta SQL para obter o nome de cada categoria e a contagem de tópicos relacionados.
    // Usamos LEFT JOIN para incluir categorias sem tópicos.
    $sql = "SELECT c.nome, COUNT(t.id) as contagem
            FROM categorias c
            LEFT JOIN topicos t ON c.id = t.categoria_id
            GROUP BY c.nome
            ORDER BY contagem DESC
            LIMIT 5";

    $result = $conn->query($sql);

    // Inicia a renderização do HTML principal
    echo '<div class="caixa-info">';
    echo '    <h4 class="caixa-info-titulo">Categorias</h4>';
    echo '    <ul class="caixa-info-lista">';

    // Verifica se a consulta retornou resultados
    if ($result->num_rows > 0) {
        // Itera sobre cada linha de resultado para gerar os itens da lista
        while ($row = $result->fetch_assoc()) {
            // Usamos htmlspecialchars() para evitar ataques XSS
            $nomeCategoria = htmlspecialchars($row['nome']);
            $contagemTopicos = htmlspecialchars($row['contagem']);
            
            // Renderiza o item da lista com o nome da categoria e a contagem
            echo "<li><a href='categorias.php?curso=" . urlencode($nomeCategoria) . "'>$nomeCategoria <span class='contador'>$contagemTopicos</span></a></li>";
        }
    } else {
        // Se não houver categorias no banco de dados, exibe uma mensagem
        echo '<li>Nenhuma categoria encontrada.</li>';
    }

    // Finaliza a renderização do HTML
    echo '    </ul>';
    echo '</div>';
}

?>