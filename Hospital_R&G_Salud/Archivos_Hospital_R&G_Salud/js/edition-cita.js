function openEditModal(idCita, motivoCita, fechaCita) {
    // Set values in the modal
    document.getElementById('editIdCita').value = idCita;
    document.getElementById('edit_motivo_cita').value = motivoCita;
    document.getElementById('edit_fecha_cita').value = fechaCita;
    // Show the modal
    var modal = new bootstrap.Modal(document.getElementById('editModal'));
    modal.show();
}