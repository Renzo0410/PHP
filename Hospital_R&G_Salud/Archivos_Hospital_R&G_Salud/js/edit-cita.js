// ADMINISTRADORES
// Edición de cita
function openEditCitaAdminModal(idCita, motivo_cita, fecha_cita) {
    document.getElementById('editIdCita').value = idCita;
    document.getElementById('edit_motivo_cita').value = motivo_cita;
    document.getElementById('edit_fecha_cita').value = fecha_cita;
    
    var modal = new bootstrap.Modal(document.getElementById('editModalCitaAdmin'));
    modal.show();
}

// Eliminación de cita
function openDeleteCitaAdminModal(idCita) {
    document.getElementById('deleteIdCita').value = idCita;
    var modal = new bootstrap.Modal(document.getElementById('deleteModalCitaAdmin'));
    modal.show();
}

// --------------------------------------------------------
// USUARIOS
// Edición de cita
function openEditCitaUserModal(idCita, motivo_cita, fecha_cita) {
    document.getElementById('editIdCita').value = idCita;
    document.getElementById('edit_motivo_cita').value = motivo_cita;
    document.getElementById('edit_fecha_cita').value = fecha_cita;
    
    var modal = new bootstrap.Modal(document.getElementById('editModalCitaUser'));
    modal.show();
}

// Eliminación de cita
function openDeleteCitaUserModal(idCita) {
    document.getElementById('deleteIdCita').value = idCita;
    var modal = new bootstrap.Modal(document.getElementById('deleteModalCitaUser'));
    modal.show();
}