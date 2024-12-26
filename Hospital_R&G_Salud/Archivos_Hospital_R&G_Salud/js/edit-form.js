function mostrarFormularioEdicion(idUser) {
    // Ocultar cualquier otro formulario abierto
    const formularios = document.querySelectorAll('.formulario-edicion');
    formularios.forEach(formulario => {
        formulario.style.display = 'none';
    });

    // Mostrar el formulario de edición correspondiente al usuario seleccionado
    const formularioEdicion = document.getElementById('formulario-edicion-' + idUser);
    formularioEdicion.style.display = 'block';
}

function ocultarFormularioEdicion(idUser) {
    // Ocultar el formulario de edición correspondiente
    const formularioEdicion = document.getElementById('formulario-edicion-' + idUser);
    formularioEdicion.style.display = 'none';
}
