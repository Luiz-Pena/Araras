<?php

function renderEventosCaixa($conn) {
    ?>
    <div class="caixa-info">
        <h4 class="caixa-info-titulo">Eventos da Faculdade</h4>
        <ul class="caixa-info-lista lista-eventos">
            <?php
            $sql = "SELECT nome, data_evento FROM eventos ORDER BY data_evento DESC LIMIT 3";
            $result = $conn->query($sql);
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $nome = htmlspecialchars($row['nome']);
                    $data = date('d \d\e F \d\e Y, H:i', strtotime($row['data_evento']));
                    echo "<li><strong>$nome</strong> <span>$data</span></li>";
                }
            } else {
                echo "<li>Nenhum evento cadastrado.</li>";
            }
            ?>
        </ul>
    </div>
    <?php
}

?>