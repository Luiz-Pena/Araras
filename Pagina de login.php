<?php
    include 'crud.php';
    session_start();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = $_POST['email'];
        $senha = $_POST['senha'];

        $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($senha, $user['senha'])) {
                $_SESSION['user_id'] = $user['id'];
                header("Location: index.php");
                exit();
            } else {
                echo "<script>alert('Senha incorreta.');</script>";
            }
        } else {
            echo "<script>alert('Usuário não encontrado.');</script>";
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <title> Login </title>
    <link rel="icon" type="image/x-icon" href="https://upload.wikimedia.org/wikipedia/commons/thumb/6/65/Ufu_logo.svg/1200px-Ufu_logo.svg.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .meu-form-item {
            padding-top: 0.5rem;  
            padding-bottom: 0.5rem; 
            width: 75%; 
        }
    </style>            

</head>
<body>
    <div class="container-flex"> 
        <div class="row align-items-center">   
            <div class="col">
                <img src="https://famed.ufu.br/sites/famed.ufu.br/files//imce/45_anos_1.jpeg" class="img-fluid">
            </div>
        
            <form class="col d-flex flex-column justify-content-center align-items-center" method="post" action="">
                <div class="meu-form-item px-1 mb-3"> 
                    <a href="index.php" class="text-decoration-underline" style="font-size: 1.1rem;">Voltar</a> 
                </div>

                <h1 class="mb-4" style="font-size: 2rem;">Login</h1> 

                <div class="meu-form-item"> 
                    <label for="emailUsuario" class="form-label">Email:</label>
                    <input type="text" id="emailUsuario" name="email" class="form-control form-control-lg" required/> 
                </div>

                <div class="meu-form-item"> 
                    <label for="senhaUsuario" class="form-label">Senha:</label>
                    <input type="password" id="senhaUsuario" name="senha" class="form-control form-control-lg" required/> 
                </div>

                <div class="meu-form-item px-1"> 
                    <input class="form-check-input" type="checkbox" id="salvarEmail" style="transform: scale(1.5);"> 
                    <label class="form-check-label ms-3" for="salvarEmail" style="font-size: 1.1rem;">Lembrar email</label> 
                </div>

                <div class="meu-form-item"> 
                    <button type="submit" class="btn btn-primary btn-lg w-100">Login</button> 
                </div>

                <div class="d-flex justify-content-between w-75 pt-2">
                    <a href="Cadastrar.php" class="text-decoration-underline" style="font-size: 1.1rem;">Cadastrar</a> 
                    <a href="#" class="text-decoration-underline" style="font-size: 1.1rem;">Esqueci a Senha</a> 
                </div>
            </form> 
        </div>
    </div>
</body>
</html>