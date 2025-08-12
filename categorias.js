// Dados de exemplo para simulação
const categorias = {
    "Agronomia": {
        nome: "Agronomia",
        descricao: "Fórum para estudantes e professores do curso de Agronomia.",
        subcategorias: {
            "Tópicos Gerais": "Discussões sobre o curso, estágios e eventos.",
            "Fitotecnia": "Estudos sobre manejo de culturas e produção vegetal.",
            "Solos e Nutrição": "Debates sobre a ciência do solo e fertilidade.",
            "Defesa Fitossanitária": "Controle de pragas, doenças e plantas daninhas."
        }
    },
    "Engenharia de Agrimensura e Cartográfica": {
        nome: "Engenharia de Agrimensura e Cartográfica",
        descricao: "Fórum para estudantes e professores do curso de Engenharia de Agrimensura e Cartográfica.",
        subcategorias: {
            "Tópicos Gerais": "Discussões gerais sobre o curso, estágios e eventos.",
            "Topografia": "Dúvidas e projetos sobre medição e representação do terreno.",
            "Geodésia e GPS": "Temas sobre sistemas de posicionamento global e geodésia.",
            "Sensoriamento Remoto": "Aplicações de imagens de satélite e fotogrametria."
        }
    },
    "Engenharia Florestal": {
        nome: "Engenharia Florestal",
        descricao: "Fórum para estudantes e professores do curso de Engenharia Florestal.",
        subcategorias: {
            "Tópicos Gerais": "Discussões sobre o curso, estágios e eventos.",
            "Silvicultura": "Cultivo e manejo de florestas.",
            "Manejo Florestal": "Planejamento e gestão de recursos florestais.",
            "Produtos Florestais": "Indústria da madeira e outros produtos da floresta."
        }
    },
    "Geologia": {
        nome: "Geologia",
        descricao: "Fórum para estudantes e professores do curso de Geologia.",
        subcategorias: {
            "Tópicos Gerais": "Discussões gerais sobre o curso, estágios e eventos.",
            "Mineralogia e Petrografia": "Estudo de minerais e rochas.",
            "Geologia Estrutural": "Análise de deformações e estruturas da crosta terrestre.",
            "Hidrogeologia": "Estudos sobre águas subterrâneas."
        }
    },
    "Sistemas de Informação": {
        nome: "Sistemas de Informação",
        descricao: "Fórum para estudantes e professores do curso de Sistemas de Informação.",
        subcategorias: {
            "Tópicos Gerais": "Discussões gerais sobre o curso, estágios e eventos.",
            "Programação Web": "HTML, CSS, JavaScript e frameworks.",
            "Bancos de Dados": "Modelagem, administração e linguagens SQL/NoSQL.",
            "Engenharia de Software": "Metodologias, testes e processos de desenvolvimento."
        }
    }
};

const usuarios = [
    { id: 1, nome: "Ricardo", avatar: "ImagemTeste01.jpg", usuario: "ricardo", senha: "123", curso: "Sistemas de Informação" },
    { id: 2, nome: "Cleber", avatar: "ImagemTeste02.jpg", usuario: "cleber", senha: "123", curso: "Sistemas de Informação" },
    { id: 3, nome: "Ana", avatar: "ImagemTeste03.jpg", usuario: "ana", senha: "123", curso: "Agronomia" }
];

const topicos = [
    { titulo: "Dúvidas sobre o trabalho de Web", autor: usuarios[0], curso: "Sistemas de Informação", categoria: "Programação Web", respostas: 12, ultimaResposta: "Hoje às 14:30" },
    { titulo: "Busco grupo para o projeto de POO", autor: usuarios[1], curso: "Sistemas de Informação", categoria: "Programação Web", respostas: 5, ultimaResposta: "Ontem às 21:07" },
    { titulo: "Erro ao compilar em C++", autor: usuarios[0], curso: "Sistemas de Informação", categoria: "Programação Web", respostas: 3, ultimaResposta: "Ontem às 18:00" },
    { titulo: "Início da colheita de café", autor: usuarios[2], curso: "Agronomia", categoria: "Tópicos Gerais", respostas: 8, ultimaResposta: "Hoje às 10:00" }
];

