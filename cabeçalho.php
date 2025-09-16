<?php
// header.php

// A chamada a session_start() é essencial para usar as variáveis de sessão.
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

function renderHeader($activePage = '') {
    // Declara as variáveis de URL e texto com base no status da sessão
    if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
        // Lógica para usuário logado
        $perfil_url = "Pagina de perfil.php?id=" . htmlspecialchars($_SESSION['user_id']);
        $perfil_texto = "Meu Perfil";
        $login_url = "logout.php";
        $login_texto = "Sair";
    } else {
        // Lógica para usuário não logado
        $perfil_url = "Pagina de login.php";
        $perfil_texto = "Perfil";
        $login_url = "Pagina de login.php";
        $login_texto = "Login";
    }
?>
    <header class="cabecalho-site">
        <div class="container d-flex justify-content-between align-items-center">
            <div class="logo d-flex align-items-center">
                <img src="92020.png" alt="Logo de Arara" class="logo-imagem">
                <h1>Araras</h1>
            </div>
            <nav class="navegacao-principal d-flex">
                <a href="index.php" class="nav-link<?= $activePage === 'inicio' ? ' active' : '' ?>">Início</a>
                <a href="categorias.php" class="nav-link<?= $activePage === 'categorias' ? ' active' : '' ?>">Categorias</a>
                <a href="Regras.php" class="nav-link<?= $activePage === 'regras' ? ' active' : '' ?>">Regras</a>
                <a href="membros.php" class="nav-link<?= $activePage === 'membros' ? ' active' : '' ?>">Membros</a>
                <a href="eventos.php" class="nav-link<?= $activePage === 'eventos' ? ' active' : '' ?>">Eventos</a>
            </nav>
            <div class="cabecalho-acoes">
                <a href="<?= htmlspecialchars($login_url) ?>" class="botao botao-login me-2"><?= htmlspecialchars($login_texto) ?></a>
                <a href="<?= htmlspecialchars($perfil_url) ?>" class="botao botao-registrar"><?= htmlspecialchars($perfil_texto) ?></a>
            </div>
        </div>
    </header>
<?php
}
?>