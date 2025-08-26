function validarFormulario() {
    let valido = true;
    
    const campos = ['#nome', '#email', '#senha', '#senha2'];
    
    campos.forEach(campo => {
        if ($(campo).val() === '') {
            $(campo).addClass('borda-vermelha');
            alert(`Por favor, preencha o campo ${$(campo).attr('placeholder') || $(campo).attr('name')}`);
            valido = false;
        } else {
            $(campo).removeClass('borda-vermelha');
            $(campo).css('border', '');
        }
    });
    
    if (!valido) return false;

    if ($('#senha').val() !== $('#senha2').val()) {
        $('#senha, #senha2').addClass('borda-vermelha');
        alert('As senhas não coincidem. Por favor, tente novamente.');
        valido = false;
    } else {
        $('#senha, #senha2').removeClass('borda-vermelha');
        $('#senha, #senha2').css('border', '');
    }
    
    return valido;
}
