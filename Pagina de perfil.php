<?php
    session_start();
    require_once 'crud.php'; // Certifique-se de que este arquivo conecta ao banco e define $conn

    // Supondo que o usuário está logado e o id está na sessão
    $user_id = $_SESSION['user_id'] ?? 1; // Troque para o método correto de autenticação

    // Buscar dados do perfil
    $stmt = $conn->prepare("SELECT p.nome, p.bio, p.avatar, p.created_at, p.user_id FROM perfis p WHERE p.user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($nome, $bio, $avatar, $created_at, $perfil_user_id);
    $stmt->fetch();
    $stmt->close();

    // Buscar seguidores
    $stmt = $conn->prepare("SELECT COUNT(*) FROM seguir WHERE seguindo_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($seguidores);
    $stmt->fetch();
    $stmt->close();

    // Buscar seguindo
    $stmt = $conn->prepare("SELECT COUNT(*) FROM seguir WHERE seguidor_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($seguindo);
    $stmt->fetch();
    $stmt->close();

    // Avatar padrão se não houver
    if (empty($avatar)) {
        $avatar = "https://cdn-icons-png.flaticon.com/512/3736/3736502.png";
    }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <title> Perfil </title>
    <link rel="icon" type="image/x-icon" href="https://upload.wikimedia.org/wikipedia/commons/thumb/6/65/Ufu_logo.svg/1200px-Ufu_logo.svg.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style> 
        body {
            padding: 50px;
        }

        .div-example {
            border: 2px solid #dee2e6;
            background-color: #f8f9fa;
            color: #212529;
            margin-bottom: 30px;
        }

        .profile-header {
            display: flex;
            align-items: center; 
            gap: 10px;           
        }

        .sidebar {
            position:fixed;
            z-index: 1;
        }

        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            height: 100vh;
            width: 350px;
            background-color: #f8f9fa;
            border-right: 1px solid #dee2e6;
            overflow-y: auto;
            z-index: 1000;
        }
        
        .main-content {
            margin-left: 350px;
            padding: 20px;
            width: calc(100% - 350px);
        }
        
        .profile-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .imagem-flutuante {
            width: 220px;
            height: 220px;
            border-radius: 50%;
            object-fit: cover;
        }
        
        .div-example {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        } 
    </style>

</head>
<body>
    <div class="sidebar p-4"> 
        <div> 
            <a href="index.php"> Voltar </a>
        </div>
        <img class="imagem-flutuante" src="<?php echo htmlspecialchars($avatar); ?>">
        <h1> <?php echo htmlspecialchars($nome ?? 'Meu Perfil'); ?> </h1>
        <h5 class="p-1"> <?php echo $seguidores; ?> Seguidores </h5>
        <h5 class="p-1"> <?php echo $seguindo; ?> Seguindo </h5>
        <h5 class="p-1"> Membro desde <?php echo date('d-m-Y', strtotime($created_at ?? 'now')); ?> </h5>
        <div class="p-1">
            <h5> 
            <?php echo nl2br(htmlspecialchars($bio ?? '')); ?>
            </h5>
        </div>

        <?php if (isset($_SESSION['user_id']) && $perfil_user_id == $_SESSION['user_id']): ?>
            <button class="btn btn-primary btn-lg w-100 mb-3" onclick="document.getElementById('editPerfilForm').style.display='block'">Editar Perfil</button>
                <form id="editPerfilForm" action="editPerfil.php" method="POST" enctype="multipart/form-data" style="display:none;">
                    <div class="mb-2">
                        <label for="nome" class="form-label">Nome</label>
                        <input type="text" class="form-control" name="nome" id="nome" value="<?php echo htmlspecialchars($nome); ?>" required>
                    </div>

                    <div class="mb-2">
                        <label for="avatar" class="form-label">Avatar (URL)</label>
                        <input type="text" class="form-control" name="avatar" id="avatar" value="<?php echo htmlspecialchars($avatar); ?>">
                    </div>

                    <div class="mb-2">
                        <label for="bio" class="form-label">Bio</label>
                        <textarea class="form-control" name="bio" id="bio" rows="3"><?php echo htmlspecialchars($bio); ?></textarea>
                    </div>

                    <button type="submit" class="btn btn-success w-100">Salvar</button>
                    <button type="button" class="btn btn-secondary w-100 mt-2" onclick="document.getElementById('editPerfilForm').style.display='none'">Cancelar</button>
                    <button type="submit" class="btn btn-danger w-100 mt-2" formaction="deletePerfil.php" onclick="return confirm('Tem certeza que deseja excluir seu perfil? Esta ação não pode ser desfeita.');">Excluir Perfil</button>
                </form>

        <?php elseif (isset($_SESSION['user_id']) && $perfil_user_id != $_SESSION['user_id']): ?>
            <?php
                // Verifica se já está seguindo
                $ja_segue = false;
                $check_stmt = $conn->prepare("SELECT 1 FROM seguir WHERE seguidor_id = ? AND seguindo_id = ?");
                $check_stmt->bind_param("ii", $_SESSION['user_id'], $perfil_user_id);
                $check_stmt->execute();
                $check_stmt->store_result();
        
                if ($check_stmt->num_rows > 0) {
                    $ja_segue = true;
                }
        
                $check_stmt->close();
            ?>
    
            <form action="seguir.php" method="POST">
                <input type="hidden" name="seguindo_id" value="<?php echo $perfil_user_id; ?>">
                <?php if ($ja_segue): ?>
                    <button type="submit" class="btn btn-secondary btn-lg w-100 mb-3" name="acao" value="deixar_de_seguir">Deixar de Seguir</button>
                <?php else: ?>
                    <button type="submit" class="btn btn-success btn-lg w-100 mb-3" name="acao" value="seguir">Seguir</button>
                <?php endif; ?>
            </form>
            
        <?php endif; ?>
    </div>
    
    <div class="main-content">
        <h2 class="bg-primary text-white" style="border-radius: 5px; text-align: center;"> Publicações </h2>

        <?php
        // Buscar tópicos do perfil visitado
        $stmt = $conn->prepare("SELECT t.id, t.titulo, t.conteudo, t.created_at, t.midia FROM topicos t WHERE t.user_id = ? ORDER BY t.created_at DESC");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            echo '<div class="div-example p-5 mb-4 text-center"><h4>Este perfil ainda não publicou tópicos.</h4></div>';
        } else {
            while ($row = $result->fetch_assoc()) {
                // Buscar número de curtidas (exemplo: tabela curtidas_topicos)
                $curtidas = 0;
                if ($curtidas_stmt = $conn->prepare("SELECT COUNT(*) FROM curtidas_topicos WHERE topico_id = ?")) {
                    $curtidas_stmt->bind_param("i", $row['id']);
                    $curtidas_stmt->execute();
                    $curtidas_stmt->bind_result($curtidas);
                    $curtidas_stmt->fetch();
                    $curtidas_stmt->close();
                }

                // Buscar número de comentários (respostas)
                $comentarios = 0;
                if ($comentarios_stmt = $conn->prepare("SELECT COUNT(*) FROM respostas WHERE topico_id = ?")) {
                    $comentarios_stmt->bind_param("i", $row['id']);
                    $comentarios_stmt->execute();
                    $comentarios_stmt->bind_result($comentarios);
                    $comentarios_stmt->fetch();
                    $comentarios_stmt->close();
                }

                ?>
                <div class="div-example p-5 mb-4">
                    <div class="profile-header">
                        <img class="imagem-flutuante" src="<?php echo htmlspecialchars($avatar); ?>">
                        <h2><?php echo htmlspecialchars($row['titulo']); ?></h2>
                    </div>
                
                    <div>
                        <p class="p-2">
                            <?php echo nl2br(htmlspecialchars($row['conteudo'])); ?>
                        </p>

                        <?php if (!empty($row['midia'])): ?>
                            <img src="<?php echo htmlspecialchars($row['midia']); ?>" class="img-fluid">
                        <?php endif; ?>
                    </div>

                    <div class="d-flex justify-content-between w-75 p-2">
                        <a><?php echo $curtidas; ?> Curtidas</a>
                        <a><?php echo $comentarios; ?> Comentários</a>
                        <span class="text-muted"><?php echo date('d/m/Y H:i', strtotime($row['created_at'])); ?></span>
                    </div>
    
                    <?php if (isset($_SESSION['user_id']) && $perfil_user_id == $_SESSION['user_id']): ?>
                        <form action="deleteTopico.php" method="POST" onsubmit="return confirm('Tem certeza que deseja apagar este tópico?');">
                            <input type="hidden" name="topico_id" value="<?php echo $row['id']; ?>">
                            <button type="submit" class="btn btn-danger mt-2">Apagar Tópico</button>
                        </form>
                    <?php endif; ?>
                    </div>
                <?php
            }
        }
        $stmt->close();
        ?>

        
    </div>
</body>
</html>