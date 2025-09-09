<?php
    include 'crud.php';
    session_start();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nome = htmlspecialchars($_POST['nome']);
        $email = htmlspecialchars($_POST['email']);
        $senha = htmlspecialchars($_POST['senha']);
        $senha2 = htmlspecialchars($_POST['senha2']);

        if ($senha !== $senha2) {
            echo "<script>alert('As senhas não coincidem.');</script>";
        } else {
            $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                echo "<script>alert('E-mail já está em uso.');</script>";
            } else {
                $hash = password_hash($senha, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("INSERT INTO usuarios (email, senha) VALUES (?, ?)");
                $stmt->bind_param("ss", $email, $hash);
                if ($stmt->execute()) {
                    $user_id = $stmt->insert_id;
                    $stmt2 = $conn->prepare("INSERT INTO perfis (user_id, nome) VALUES (?, ?)");
                    $stmt2->bind_param("is", $user_id, $nome);
                    $stmt2->execute();

                    $_SESSION['user_id'] = $user_id;
                    header("Location: index.php");
                    exit();
                } else {
                    echo "<script>alert('Erro ao cadastrar.');</script>";
                }
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tela de Cadastro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <style>
        .borda-vermelha { border: 2px solid red !important; }
    </style>
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <form class="card shadow" method="POST">
                    <div class="card-header text-center">
                        <h3>Cadastro</h3>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome</label>
                            <input type="text" class="form-control" id="nome" name="nome" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">E-mail</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="senha" class="form-label">Senha</label>
                            <input type="password" class="form-control" id="senha" name="senha" required>
                        </div>
                        <div class="mb-3">
                            <label for="senha2" class="form-label">Confirmar senha</label>
                            <input type="password" class="form-control" id="senha2" name="senha2" required>
                        </div>
                    </div>
                    <div class="card-footer text-center">
                        <button type="submit" id="botaoCadastro" class="btn btn-primary">Cadastrar</button> 
                        <a href="Pagina de login.php" class="btn btn-secondary">Voltar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>