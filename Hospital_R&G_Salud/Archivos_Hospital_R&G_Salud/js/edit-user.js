// Editar usuarios
function openEditUserModal(idUser, nombre, apellidos, email, telefono, direccion, fechaNacimiento, rol) {
    document.getElementById('editIdUser').value = idUser;
    document.getElementById('editNombre').value = nombre;
    document.getElementById('editApellidos').value = apellidos;
    document.getElementById('editEmail').value = email;
    document.getElementById('editTelefono').value = telefono;
    document.getElementById('editDireccion').value = direccion;
    document.getElementById('editFechaNacimiento').value = fechaNacimiento;
    document.getElementById('editRol').value = rol; // Asigna el valor del rol

    var modal = new bootstrap.Modal(document.getElementById('editUserModal'));
    modal.show();
}

// Eliminar usuarios
function openDeleteUserModal(idUser) {
    document.getElementById('deleteIdUser').value = idUser;
    var modal = new bootstrap.Modal(document.getElementById('deleteUserModal'));
    modal.show();
}
