<?php
// logout.php

// 1. Inicia a sessão para que as variáveis possam ser acessadas.
session_start();

// 2. Limpa todas as variáveis de sessão.
$_SESSION = array();

// 3. Destrói a sessão. Isso também apaga o cookie de sessão.
session_destroy();

// 4. Redireciona o usuário para a página inicial.
header("Location: index.php");
exit;
?>