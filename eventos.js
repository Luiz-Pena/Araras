// Simulação de dados de eventos
let eventos = [
    {
        titulo: "Vem pra UFU",
        descricao: "Um evento de portas abertas para a comunidade, com palestras, workshops e visitas guiadas pela universidade.",
        data: "12 a 16 de Julho"
    },
    {
        titulo: "Palestra: IA no Mercado",
        descricao: "Palestra com a participação de especialistas do mercado de tecnologia, discutindo o impacto da Inteligência Artificial em diversas áreas e as oportunidades de carreira.",
        data: "28 de Setembro, às 19:00"
    },
    {
        titulo: "Semana de Ciência e Tecnologia",
        descricao: "Evento anual que reúne a comunidade acadêmica em torno de apresentações de projetos de pesquisa, minicursos e palestras sobre as últimas tendências em ciência e tecnologia.",
        data: "15 a 19 de Outubro"
    }
];

// Função para renderizar a lista de eventos
function renderizarEventos() {
    const listaEventos = document.getElementById('listaEventos');
    listaEventos.innerHTML = '';
    
    eventos.forEach(evento => {
        listaEventos.innerHTML += `
            <ul>
                <li>
                    <h4 class="caixa-info-titulo">${evento.titulo}</h4>
                    <p>${evento.descricao}</p>
                    <span class="d-block mt-2"><strong>Quando:</strong> ${evento.data}</span>
                </li>
                <hr>
            </ul>
        `;
    });
}

// Lógica para criar um novo evento
document.getElementById('formNovoEvento').addEventListener('submit', function(e) {
    e.preventDefault();

    // Lógica de verificação de login (simulação)
    const usuarioLogado = true; // Substitua por sua lógica de verificação
    if (!usuarioLogado) {
        alert("Você precisa estar logado para criar um evento.");
        return;
    }

    const titulo = document.getElementById('tituloEvento').value;
    const data = document.getElementById('dataEvento').value;
    const descricao = document.getElementById('descricaoEvento').value;
    
    // Adiciona o novo evento no início da lista
    eventos.unshift({
        titulo,
        descricao,
        data
    });

    renderizarEventos();
    document.getElementById('formNovoEvento').reset();
    bootstrap.Modal.getInstance(document.getElementById('modalNovoEvento')).hide();
});

// Executa a função ao carregar a página
document.addEventListener('DOMContentLoaded', renderizarEventos);