// Função para obter o parâmetro da URL
const urlParams = new URLSearchParams(window.location.search);
const cursoSelecionado = urlParams.get('curso');
const categoriaSelecionada = urlParams.get('cat');

function renderizarCursos() {
    const listaPrincipal = document.getElementById('listaPrincipal');
    const tituloPrincipal = document.getElementById('tituloPrincipal');
    
    tituloPrincipal.textContent = "Cursos UFU Monte Carmelo";
    listaPrincipal.innerHTML = '';
    
    for (const chave in categorias) {
        const curso = categorias[chave];
        listaPrincipal.innerHTML += `
            <div class="caixa-info mb-3">
                <h4 class="caixa-info-titulo"><a href="categorias.html?curso=${encodeURIComponent(curso.nome)}">${curso.nome}</a></h4>
                <p class="text-muted">${curso.descricao}</p>
            </div>
        `;
    }
}

function renderizarSubcategorias(curso) {
    const listaPrincipal = document.getElementById('listaPrincipal');
    const tituloPrincipal = document.getElementById('tituloPrincipal');
    
    tituloPrincipal.innerHTML = `Categorias de ${curso.nome}`;
    listaPrincipal.innerHTML = `<div class="topicos-cabecalho mb-3"><h3>Subcategorias</h3></div>`;

    for (const chave in curso.subcategorias) {
        listaPrincipal.innerHTML += `
            <div class="caixa-info mb-3">
                <h4 class="caixa-info-titulo"><a href="categorias.html?curso=${encodeURIComponent(curso.nome)}&cat=${encodeURIComponent(chave)}">${chave}</a></h4>
                <p class="text-muted">${curso.subcategorias[chave]}</p>
            </div>
        `;
    }
}

function renderizarTopicos(curso, categoria) {
    const listaPrincipal = document.getElementById('listaPrincipal');
    const tituloPrincipal = document.getElementById('tituloPrincipal');
    
    tituloPrincipal.innerHTML = `Tópicos: ${categoria} (${curso.nome})`;
    listaPrincipal.innerHTML = '';
    
    const topicosFiltrados = topicos.filter(t => t.curso === curso.nome && t.categoria === categoria);
    
    if (topicosFiltrados.length > 0) {
        topicosFiltrados.forEach(topico => {
            listaPrincipal.innerHTML += `
                <div class="cartao-topico">
                    <div class="topico-autor-avatar">
                        <img src="${topico.autor.avatar}" alt="Avatar do autor" class="rounded-circle">
                    </div>
                    <div class="topico-detalhes">
                        <h3 class="topico-titulo"><a href="#">${topico.titulo}</a></h3>
                        <div class="topico-info">
                            Iniciado por <a href="#" class="nome-autor">${topico.autor.nome}</a> em <a href="#" class="topico-categoria">${topico.categoria}</a>
                            <span class="topico-estatisticas">
                                ${topico.respostas} Respostas
                            </span>
                        </div>
                    </div>
                    <div class="topico-ultima-postagem">
                        <span>Última resposta</span>
                        <span>${topico.ultimaResposta}</span>
                    </div>
                </div>
            `;
        });
    } else {
        listaPrincipal.innerHTML = `<div class="p-4 text-center text-muted">Nenhum tópico encontrado nesta categoria.</div>`;
    }
}

// Lógica de navegação e renderização
if (cursoSelecionado && categoriaSelecionada) {
    renderizarTopicos(categorias[cursoSelecionado], categoriaSelecionada);
} else if (cursoSelecionado) {
    renderizarSubcategorias(categorias[cursoSelecionado]);
} else {
    renderizarCursos();
}