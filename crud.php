<?php
    $host = "localhost";
    $user = "root";
    $pass = "mysql";
    $db = "siteAraras";

    // Conexão
    $conn = new mysqli($host, $user, $pass);
    if ($conn->connect_error) die("Falha na conexão: " . $conn->connect_error);

    // Criação do banco e tabela
    $conn->query("CREATE DATABASE IF NOT EXISTS $db");
    $conn->select_db($db);
    
    $conn->query("CREATE TABLE IF NOT EXISTS usuarios (
        id INT AUTO_INCREMENT PRIMARY KEY,
        email VARCHAR(100) NOT NULL UNIQUE,
        senha VARCHAR(255) NOT NULL
    )");

    $conn->query("CREATE TABLE IF NOT EXISTS categorias (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nome VARCHAR(100) NOT NULL UNIQUE,
        descricao TEXT
    )");

    $conn->query("CREATE TABLE IF NOT EXISTS eventos (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nome VARCHAR(100) NOT NULL UNIQUE,
        descricao TEXT,
        data_evento DATETIME NOT NULL,
        local VARCHAR(255),
        criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    $conn->query("CREATE TABLE IF NOT EXISTS topicos (
        id INT AUTO_INCREMENT PRIMARY KEY,
        titulo VARCHAR(255) NOT NULL,
        conteudo TEXT NOT NULL,
        user_id INT,
        categoria_id INT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        midia VARCHAR(255),
        FOREIGN KEY (user_id) REFERENCES usuarios(id) ON DELETE SET NULL,
        FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE SET NULL
    )");

    $conn->query("CREATE TABLE IF NOT EXISTS respostas (
        id INT AUTO_INCREMENT PRIMARY KEY,
        conteudo TEXT NOT NULL,
        user_id INT,
        topico_id INT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        midia VARCHAR(255),
        FOREIGN KEY (user_id) REFERENCES usuarios(id) ON DELETE SET NULL,
        FOREIGN KEY (topico_id) REFERENCES topicos(id) ON DELETE CASCADE
    )");

    $conn->query("CREATE TABLE IF NOT EXISTS seguir (
        id INT AUTO_INCREMENT PRIMARY KEY,
        seguidor_id INT,
        seguindo_id INT,
        FOREIGN KEY (seguidor_id) REFERENCES usuarios(id) ON DELETE CASCADE,
        FOREIGN KEY (seguindo_id) REFERENCES usuarios(id) ON DELETE CASCADE
    )");

    $conn->query("CREATE TABLE IF NOT EXISTS perfis (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT,
        nome VARCHAR(100),
        bio TEXT,
        avatar VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES usuarios(id) ON DELETE CASCADE
    )");

    $conn->query("CREATE TABLE IF NOT EXISTS curtidas_topicos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    topico_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (topico_id) REFERENCES topicos(id) ON DELETE CASCADE,
    UNIQUE KEY (user_id, topico_id)
)");
?>