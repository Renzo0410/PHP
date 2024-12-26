// Edición de noticia
function openEditModalNoticias(idNoticia, titulo, texto, imagen) {
    document.getElementById('modalIdNoticiaEdit').value = idNoticia;
    document.getElementById('tituloEdit').value = titulo;
    document.getElementById('textoEdit').value = texto;
    document.getElementById('modalImagenActual').src = imagen ? '../img/noticias/' + imagen : '';

    var myModal = new bootstrap.Modal(document.getElementById('editModalNoticias'));
    myModal.show();
}


// Eliminación de noticia
function openDeleteNoticeModal(idNoticia) {
    document.getElementById('modalIdNoticia').value = idNoticia;
    var myModal = new bootstrap.Modal(document.getElementById('deleteModalNoticias'));
    myModal.show();
}
