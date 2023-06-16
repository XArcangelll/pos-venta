let tblUsuarios;
let tblClientes;
let tblCajas;
let tblCategorias;
let tblMedidas;
let tblProductos;
let tblHistorialCompra;
let tblHistorialVenta;
let tblProductosModal;
document.addEventListener("DOMContentLoaded", function () {
  listarUsuarios();
  listarClientes();
  listarCajas();
  listarCategorias();
  listarMedidas();
  listarProductos();
  PreviewFoto();
  borrarFoto();
  cargarDetalle();
  cargarDetalleVenta();
  listarHistorialCompras();
  listarHistorialVentas();
});
function frmLogin(e) {
  e.preventDefault();
  const usuario = document.getElementById("usuario");
  const clave = document.getElementById("clave");
  if (usuario.value == "") {
    clave.classList.remove("is-invalid");
    usuario.classList.add("is-invalid");
    usuario.focus();
  } else if (clave.value == "") {
    usuario.classList.remove("is-invalid");
    clave.classList.add("is-invalid");
    clave.focus();
  } else {
    const url = base_url + "Usuarios/validar";
    const frm = document.getElementById("frmLogin");
    const http = new XMLHttpRequest();
    http.open("POST", url, true);
    http.send(new FormData(frm));
    http.onreadystatechange = function () {
      if (this.readyState == 4 && this.status == 200) {
        console.log = this.responseText;
        const res = JSON.parse(this.responseText);
        if (res == "ok") {
          window.location = base_url + "Usuarios";
          /* Swal.fire({
                            icon: "success",
                            title: 'Ingresando al Sistema',
                          }
                          )
                          .then(function(){
                            window.location = base_url + "Usuarios";
                          })*/
        } else {
          document.getElementById("alerta").classList.remove("d-none");
          document.getElementById("alerta").innerHTML = res;
        }
      }
    };
  }
}

function listarUsuarios() {
  if (document.getElementById("tblUsuarios")) {
    tblUsuarios = $("#tblUsuarios").DataTable({
      destroy: true,
      responsive: true,

      language: {
        url: base_url + "Assets/json/español.json",
      },
      ajax: {
        url: base_url + "Usuarios/listar",
        dataSrc: "",
      },
      columns: [
        {
          data: "id",
        },
        {
          data: "usuario",
        },
        {
          data: "nombre",
        },
        {
          data: "caja",
        },
        {
          data: "estado",
        },
        {
          data: "acciones",
        },
      ],
    });
  }
}

function cerrarFormUser(e) {
  e.preventDefault();
  document.getElementById("frmUsuario").reset();
}

function registrarUser(e) {
  e.preventDefault();
  const usuario = document.getElementById("usuario");
  const clave = document.getElementById("clave");
  const nombre = document.getElementById("nombre");
  const confirmar = document.getElementById("confirmar");
  const caja = document.getElementById("caja");
  if (usuario.value == "" || nombre.value == "" || caja.value == "") {
    Swal.fire({
      icon: "error",
      title: "Todos los Campos son obligatorios",
      timer: 2500,
      showConfirmButton: false,
    });
  } else if (clave.value != confirmar.value) {
    Swal.fire({
      icon: "error",
      title: "Las Contraseñas no coinciden",
      timer: 2500,
      showConfirmButton: false,
    });
  } else {
    const url = base_url + "Usuarios/registrar";
    const frm = document.getElementById("frmUsuario");
    const http = new XMLHttpRequest();
    http.open("POST", url, true);
    http.send(new FormData(frm));
    http.onreadystatechange = function () {
      if (this.readyState == 4 && this.status == 200) {
        const res = JSON.parse(this.responseText);
        if (res == "ok") {
          Swal.fire({
            icon: "success",
            title: "Usuario registrado con éxito",
            timer: 2500,
            showConfirmButton: false,
          });
          frm.reset();
          $("#nuevo_usuario").modal("hide");
          tblUsuarios.ajax.reload(null, false);
        } else if (res == "modificado") {
          Swal.fire({
            icon: "success",
            title: "Usuario modificado con éxito",
            timer: 2500,
            showConfirmButton: false,
          });
          frm.reset();
          $("#nuevo_usuario").modal("hide");
          tblUsuarios.ajax.reload(null, false);
        } else {
          Swal.fire({
            icon: "error",
            title: res,
            timer: 2500,
            showConfirmButton: false,
          });
        }
      }
    };
  }
}

function btnNuevoUser() {
  document.getElementById("claves").classList.remove("d-none");
  document.getElementById("my-modal-title").innerHTML = "Nuevo Usuario";
  document.getElementById("btnAccion").innerHTML = "Registrar";
  document.getElementById("id").value = "";
  const frm = document.getElementById("frmUsuario");
  frm.reset();
}

function btnEditarUser(id) {
  document.getElementById("my-modal-title").innerHTML = "Actualizar Usuario";
  document.getElementById("btnAccion").innerHTML = "Modificar";
  document.getElementById("claves").classList.add("d-none");
  document.getElementById("usuario").value = "cargando...";
  document.getElementById("nombre").value = "cargando...";

  const url = base_url + "Usuarios/editar/" + id;
  const http = new XMLHttpRequest();
  http.open("GET", url, true);
  http.send();
  http.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      const res = JSON.parse(this.responseText);

      document.getElementById("id").value = res.id;
      document.getElementById("usuario").value = res.usuario;
      document.getElementById("nombre").value = res.nombre;
      document.getElementById("caja").value = res.id_caja;
    }
  };
}

function btnEliminarUser(id) {
  Swal.fire({
    title: "¿Está seguro de eliminar el Usuario con el ID " + id + " ?",
    text: "El Usuario no se eliminará de forma permanente, solo cambiará a estado inactivo.",
    icon: "warning",
    showCancelButton: true,
    cancelButtonText: "No",
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Sí",
  }).then((result) => {
    if (result.isConfirmed) {
      const url = base_url + "Usuarios/eliminar/" + id;
      const http = new XMLHttpRequest();
      http.open("GET", url, true);
      http.send();
      http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
          const res = JSON.parse(this.responseText);
          if (res == "ok") {
            Swal.fire({
              icon: "success",
              timer: 2500,
              title: "Eliminado",
              text: "El Usuario ha sido eliminado exitosamente.",
            });
            tblUsuarios.ajax.reload(null, false);
          } else {
            Swal.fire({
              icon: "error",
              title: res,
            });
          }
        }
      };
    }
  });
}

function btnReingresarUser(id) {
  Swal.fire({
    title: "¿Está seguro de reingresar el Usuario con el ID " + id + " ?",
    icon: "warning",
    showCancelButton: true,
    cancelButtonText: "No",
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Sí",
  }).then((result) => {
    if (result.isConfirmed) {
      const url = base_url + "Usuarios/reingresar/" + id;
      const http = new XMLHttpRequest();
      http.open("GET", url, true);
      http.send();
      http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
          const res = JSON.parse(this.responseText);
          if (res == "ok") {
            Swal.fire({
              icon: "success",
              timer: 2500,
              title: "Reingresado",
              text: "El Usuario ha sido reingresado exitosamente.",
            });
            tblUsuarios.ajax.reload(null, false);
          } else {
            Swal.fire({
              icon: "error",
              title: res,
            });
          }
        }
      };
    }
  });
}

//codigo para clientes

function listarClientes() {
  if (document.getElementById("tblClientes")) {
    tblClientes = $("#tblClientes").DataTable({
      destroy: true,
      responsive: true,

      language: {
        url: base_url + "Assets/json/español.json",
      },
      ajax: {
        url: base_url + "Clientes/listar",
        dataSrc: "",
      },
      columns: [
        {
          data: "id",
        },
        {
          data: "dni",
        },
        {
          data: "nombre",
        },
        {
          data: "telefono",
        },
        {
          data: "direccion",
        },
        {
          data: "estado",
        },
        {
          data: "acciones",
        },
      ],
    });
  }
}

function cerrarFormCliente(e) {
  e.preventDefault();
  document.getElementById("frmCliente").reset();
}

function registrarCliente(e) {
  e.preventDefault();
  const DNI = document.getElementById("dni");
  const telefono = document.getElementById("telefono");
  const nombre = document.getElementById("nombre");
  const direccion = document.getElementById("direccion");
  if (
    DNI.value == "" ||
    nombre.value == "" ||
    telefono.value == "" ||
    direccion.value == ""
  ) {
    Swal.fire({
      icon: "error",
      title: "Todos los Campos son obligatorios",
      timer: 2500,
      showConfirmButton: false,
    });
  } else {
    const url = base_url + "Clientes/registrar";
    const frm = document.getElementById("frmCliente");
    const http = new XMLHttpRequest();
    http.open("POST", url, true);
    http.send(new FormData(frm));
    http.onreadystatechange = function () {
      if (this.readyState == 4 && this.status == 200) {
        const res = JSON.parse(this.responseText);
        if (res == "ok") {
          Swal.fire({
            icon: "success",
            title: "Cliente registrado con éxito",
            timer: 2500,
            showConfirmButton: false,
          });
          frm.reset();
          $("#nuevo_cliente").modal("hide");
          tblClientes.ajax.reload(null, false);
        } else if (res == "modificado") {
          Swal.fire({
            icon: "success",
            title: "Cliente modificado con éxito",
            timer: 2500,
            showConfirmButton: false,
          });
          frm.reset();
          $("#nuevo_cliente").modal("hide");
          tblClientes.ajax.reload(null, false);
        } else {
          Swal.fire({
            icon: "error",
            title: res,
            timer: 2500,
            showConfirmButton: false,
          });
        }
      }
    };
  }
}

function btnNuevoCliente() {
  document.getElementById("my-modal-title").innerHTML = "Nuevo Cliente";
  document.getElementById("btnAccion").innerHTML = "Registrar";
  document.getElementById("id").value = "";
  const frm = document.getElementById("frmCliente");
  frm.reset();
}

function btnEditarCliente(id) {
  document.getElementById("my-modal-title").innerHTML = "Actualizar Cliente";
  document.getElementById("btnAccion").innerHTML = "Modificar";
  document.getElementById("nombre").value = "cargando...";
  document.getElementById("direccion").value = "cargando...";

  const url = base_url + "Clientes/editar/" + id;
  const http = new XMLHttpRequest();
  http.open("GET", url, true);
  http.send();
  http.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      const res = JSON.parse(this.responseText);

      document.getElementById("id").value = res.id;
      document.getElementById("dni").value = res.dni;
      document.getElementById("nombre").value = res.nombre;
      document.getElementById("direccion").value = res.direccion;
      document.getElementById("telefono").value = res.telefono;
    }
  };
}

function btnEliminarCliente(id) {
  Swal.fire({
    title: "¿Está seguro de eliminar el Cliente con el ID " + id + " ?",
    text: "El Cliente no se eliminará de forma permanente, solo cambiará a estado inactivo.",
    icon: "warning",
    showCancelButton: true,
    cancelButtonText: "No",
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Sí",
  }).then((result) => {
    if (result.isConfirmed) {
      const url = base_url + "Clientes/eliminar/" + id;
      const http = new XMLHttpRequest();
      http.open("GET", url, true);
      http.send();
      http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
          const res = JSON.parse(this.responseText);
          if (res == "ok") {
            Swal.fire({
              icon: "success",
              timer: 2500,
              title: "Eliminado",
              text: "El Cliente ha sido eliminado exitosamente.",
            });
            tblClientes.ajax.reload(null, false);
          } else {
            Swal.fire({
              icon: "error",
              title: res,
            });
          }
        }
      };
    }
  });
}

function btnReingresarCliente(id) {
  Swal.fire({
    title: "¿Está seguro de reingresar el Cliente con el ID " + id + " ?",
    icon: "warning",
    showCancelButton: true,
    cancelButtonText: "No",
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Sí",
  }).then((result) => {
    if (result.isConfirmed) {
      const url = base_url + "Clientes/reingresar/" + id;
      const http = new XMLHttpRequest();
      http.open("GET", url, true);
      http.send();
      http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
          const res = JSON.parse(this.responseText);
          if (res == "ok") {
            Swal.fire({
              icon: "success",
              timer: 2500,
              title: "Reingresado",
              text: "El Cliente ha sido reingresado exitosamente.",
            });
            tblClientes.ajax.reload(null, false);
          } else {
            Swal.fire({
              icon: "error",
              title: res,
            });
          }
        }
      };
    }
  });
}

//codigo para cajas

function listarCajas() {
  if (document.getElementById("tblCajas")) {
    tblCajas = $("#tblCajas").DataTable({
      destroy: true,
      responsive: true,

      language: {
        url: base_url + "Assets/json/español.json",
      },
      ajax: {
        url: base_url + "Cajas/listar",
        dataSrc: "",
      },
      columns: [
        {
          data: "id",
        },
        {
          data: "caja",
        },
        {
          data: "estado",
        },
        {
          data: "acciones",
        },
      ],
    });
  }
}

function cerrarFormCaja(e) {
  e.preventDefault();
  document.getElementById("frmCaja").reset();
}

function registrarCaja(e) {
  e.preventDefault();
  const caja = document.getElementById("caja");
  if (caja.value == "") {
    Swal.fire({
      icon: "error",
      title: "Todos los Campos son obligatorios",
      timer: 2500,
      showConfirmButton: false,
    });
  } else {
    const url = base_url + "Cajas/registrar";
    const frm = document.getElementById("frmCaja");
    const http = new XMLHttpRequest();
    http.open("POST", url, true);
    http.send(new FormData(frm));
    http.onreadystatechange = function () {
      if (this.readyState == 4 && this.status == 200) {
        const res = JSON.parse(this.responseText);
        if (res == "ok") {
          Swal.fire({
            icon: "success",
            title: "Caja registrado con éxito",
            timer: 2500,
            showConfirmButton: false,
          });
          frm.reset();
          $("#nuevo_caja").modal("hide");
          tblCajas.ajax.reload(null, false);
        } else if (res == "modificado") {
          Swal.fire({
            icon: "success",
            title: "Caja modificado con éxito",
            timer: 2500,
            showConfirmButton: false,
          });
          frm.reset();
          $("#nuevo_caja").modal("hide");
          tblCajas.ajax.reload(null, false);
        } else {
          Swal.fire({
            icon: "error",
            title: res,
            timer: 2500,
            showConfirmButton: false,
          });
        }
      }
    };
  }
}

function btnNuevoCaja() {
  document.getElementById("my-modal-title").innerHTML = "Nueva Caja";
  document.getElementById("btnAccion").innerHTML = "Registrar";
  document.getElementById("id").value = "";
  const frm = document.getElementById("frmCaja");
  frm.reset();
}

function btnEditarCaja(id) {
  document.getElementById("my-modal-title").innerHTML = "Actualizar Caja";
  document.getElementById("btnAccion").innerHTML = "Modificar";
  document.getElementById("caja").value = "cargando...";

  const url = base_url + "Cajas/editar/" + id;
  const http = new XMLHttpRequest();
  http.open("GET", url, true);
  http.send();
  http.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      const res = JSON.parse(this.responseText);

      document.getElementById("id").value = res.id;
      document.getElementById("caja").value = res.caja;
    }
  };
}

function btnEliminarCaja(id) {
  Swal.fire({
    title: "¿Está seguro de eliminar la Caja con el ID " + id + " ?",
    text: "La caja no se eliminará de forma permanente, solo cambiará a estado inactivo.",
    icon: "warning",
    showCancelButton: true,
    cancelButtonText: "No",
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Sí",
  }).then((result) => {
    if (result.isConfirmed) {
      const url = base_url + "Cajas/eliminar/" + id;
      const http = new XMLHttpRequest();
      http.open("GET", url, true);
      http.send();
      http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
          const res = JSON.parse(this.responseText);
          if (res == "ok") {
            Swal.fire({
              icon: "success",
              timer: 2500,
              title: "Eliminado",
              text: "La caja ha sido eliminado exitosamente.",
            });
            tblCajas.ajax.reload(null, false);
          } else {
            Swal.fire({
              icon: "error",
              title: res,
            });
          }
        }
      };
    }
  });
}

function btnReingresarCaja(id) {
  Swal.fire({
    title: "¿Está seguro de reingresar la Caja con el ID " + id + " ?",
    icon: "warning",
    showCancelButton: true,
    cancelButtonText: "No",
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Sí",
  }).then((result) => {
    if (result.isConfirmed) {
      const url = base_url + "Cajas/reingresar/" + id;
      const http = new XMLHttpRequest();
      http.open("GET", url, true);
      http.send();
      http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
          const res = JSON.parse(this.responseText);
          if (res == "ok") {
            Swal.fire({
              icon: "success",
              timer: 2500,
              title: "Reingresado",
              text: "La caja ha sido reingresado exitosamente.",
            });
            tblCajas.ajax.reload(null, false);
          } else {
            Swal.fire({
              icon: "error",
              title: res,
            });
          }
        }
      };
    }
  });
}

//codigo para categorias

function listarCategorias() {
  if (document.getElementById("tblCategorias")) {
    tblCategorias = $("#tblCategorias").DataTable({
      destroy: true,
      responsive: true,

      language: {
        url: base_url + "Assets/json/español.json",
      },
      ajax: {
        url: base_url + "Categorias/listar",
        dataSrc: "",
      },
      columns: [
        {
          data: "id",
        },
        {
          data: "nombre",
        },
        {
          data: "estado",
        },
        {
          data: "acciones",
        },
      ],
    });
  }
}

function cerrarFormCategoria(e) {
  e.preventDefault();
  document.getElementById("frmCategoria").reset();
}

function registrarCategoria(e) {
  e.preventDefault();
  const nombre = document.getElementById("nombre");
  if (nombre.value == "") {
    Swal.fire({
      icon: "error",
      title: "Todos los Campos son obligatorios",
      timer: 2500,
      showConfirmButton: false,
    });
  } else {
    const url = base_url + "Categorias/registrar";
    const frm = document.getElementById("frmCategoria");
    const http = new XMLHttpRequest();
    http.open("POST", url, true);
    http.send(new FormData(frm));
    http.onreadystatechange = function () {
      if (this.readyState == 4 && this.status == 200) {
        const res = JSON.parse(this.responseText);
        if (res == "ok") {
          Swal.fire({
            icon: "success",
            title: "Categoría registrada con éxito",
            timer: 2500,
            showConfirmButton: false,
          });
          frm.reset();
          $("#nuevo_categoria").modal("hide");
          tblCategorias.ajax.reload(null, false);
        } else if (res == "modificado") {
          Swal.fire({
            icon: "success",
            title: "Categoría modificada con éxito",
            timer: 2500,
            showConfirmButton: false,
          });
          frm.reset();
          $("#nuevo_categoria").modal("hide");
          tblCategorias.ajax.reload(null, false);
        } else {
          Swal.fire({
            icon: "error",
            title: res,
            timer: 2500,
            showConfirmButton: false,
          });
        }
      }
    };
  }
}

function btnNuevoCategoria() {
  document.getElementById("my-modal-title").innerHTML = "Nueva Categoría";
  document.getElementById("btnAccion").innerHTML = "Registrar";
  document.getElementById("id").value = "";
  const frm = document.getElementById("frmCategoria");
  frm.reset();
}

function btnEditarCategoria(id) {
  document.getElementById("my-modal-title").innerHTML = "Actualizar Categoría";
  document.getElementById("btnAccion").innerHTML = "Modificar";
  document.getElementById("nombre").value = "cargando...";

  const url = base_url + "Categorias/editar/" + id;
  const http = new XMLHttpRequest();
  http.open("GET", url, true);
  http.send();
  http.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      const res = JSON.parse(this.responseText);

      document.getElementById("id").value = res.id;
      document.getElementById("nombre").value = res.nombre;
    }
  };
}

function btnEliminarCategoria(id) {
  Swal.fire({
    title: "¿Está seguro de eliminar la Categoría con el ID " + id + " ?",
    text: "La categoría no se eliminará de forma permanente, solo cambiará a estado inactivo.",
    icon: "warning",
    showCancelButton: true,
    cancelButtonText: "No",
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Sí",
  }).then((result) => {
    if (result.isConfirmed) {
      const url = base_url + "Categorias/eliminar/" + id;
      const http = new XMLHttpRequest();
      http.open("GET", url, true);
      http.send();
      http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
          const res = JSON.parse(this.responseText);
          if (res == "ok") {
            Swal.fire({
              icon: "success",
              timer: 2500,
              title: "Eliminado",
              text: "La categoría ha sido eliminada exitosamente.",
            });
            tblCategorias.ajax.reload(null, false);
          } else {
            Swal.fire({
              icon: "error",
              title: res,
            });
          }
        }
      };
    }
  });
}

function btnReingresarCategoria(id) {
  Swal.fire({
    title: "¿Está seguro de reingresar la Categoría con el ID " + id + " ?",
    icon: "warning",
    showCancelButton: true,
    cancelButtonText: "No",
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Sí",
  }).then((result) => {
    if (result.isConfirmed) {
      const url = base_url + "Categorias/reingresar/" + id;
      const http = new XMLHttpRequest();
      http.open("GET", url, true);
      http.send();
      http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
          const res = JSON.parse(this.responseText);
          if (res == "ok") {
            Swal.fire({
              icon: "success",
              timer: 2500,
              title: "Reingresado",
              text: "La categoría ha sido reingresada exitosamente.",
            });
            tblCategorias.ajax.reload(null, false);
          } else {
            Swal.fire({
              icon: "error",
              title: res,
            });
          }
        }
      };
    }
  });
}

//codigo para Medidas

function listarMedidas() {
  if (document.getElementById("tblMedidas")) {
    tblMedidas = $("#tblMedidas").DataTable({
      destroy: true,
      responsive: true,

      language: {
        url: base_url + "Assets/json/español.json",
      },
      ajax: {
        url: base_url + "Medidas/listar",
        dataSrc: "",
      },
      columns: [
        {
          data: "id",
        },
        {
          data: "nombre",
        },
        {
          data: "nombre_corto",
        },
        {
          data: "estado",
        },
        {
          data: "acciones",
        },
      ],
    });
  }
}

function cerrarFormMedida(e) {
  e.preventDefault();
  document.getElementById("frmMedida").reset();
}

function registrarMedida(e) {
  e.preventDefault();
  const nombre = document.getElementById("nombre");
  const nombre_corto = document.getElementById("nombre_corto");
  if (nombre.value == "" || nombre_corto.value == "") {
    Swal.fire({
      icon: "error",
      title: "Todos los Campos son obligatorios",
      timer: 2500,
      showConfirmButton: false,
    });
  } else {
    const url = base_url + "Medidas/registrar";
    const frm = document.getElementById("frmMedida");
    const http = new XMLHttpRequest();
    http.open("POST", url, true);
    http.send(new FormData(frm));
    http.onreadystatechange = function () {
      if (this.readyState == 4 && this.status == 200) {
        const res = JSON.parse(this.responseText);
        if (res == "ok") {
          Swal.fire({
            icon: "success",
            title: "Medida registrada con éxito",
            timer: 2500,
            showConfirmButton: false,
          });
          frm.reset();
          $("#nuevo_medida").modal("hide");
          tblMedidas.ajax.reload(null, false);
        } else if (res == "modificado") {
          Swal.fire({
            icon: "success",
            title: "Medida modificada con éxito",
            timer: 2500,
            showConfirmButton: false,
          });
          frm.reset();
          $("#nuevo_medida").modal("hide");
          tblMedidas.ajax.reload(null, false);
        } else {
          Swal.fire({
            icon: "error",
            title: res,
            timer: 2500,
            showConfirmButton: false,
          });
        }
      }
    };
  }
}

function btnNuevoMedida() {
  document.getElementById("my-modal-title").innerHTML = "Nueva Medida";
  document.getElementById("btnAccion").innerHTML = "Registrar";
  document.getElementById("id").value = "";
  const frm = document.getElementById("frmMedida");
  frm.reset();
}

function btnEditarMedida(id) {
  document.getElementById("my-modal-title").innerHTML = "Actualizar Medida";
  document.getElementById("btnAccion").innerHTML = "Modificar";
  document.getElementById("nombre").value = "cargando...";
  document.getElementById("nombre_corto").value = "cargando...";

  const url = base_url + "Medidas/editar/" + id;
  const http = new XMLHttpRequest();
  http.open("GET", url, true);
  http.send();
  http.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      const res = JSON.parse(this.responseText);

      document.getElementById("id").value = res.id;
      document.getElementById("nombre").value = res.nombre;
      document.getElementById("nombre_corto").value = res.nombre_corto;
    }
  };
}

function btnEliminarMedida(id) {
  Swal.fire({
    title: "¿Está seguro de eliminar la Medida con el ID " + id + " ?",
    text: "La medida no se eliminará de forma permanente, solo cambiará a estado inactivo.",
    icon: "warning",
    showCancelButton: true,
    cancelButtonText: "No",
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Sí",
  }).then((result) => {
    if (result.isConfirmed) {
      const url = base_url + "Medidas/eliminar/" + id;
      const http = new XMLHttpRequest();
      http.open("GET", url, true);
      http.send();
      http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
          const res = JSON.parse(this.responseText);
          if (res == "ok") {
            Swal.fire({
              icon: "success",
              timer: 2500,
              title: "Eliminado",
              text: "La medida ha sido eliminada exitosamente.",
            });
            tblMedidas.ajax.reload(null, false);
          } else {
            Swal.fire({
              icon: "error",
              title: res,
            });
          }
        }
      };
    }
  });
}

function btnReingresarMedida(id) {
  Swal.fire({
    title: "¿Está seguro de reingresar la Medida con el ID " + id + " ?",
    icon: "warning",
    showCancelButton: true,
    cancelButtonText: "No",
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Sí",
  }).then((result) => {
    if (result.isConfirmed) {
      const url = base_url + "Medidas/reingresar/" + id;
      const http = new XMLHttpRequest();
      http.open("GET", url, true);
      http.send();
      http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
          const res = JSON.parse(this.responseText);
          if (res == "ok") {
            Swal.fire({
              icon: "success",
              timer: 2500,
              title: "Reingresado",
              text: "La medida ha sido reingresada exitosamente.",
            });
            tblMedidas.ajax.reload(null, false);
          } else {
            Swal.fire({
              icon: "error",
              title: res,
            });
          }
        }
      };
    }
  });
}

//codigo para productos

function listarProductos() {
  if (document.getElementById("tblProductos")) {
    tblProductos = $("#tblProductos").DataTable({
      destroy: true,
      responsive: true,
      language: {
        url: base_url + "Assets/json/español.json",
      },
      ajax: {
        url: base_url + "Productos/listar",
        dataSrc: "",
      },
      
      dom: "<'row'<'col-md-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>" +
      "<'row'<'col-sm-12'tr>>" +
      "<'row'<'col-sm-12 col-md-5'i><'col-md-7'p>>"
      ,
      buttons: [
        {
          extend: 'excelHtml5',
          footer: true,
          title: 'Archivo',
          filename: 'Export_file',
          text: '<span class="btn btn-success"><i class="fas fa-file-excel"></i></span>'
        },{
          extend: 'pdfHtml5',
          text: '<span class="btn btn-danger"><i class="fas fa-file-pdf"></i></span>',
        },
        {
          extend: 'colvis',
          text: '<span class="btn btn-info"><i class="fas fa-table-columns"></i></span>',
        }
      ],
      columns: [
        {
          data: "id",
        },
        {
          data: "codigo",
        },
        {
          data: "descripcion",
        },
        {
          data: "foto",
        },
        {
          data: "precio_venta",
        },
        {
          data: "cantidad",
        },
        {
          data: "nombre_medida",
        },
        {
          data: "nombre_categoria",
        },
        {
          data: "estado",
        },
        {
          data: "acciones",
        },
      ],
    });
  }
}

function cerrarFormProducto(e) {
  e.preventDefault();
  document.getElementById("frmProducto").reset();
  if (document.getElementById("img")) {
    document.getElementById("img").remove();
  }
  document
    .getElementsByClassName("prevPhoto")[0]
    .classList.remove("prevPhoto2");
  document.getElementsByClassName("delPhoto")[0].classList.add("notBlock");
  let uploadFoto = document.getElementById("foto").value;
  uploadFoto = "";

  if (
    document.getElementById("foto_actual") &&
    document.getElementById("foto_remove")
  ) {
    document.getElementById("foto_actual").remove();
    document.getElementById("foto_remove").remove();
  }
}

function registrarProducto(e) {
  e.preventDefault();
  const codigo = document.getElementById("codigo");
  const descripcion = document.getElementById("descripcion");
  const precio_compra = document.getElementById("precio_compra");
  const precio_venta = document.getElementById("precio_venta");
  const cantidad = document.getElementById("adicional");
  const medida = document.getElementById("medida");
  const categoria = document.getElementById("categoria");
  if (
    codigo.value == "" ||
    descripcion.value == "" ||
    precio_compra.value == "" ||
    precio_venta.value == "" ||
    medida.value == "" ||
    categoria.value == ""
  ) {
    Swal.fire({
      icon: "error",
      title: "Todos los Campos son obligatorios",
      timer: 2500,
      showConfirmButton: false,
    });
  } else {
    const url = base_url + "Productos/registrar";
    const frm = document.getElementById("frmProducto");
    const http = new XMLHttpRequest();
    http.open("POST", url, true);
    http.send(new FormData(frm));
    http.onreadystatechange = function () {
      if (this.readyState == 4 && this.status == 200) {
        const res = JSON.parse(this.responseText);
        if (res == "ok") {
          Swal.fire({
            icon: "success",
            title: "Producto registrado con éxito",
            timer: 2500,
            showConfirmButton: false,
          });
          frm.reset();
          $("#nuevo_producto").modal("hide");
          tblProductos.ajax.reload(null, false);
        } else if (res == "modificado") {
          Swal.fire({
            icon: "success",
            title: "Producto modificado con éxito",
            timer: 2500,
            showConfirmButton: false,
          });
          frm.reset();
          $("#nuevo_producto").modal("hide");
          tblProductos.ajax.reload(null, false);
        } else {
          Swal.fire({
            icon: "error",
            title: res,
            timer: 2500,
            showConfirmButton: false,
          });
        }
      }
    };
  }
}

function btnNuevoProducto() {
  if (
    document.getElementById("foto_actual") &&
    document.getElementById("foto_remove")
  ) {
    document.getElementById("foto_actual").remove();
    document.getElementById("foto_remove").remove();
  }
  if (document.getElementById("img")) {
    document.getElementById("img").remove();
  }
  let uploadFoto = document.getElementById("foto").value;
  uploadFoto = "";
  document.getElementsByClassName("delPhoto")[0].classList.add("notBlock");
  document
    .getElementsByClassName("prevPhoto")[0]
    .classList.remove("prevPhoto2");
  document.getElementById("my-modal-title").innerHTML = "Nuevo Producto";
  document.getElementById("btnAccion").innerHTML = "Registrar";
  document.getElementById("id").value = "";
  const frm = document.getElementById("frmProducto");
  frm.reset();
}

function btnEditarProducto(id) {
  document.getElementById("my-modal-title").innerHTML = "Actualizar Producto";
  document.getElementById("btnAccion").innerHTML = "Modificar";
  document.getElementById("codigo").value = "cargando...";
  document.getElementById("descripcion").value = "cargando...";
  document.getElementById("precio_compra").value = 0.0;
  document.getElementById("precio_venta").value = 0.0;
  document.getElementById("adicional").value = 0.0;

  const url = base_url + "Productos/editar/" + id;
  const http = new XMLHttpRequest();
  http.open("GET", url, true);
  http.send();
  http.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      const res = JSON.parse(this.responseText);

      document.getElementById("id").value = res.id;
      document.getElementById("codigo").value = res.codigo;
      document.getElementById("descripcion").value = res.descripcion;
      document.getElementById("precio_compra").value = res.precio_compra;
      document.getElementById("precio_venta").value = res.precio_venta;
      document.getElementById("adicional").value = res.adicional;
      document.getElementById("medida").value = res.id_medida;
      document.getElementById("categoria").value = res.id_categoria;

      if (
        document.getElementById("foto_actual") &&
        document.getElementById("foto_remove")
      ) {
        document.getElementById("foto_actual").remove();
        document.getElementById("foto_remove").remove();
      }

      let actual = document.createElement("input");
      actual.setAttribute("type", "hidden");
      actual.setAttribute("name", "foto_actual");
      actual.setAttribute("id", "foto_actual");
      actual.setAttribute("value", res.foto);
      let remove = document.createElement("input");
      remove.setAttribute("type", "hidden");
      remove.setAttribute("name", "foto_remove");
      remove.setAttribute("id", "foto_remove");
      remove.setAttribute("value", res.foto);
      let formulario = document.getElementById("id").parentNode;
      formulario.insertBefore(actual, document.getElementById("id"));
      formulario.insertBefore(remove, document.getElementById("id"));

      if (res.foto != "img_producto.png") {
        if (document.getElementById("img")) {
          document.getElementById("img").remove();
        }

        document.getElementById("previo").classList.add("opacidad");

        document
          .getElementsByClassName("delPhoto")[0]
          .classList.remove("notBlock");
        document
          .getElementsByClassName("prevPhoto")[0]
          .classList.add("prevPhoto2");
        document.getElementsByClassName("prevPhoto")[0].innerHTML +=
          "<img width='100%' height='auto' id='img' src='" +
          base_url +
          "Assets/img/" +
          res.foto +
          "'>";
        borrarFoto();
      } else {
        if (document.getElementById("img")) {
          document.getElementById("img").remove();
        }

        document.getElementById("previo").classList.add("opacidad");
        document
          .getElementsByClassName("delPhoto")[0]
          .classList.add("notBlock");
        document
          .getElementsByClassName("prevPhoto")[0]
          .classList.remove("prevPhoto2");
      }
    }
  };
}

function btnEliminarProducto(id) {
  Swal.fire({
    title: "¿Está seguro de eliminar el Producto con el ID " + id + " ?",
    text: "El Producto no se eliminará de forma permanente, solo cambiará a estado inactivo.",
    icon: "warning",
    showCancelButton: true,
    cancelButtonText: "No",
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Sí",
  }).then((result) => {
    if (result.isConfirmed) {
      const url = base_url + "Productos/eliminar/" + id;
      const http = new XMLHttpRequest();
      http.open("GET", url, true);
      http.send();
      http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
          const res = JSON.parse(this.responseText);
          if (res == "ok") {
            Swal.fire({
              icon: "success",
              timer: 2500,
              title: "Eliminado",
              text: "El Producto ha sido eliminado exitosamente.",
            });
            tblProductos.ajax.reload(null, false);
          } else {
            Swal.fire({
              icon: "error",
              title: res,
            });
          }
        }
      };
    }
  });
}

function btnReingresarProducto(id) {
  Swal.fire({
    title: "¿Está seguro de reingresar el Producto con el ID " + id + " ?",
    icon: "warning",
    showCancelButton: true,
    cancelButtonText: "No",
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Sí",
  }).then((result) => {
    if (result.isConfirmed) {
      const url = base_url + "Productos/reingresar/" + id;
      const http = new XMLHttpRequest();
      http.open("GET", url, true);
      http.send();
      http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
          const res = JSON.parse(this.responseText);
          if (res == "ok") {
            Swal.fire({
              icon: "success",
              timer: 2500,
              title: "Reingresado",
              text: "El Producto ha sido reingresado exitosamente.",
            });
            tblProductos.ajax.reload(null, false);
          } else {
            Swal.fire({
              icon: "error",
              title: res,
            });
          }
        }
      };
    }
  });
}

function PreviewFoto() {
  let fotito = document.getElementById("foto");

  if (fotito) {
    fotito.addEventListener("change", function () {
      let uploadFoto = document.getElementById("foto").value;
      let foto = document.getElementById("foto").files;
      let nav = window.URL || window.webkitURL;

      if (uploadFoto != "") {
        let type = foto[0].type;
        let name = foto[0].name;

        if (
          type != "image/jpeg" &&
          type != "image/jpg" &&
          type != "image/png"
        ) {
          if (document.getElementById("img")) {
            document.getElementById("img").remove();
          }

          document
            .getElementsByClassName("delPhoto")[0]
            .classList.add("notBlock");
          document.getElementById("foto").value = "";
          Swal.fire({
            text: "Formato no válido",
            icon: "warning",
          });
          return false;
        } else {
          if (document.getElementById("img")) {
            document.getElementById("img").remove();
          }

          document.getElementById("previo").classList.add("opacidad");

          document
            .getElementsByClassName("delPhoto")[0]
            .classList.remove("notBlock");
          document
            .getElementsByClassName("prevPhoto")[0]
            .classList.add("prevPhoto2");
          const objeto_url = nav.createObjectURL(this.files[0]);
          document.getElementsByClassName("prevPhoto")[0].innerHTML +=
            "<img width='100%' height='auto' id='img' src=" + objeto_url + ">";
          borrarFoto();
        }
      } else {
        Swal.fire({
          title: "No seleccionó Foto",
        });
        document.getElementById("img").remove();
        document
          .getElementsByClassName("delPhoto")[0]
          .classList.add("notBlock");
        document
          .getElementsByClassName("prevPhoto")[0]
          .classList.remove("prevPhoto2");
      }
    });
  }
}

function borrarFoto() {
  let borrar = document.getElementsByClassName("delPhoto")[0];
  if (borrar) {
    borrar.addEventListener("click", function () {
      Swal.fire({
        title: "¿Seguro de remover la imagen?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        cancelButtonText: "No",
        confirmButtonText: "Sí",
      }).then((result) => {
        if (result.isConfirmed) {
          document.getElementById("previo").classList.remove("opacidad");
          document.getElementById("foto").value = "";
          document
            .getElementsByClassName("delPhoto")[0]
            .classList.add("notBlock");
          document
            .getElementsByClassName("prevPhoto")[0]
            .classList.remove("prevPhoto2");
          document.getElementById("img").remove();

          if (
            document.getElementById("foto_actual") &&
            document.getElementById("foto_remove")
          ) {
            document.getElementById("foto_remove").value = "img_producto.png";
          }
        }
      });
    });
  }
}

function buscarCodigo(e) {
  e.preventDefault();
  const cod = document.getElementById("codigo").value;
  const url = base_url + "Compras/buscarCodigo/" + cod;
  const http = new XMLHttpRequest();
  http.open("GET", url, true);
  http.send();
  http.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      const res = JSON.parse(this.responseText);
      if (res) {
        document.getElementById("id").value = res.id;
        document.getElementById("nombre").value = res.descripcion;
        document.getElementById("stock").value = res.cantidad;
          document.getElementById("cantidad").value = 1;
          document.getElementById("medida").value = res.id_medida;
          document.getElementById("cantidad").removeAttribute("disabled");
          document.getElementById("agregar").classList.remove("invisible");
          document.getElementById("agregar").removeAttribute("disabled");
        document.getElementById("precio").value = res.precio_compra;
        if(res.id_medida != 2){
          document.getElementById("precio_total").value = (
            res.precio_compra * document.getElementById("cantidad").value
          ).toFixed(2);
          }else{
            document.getElementById("precio_total").value = (
              (res.precio_compra * document.getElementById("cantidad").value)/1000
            ).toFixed(2);
          }
        document.getElementById("cantidad").focus();
      } else {
        document.getElementById("id").value = "";
        document.getElementById("nombre").value = "";
        document.getElementById("stock").value = "";
        document.getElementById("cantidad").value = "";
        document.getElementById("medida").value = "";
        document.getElementById("cantidad").setAttribute("disabled", "");
        document.getElementById("precio").value = "";
        document.getElementById("precio_total").value = "";
        document.getElementById("agregar").classList.add("invisible");
        document.getElementById("agregar").setAttribute("disabled", "");
      }
    }
  };
}

function buscarCodigoVenta(e) {
  e.preventDefault();
  const cod = document.getElementById("codigo").value;
  const url = base_url + "Compras/buscarCodigoConStock/" + cod;
  const http = new XMLHttpRequest();
  http.open("GET", url, true);
  http.send();
  http.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      const res = JSON.parse(this.responseText);
      if (res.msg == "ok") {
        if(res.data){
          document.getElementById("id").value = res.data.id;
          document.getElementById("nombre").value = res.data.descripcion;
          document.getElementById("stock").value = res.data.cantidad;
          if( parseInt(res.data.cantidad) == 0){
            document.getElementById("cantidad").value = 0;
          }else{
           
            let cantidad_temporal;
            if(res.cantidad_temp != null){
              cantidad_temporal = parseInt(res.cantidad_temp);
            }else{
              cantidad_temporal = 0;
            }
            if(cantidad_temporal >=  parseInt(res.data.cantidad)){
                        
              document.getElementById("cantidad").value = 0;
              document.getElementById("cantidad").setAttribute("disabled","");
              document.getElementById("agregar").classList.add("invisible");
              document.getElementById("agregar").setAttribute("disabled","");
              document.getElementById("adicional").setAttribute("disabled","");
              document.getElementById("colAdicional").classList.add("invisible");
            }else{
              document.getElementById("cantidad").value = 1;
              document.getElementById("cantidad").removeAttribute("disabled");
              document.getElementById("agregar").classList.remove("invisible");
              document.getElementById("agregar").removeAttribute("disabled");
              if(parseFloat(res.data.adicional) > 0){
                  
              document.getElementById("colAdicional").classList.remove("invisible");
              document.getElementById("adicional").removeAttribute("disabled");
              }else{
                document.getElementById("colAdicional").classList.add("invisible");
                document.getElementById("adicional").setAttribute("disabled", "");
              }
            }
          }
          document.getElementById("precio").value = res.data.precio_venta;
          if(res.data.id_medida != 2){
          document.getElementById("precio_total").value = (
            res.data.precio_venta * document.getElementById("cantidad").value
          ).toFixed(2);
          }else{
            document.getElementById("precio_total").value = (
              (res.data.precio_venta * document.getElementById("cantidad").value)/1000
            ).toFixed(2);
          }
        }else{
            
        document.getElementById("id").value = "";
        document.getElementById("nombre").value = "";
        document.getElementById("stock").value = "";
        document.getElementById("cantidad").value = "";
        document.getElementById("cantidad").setAttribute("disabled", "");
        document.getElementById("precio").value = "";
        document.getElementById("precio_total").value = "";
        document.getElementById("agregar").classList.add("invisible");
        document.getElementById("agregar").setAttribute("disabled", "");
        document.getElementById("colAdicional").classList.add("invisible");
        document.getElementById("adicional").setAttribute("disabled", "");
        }

       
      } else {
        document.getElementById("id").value = "";
        document.getElementById("nombre").value = "";
        document.getElementById("stock").value = "";
        document.getElementById("cantidad").value = "";
        document.getElementById("cantidad").setAttribute("disabled", "");
        document.getElementById("precio").value = "";
        document.getElementById("precio_total").value = "";
        document.getElementById("agregar").classList.add("invisible");
        document.getElementById("agregar").setAttribute("disabled", "");
        document.getElementById("colAdicional").classList.add("invisible");
        document.getElementById("adicional").setAttribute("disabled", "");
      }
    }
  };
}

function agregarCodigoProducto(e, codigo) {
  e.preventDefault();
  const url = base_url + "Compras/buscarCodigo/" + codigo;
  const http = new XMLHttpRequest();
  http.open("GET", url, true);
  http.send();
  http.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      const res = JSON.parse(this.responseText);
      if (res) {
        Swal.fire({
          icon: "success",
          title: "Producto agregado con éxito",
          timer: 1700,
          showConfirmButton: false,
        });
        document.getElementById("id").value = res.id;
        document.getElementById("codigo").value = res.codigo;
        document.getElementById("nombre").value = res.descripcion;
        document.getElementById("stock").value = res.cantidad;
        document.getElementById("medida").value = res.id_medida;
    
          document.getElementById("cantidad").value = 1;
          document.getElementById("cantidad").removeAttribute("disabled");
          document.getElementById("agregar").classList.remove("invisible");
          document.getElementById("agregar").removeAttribute("disabled");
      
        document.getElementById("precio").value = res.precio_compra;
        if(res.id_medida != 2){
          document.getElementById("precio_total").value = (
            res.precio_compra * document.getElementById("cantidad").value
          ).toFixed(2);
          }else{
            document.getElementById("precio_total").value = (
              (res.precio_compra * document.getElementById("cantidad").value)/1000
            ).toFixed(2);
          }
      } else {
        document.getElementById("id").value = "";
        document.getElementById("codigo").value = "";
        document.getElementById("nombre").value = "";
        document.getElementById("stock").value = "";
        document.getElementById("cantidad").value = "";
        document.getElementById("medida").value = "";
        document.getElementById("cantidad").setAttribute("disabled", "");
        document.getElementById("precio").value = "";
        document.getElementById("precio_total").value = "";
        document.getElementById("agregar").classList.add("invisible");
        document.getElementById("agregar").setAttribute("disabled", "");
      }
    }
  };
}


/*function validacionStockAgregar(id_producto){
  const urlo = base_url + "Compras/validacionStock/"+ parseInt(id_producto);
  const httpp = new XMLHttpRequest();
  httpp.open("GET", urlo, true);
  httpp.send();
  httpp.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      const reso = JSON.parse(this.responseText);
      if (reso.msg == "ok") {
        let cantidad_temporal;
        if(reso.cantidad_temp != null){
          cantidad_temporal = parseInt(reso.cantidad_temp);
        }else{
          cantidad_temporal = 0;
        }
        if(cantidad_temporal >=  parseInt(reso.cantidad)){
                    
          document.getElementById("cantidad").value = 0;
          document.getElementById("cantidad").setAttribute("disabled","");
          document.getElementById("agregar").classList.add("invisible");
          document.getElementById("agregar").setAttribute("disabled","");
        }else{
          document.getElementById("cantidad").value = 1;
          document.getElementById("cantidad").removeAttribute("disabled");
          document.getElementById("agregar").classList.remove("invisible");
          document.getElementById("agregar").removeAttribute("disabled");
        }

      }
    }
  };

}*/

function agregarCodigoProductoVenta(e, codigo) {
  e.preventDefault();
  const url = base_url + "Compras/buscarCodigoConStock/"+codigo;
  const http = new XMLHttpRequest();
  http.open("GET", url, true);
  http.send();
  http.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      const res = JSON.parse(this.responseText);
      if (res) {
        Swal.fire({
          icon: "success",
          title: "Producto agregado con éxito",
          timer: 1700,
          showConfirmButton: false,
        });
        document.getElementById("id").value = res.data.id;
        document.getElementById("codigo").value = res.data.codigo;
        document.getElementById("nombre").value = res.data.descripcion;
        document.getElementById("stock").value = res.data.cantidad;
          if( parseInt(res.data.cantidad) == 0){
            document.getElementById("cantidad").value = 0;
            document.getElementById("cantidad").setAttribute("disabled", "");
          }else{
            let cantidad_temporal;
            if(res.cantidad_temp != null){
              cantidad_temporal = parseInt(res.cantidad_temp);
            }else{
              cantidad_temporal = 0;
            }
            if(cantidad_temporal >=  parseInt(res.data.cantidad)){
                        
              document.getElementById("cantidad").value = 0;
              document.getElementById("cantidad").setAttribute("disabled","");
              document.getElementById("agregar").classList.add("invisible");
              document.getElementById("agregar").setAttribute("disabled","");
              document.getElementById("adicional").setAttribute("disabled","");
              document.getElementById("adicional").classList.add("invisible");
            }else{
              document.getElementById("cantidad").value = 1;
              document.getElementById("cantidad").removeAttribute("disabled");
              document.getElementById("agregar").classList.remove("invisible");
              document.getElementById("agregar").removeAttribute("disabled");
              if(parseFloat(res.data.adicional) > 0){
                  
                document.getElementById("colAdicional").classList.remove("invisible");
                document.getElementById("adicional").removeAttribute("disabled");
                }else{
                  document.getElementById("colAdicional").classList.add("invisible");
                  document.getElementById("adicional").setAttribute("disabled", "");
                }
            }
          }
      
        document.getElementById("precio").value = res.data.precio_venta;
        if(res.data.id_medida != 2){
          document.getElementById("precio_total").value = (
            res.data.precio_venta * document.getElementById("cantidad").value
          ).toFixed(2);
          }else{
            document.getElementById("precio_total").value = (
              (res.data.precio_venta * document.getElementById("cantidad").value)/1000
            ).toFixed(2);
          }
      } else {
        document.getElementById("id").value = "";
        document.getElementById("codigo").value = "";
        document.getElementById("nombre").value = "";
        document.getElementById("stock").value = "";
        document.getElementById("cantidad").value = "";
        document.getElementById("cantidad").setAttribute("disabled", "");
        document.getElementById("precio").value = "";
        document.getElementById("precio_total").value = "";
        document.getElementById("agregar").classList.add("invisible");
        document.getElementById("agregar").setAttribute("disabled", "");
        document.getElementById("colAdicional").classList.add("invisible");
        document.getElementById("adicional").setAttribute("disabled", "");
      }
    }
  };
}


function agregarCodigoCliente(e, id) {
  e.preventDefault();
  const url = base_url + "Compras/buscarIdCliente/" + id;
  const http = new XMLHttpRequest();
  http.open("GET", url, true);
  http.send();
  http.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      const res = JSON.parse(this.responseText);
      if (res) {
        Swal.fire({
          icon: "success",
          title: "Cliente seleccionado con éxito",
          timer: 1700,
          showConfirmButton: false,
        });
        document.getElementById("idcliente").value = res.id;
        document.getElementById("dni").value = res.dni;
        document.getElementById("nombrecliente").value = res.nombre;
        document.getElementById("telefono").value = res.telefono;
          document.getElementById("direccion").value = res.direccion;
          document.getElementById("removerCliente").classList.remove("invisible");
          document.getElementById("removerCliente").removeAttribute("disabled");
      } else {
        
        document.getElementById("idcliente").value = "";
        document.getElementById("dni").value = "";
        document.getElementById("nombrecliente").value = "";
        document.getElementById("telefono").value = "";
          document.getElementById("direccion").value = "";
          document.getElementById("removerCliente").classList.add("invisible");
          document.getElementById("removerCliente").setAttribute("disabled", "");
      }
    }
  };
}

function removerCliente(e){
  Swal.fire({
    icon: "success",
    title: "Cliente removido con éxito",
    timer: 1700,
    showConfirmButton: false,
  });
  document.getElementById("idcliente").value = "";
  document.getElementById("dni").value = "";
  document.getElementById("nombrecliente").value = "";
  document.getElementById("telefono").value = "";
    document.getElementById("direccion").value = "";
    document.getElementById("removerCliente").classList.add("invisible");
    document.getElementById("removerCliente").setAttribute("disabled", "");
}

function calcularPrecio(e) {
  e.preventDefault();
  let cant = parseInt(document.getElementById("cantidad").value);
  let subtotal = document.getElementById("precio_total");
  if (
    cant < 1 ||
    isNaN(cant) 
  ) {
    subtotal.value = "";
    document.getElementById("agregar").classList.add("invisible");
    document.getElementById("agregar").setAttribute("disabled", "");
  } else {

    
    if(document.getElementById("medida").value != 2){
    subtotal.value = (cant * document.getElementById("precio").value).toFixed(
      2
    );
    }else{
      subtotal.value = ((cant * document.getElementById("precio").value)/1000).toFixed(
        2
      );
    }

    document.getElementById("agregar").classList.remove("invisible");
    document.getElementById("agregar").removeAttribute("disabled");
  }
}


function calcularPrecioVenta(e) {
  e.preventDefault();
  let cant = parseInt(document.getElementById("cantidad").value);
  const stock = parseInt(document.getElementById("stock").value);
  let subtotal = document.getElementById("precio_total");
  if ( (
    cant < 1 ||
    isNaN(cant)  ) 
  ) {
    subtotal.value = "";
    document.getElementById("agregar").classList.add("invisible");
    document.getElementById("agregar").setAttribute("disabled", "");
  } else {
    const id =     document.getElementById("id");
    const url = base_url + "Compras/validacionStock/"+ id.value ;
    const http = new XMLHttpRequest();
    http.open("GET", url, true);
    http.send();
    http.onreadystatechange = function () {
      if (this.readyState == 4 && this.status == 200) {
        const res = JSON.parse(this.responseText);
        if (res.msg == "ok") {
          let cantidad_temporal;
          if(res.cantidad_temp != null){
            cantidad_temporal = parseInt(res.cantidad_temp);
          }else{
            cantidad_temporal = 0;
          }
          if(cant +  cantidad_temporal >  parseInt(res.cantidad)){
            subtotal.value = "";
            document.getElementById("agregar").classList.add("invisible");
            document.getElementById("agregar").setAttribute("disabled", "");
          }else{
            if(res.medida != 2){
              subtotal.value = (cant * document.getElementById("precio").value).toFixed(2);
            }else{
              subtotal.value = ((cant * document.getElementById("precio").value)/1000).toFixed(2);
            }
     
         document.getElementById("agregar").classList.remove("invisible");
        document.getElementById("agregar").removeAttribute("disabled");

          }

        }
      }
    };
  }
}

function listarBuscarProducto() {
  if (document.getElementById("tblProductosModal")) {
    tblProductosModal = $("#tblProductosModal").DataTable({
      destroy: true,
      responsive: true,

      language: {
        url: base_url + "Assets/json/español.json",
      },
      ajax: {
        url: base_url + "Compras/listarProductos",
        dataSrc: "",
      },
      columns: [
        {
          data: "id",
        },
        {
          data: "codigo",
        },
        {
          data: "descripcion",
        },
        {
          data: "cantidad",
        },
        {
          data: "precio_compra",
        },
        {
          data: "acciones",
        },
      ],
    });
  }
}

function listarBuscarProductoVenta() {
  if (document.getElementById("tblProductosModal")) {
    tblProductosModal = $("#tblProductosModal").DataTable({
      destroy: true,
      responsive: true,

      language: {
        url: base_url + "Assets/json/español.json",
      },
      ajax: {
        url: base_url + "Compras/listarProductosVenta",
        dataSrc: "",
      },
      columns: [
        {
          data: "id",
        },
        {
          data: "codigo",
        },
        {
          data: "descripcion",
        },
        {
          data: "cantidad",
        },
        {
          data: "precio_venta",
        },
        {
          data: "acciones",
        },
      ],
    });
  }
}

function listarBuscarCliente() {
  if (document.getElementById("tblClientesModal")) {
    tblClientesModal = $("#tblClientesModal").DataTable({
      destroy: true,
      responsive: true,

      language: {
        url: base_url + "Assets/json/español.json",
      },
      ajax: {
        url: base_url + "Compras/listarClientes",
        dataSrc: "",
      },
      columns: [
        {
          data: "id",
        },
        {
          data: "dni",
        },
        {
          data: "nombre",
        },
        {
          data: "telefono",
        },
        {
          data: "direccion",
        },
        {
          data: "acciones",
        },
      ],
    });
  }
}

function agregarProductoDetalleTemp(e) {
  e.preventDefault();
  const url = base_url + "Compras/ingresar";
  const frm = document.getElementById("frmCompra");
  /* const frm2 = document.getElementById("frmCliente");
 
  let arreglo = [];
  const formData = Object.fromEntries(new FormData(frm));
  console.log(formData);
  const formData2 =  Object.fromEntries(new FormData(frm2));
  console.log(formData2);
 arreglo.push(formData);
 arreglo.push(formData2);
 console.log(arreglo);*/
  const http = new XMLHttpRequest();
  http.open("POST", url, true);
  //http.send(JSON.stringify(arreglo));
  http.send(new FormData(frm));
  http.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
       /*    console.log(JSON.parse(this.responseText));
    //  console.log(JSON.parse(this.responseText));
     // const reas = JSON.parse(this.responseText);
    // console.log(this.responseText);
      return false;
*/   
      const res = JSON.parse(this.responseText);
      
      if (res == "ok") {
        frm.reset();
        document.getElementById("id").removeAttribute("value");
        document.getElementById("cantidad").setAttribute("disabled", "");
        document.getElementById("agregar").classList.add("invisible");
        document.getElementById("agregar").setAttribute("disabled", "");
        
        Swal.fire({
          icon: "success",
          title: "Producto agregado con éxito",
          timer: 1700,
          showConfirmButton: false,
        });
        cargarDetalle();
      }
    }
  };
}

function agregarProductoDetalleTempVenta(e) {
  e.preventDefault();
  const url = base_url + "Compras/ingresarVenta";
  const frm = document.getElementById("frmCompra");
  /* const frm2 = document.getElementById("frmCliente");
  
  let arreglo = [];
  const formData = Object.fromEntries(new FormData(frm));
  console.log(formData);
  const formData2 =  Object.fromEntries(new FormData(frm2));
  console.log(formData2);
 arreglo.push(formData);
 arreglo.push(formData2);
 console.log(arreglo);*/
  const http = new XMLHttpRequest();
  http.open("POST", url, true);
  //http.send(JSON.stringify(arreglo));
  http.send(new FormData(frm));
  http.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
    
  /*    console.log(JSON.parse(this.responseText));
    //  console.log(JSON.parse(this.responseText));
     // const reas = JSON.parse(this.responseText);
    // console.log(this.responseText);
      return false;
*/
      const res = JSON.parse(this.responseText);
      
      if (res == "ok") {
        frm.reset();
        document.getElementById("id").removeAttribute("value");
        document.getElementById("cantidad").setAttribute("disabled", "");
        document.getElementById("agregar").classList.add("invisible");
        document.getElementById("agregar").setAttribute("disabled", "");
        document.getElementById("colAdicional").classList.add("invisible");
        document.getElementById("adicional").setAttribute("disabled", "");
        Swal.fire({
          icon: "success",
          title: "Producto agregado con éxito",
          timer: 1700,
          showConfirmButton: false,
        });
        cargarDetalleVenta();
      }
    }
  };
}

function cargarDetalle() {

  if (document.getElementById("tblDetalleTemp")) {

  const url = base_url + "Compras/listar";
  const http = new XMLHttpRequest();
  http.open("GET", url, true);
  http.send();
  http.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      const res = JSON.parse(this.responseText);
      let html = "";
      if (res != "") {

        if(res.detalle.length > 0){
          res.detalle.forEach((row) => {
            html += `<tr>
              <td>${row["id_producto"]}</td>
              <td>${row["descripcion"]}</td>
              <td>${row["cantidad"]}</td>
              <td>${row["precio"]}</td>
              <td>${row["sub_total"]}</td>
              <td><button class="btn btn-danger" type="button" onclick="deleteDetalle(${row["id"]})"><i class="fas fa-trash-alt"></i></button></td>
            </tr>`;
          });
  
         
            document.getElementById("tblDetalleTemp").innerHTML = html;
            //console.log(res.total_pagar.total);
            document.getElementById("total").value = "S/. "+res.total_pagar.total;
            document.getElementById("generarCompra").removeAttribute("disabled");
            document.getElementById("generarCompra").classList.remove("invisible");
          
        }else{
          document.getElementById("tblDetalleTemp").innerHTML = "";
            document.getElementById("total").value = "";
            document.getElementById("generarCompra").setAttribute("disabled","");
            document.getElementById("generarCompra").classList.add("invisible");
        }
       
      } 
        

      

     
    }
  };
}
}

function cargarDetalleVenta() {

  if (document.getElementById("tblDetalleTempVenta")) {

  const url = base_url + "Compras/listarVenta";
  const http = new XMLHttpRequest();
  http.open("GET", url, true);
  http.send();
  http.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      const res = JSON.parse(this.responseText);
      let html = "";
      if (res != "") {

        if(res.detalle.length > 0){
          res.detalle.forEach((row) => {
            html += `<tr>
              <td>${row["id_producto"]}</td>
              <td>${row["descripcion_detalle"]}</td>
              <td>${row["cantidad"]}</td>
              <td>${row["precio"]}</td>
              <td>${row["sub_total"]}</td>
              <td><button class="btn btn-danger" type="button" onclick="deleteDetalleVenta(${row["id"]})"><i class="fas fa-trash-alt"></i></button></td>
            </tr>`;
          });
  
         
            document.getElementById("tblDetalleTempVenta").innerHTML = html;
            document.getElementById("total").value = "S/. "+res.total_pagar.total;
            document.getElementById("generarVenta").removeAttribute("disabled");
            document.getElementById("generarVenta").classList.remove("invisible");
            document.getElementById("anularVenta").removeAttribute("disabled","");
            document.getElementById("anularVenta").classList.remove("invisible");
          
        }else{
          document.getElementById("tblDetalleTempVenta").innerHTML = "";
            document.getElementById("total").value = "";
            document.getElementById("generarVenta").setAttribute("disabled","");
            document.getElementById("generarVenta").classList.add("invisible");
            document.getElementById("anularVenta").setAttribute("disabled","");
            document.getElementById("anularVenta").classList.add("invisible");
        }
       
      } 
        

      

     
    }
  };
}
}

function deleteDetalle(id){
  const url = base_url + "Compras/deleteDetalle/"+id;
  const http = new XMLHttpRequest();
  http.open("GET", url, true);
  http.send();
  http.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
            const res = JSON.parse(this.responseText);
            if(res == "ok"){
              Swal.fire({
                icon: "success",
                title: "Producto eliminado con éxito",
                timer: 1700,
                showConfirmButton: false,
              });
              cargarDetalle();
            }else{
              Swal.fire({
                icon: "error",
                title: "Error",
                timer: 1700,
                showConfirmButton: false,
              });
            }

    }
  };
}

function deleteDetalleVenta(id){
  const url = base_url + "Compras/deleteDetalleVenta/"+id;
  const http = new XMLHttpRequest();
  http.open("GET", url, true);
  http.send();
  http.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
            const res = JSON.parse(this.responseText);
            if(res == "ok"){
              Swal.fire({
                icon: "success",
                title: "Producto eliminado con éxito",
                timer: 1700,
                showConfirmButton: false,
              });
              cargarDetalleVenta();
            }else{
              Swal.fire({
                icon: "error",
                title: "Error",
                timer: 1700,
                showConfirmButton: false,
              });
            }

    }
  };
}

function generarCompra(e){
  e.preventDefault();
  Swal.fire({
    title: "¿Está seguro de efectuar la compra?",
    icon: "warning",
    showCancelButton: true,
    cancelButtonText: "No",
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Sí",
  }).then((result) => {
    if (result.isConfirmed) {
      const url = base_url + "Compras/registrarCompra";
      const http = new XMLHttpRequest();
      http.open("GET", url, true);
      http.send();
      http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
          const res = JSON.parse(this.responseText);
          if (res.msg == "ok") {
            Swal.fire({
              icon: "success",
              timer: 2500,
              title: "Compra generada exitosamente",
              showConfirmButton: false
            });
            const ruta = base_url + 'Compras/generarPdf/'+res.id_compra;
            window.open(ruta);
            setTimeout(()=>{
              window.location.reload();
            },300);
            
          } else {
            Swal.fire({
              icon: "error",
              title: res.msg,
            });
          }
        }
      };
    }
  });
}

function generarVenta(e){
  e.preventDefault();
  let idcliente = document.getElementById("idcliente");
  if(idcliente.value == ""){
      idcliente.value = 1;
  }
  Swal.fire({
    title: "¿Está seguro de efectuar la venta?",
    icon: "warning",
    showCancelButton: true,
    cancelButtonText: "No",
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Sí",
  }).then((result) => {
    if (result.isConfirmed) {
      const url = base_url + "Compras/registrarVenta/"+ idcliente.value ;
      const http = new XMLHttpRequest();
      http.open("GET", url, true);
      http.send();
      http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
          const res = JSON.parse(this.responseText);
          if (res.msg == "ok") {
            Swal.fire({
              icon: "success",
              timer: 2500,
              title: "Venta generada exitosamente",
              showConfirmButton: false
            });
            const ruta = base_url + 'Compras/generarPdfVenta/'+res.id_venta;
            window.open(ruta);
            setTimeout(()=>{
              window.location.reload();
            },300);
            
          }else if(res.msg = "productoerror"){
                  
            Swal.fire({
              icon: "error",
              title: "Error en el producto "+res.data.descripcion,
              text: "La cantidad de "+res.cantidad_temp+" sobrepasa al stock actual de "+res.data.cantidad
            });
          } else {
            Swal.fire({
              icon: "error",
              title: res.msg,
            });
          }
        }
      };
    }
  });
}


function anularVenta(e){
  e.preventDefault();
  Swal.fire({
    title: "¿Está seguro de anular la venta?",
    icon: "warning",
    showCancelButton: true,
    cancelButtonText: "No",
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Sí",
  }).then((result) => {
    if (result.isConfirmed) {
      const url = base_url + "Compras/anularVenta";
      const http = new XMLHttpRequest();
      http.open("GET", url, true);
      http.send();
      http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
          const res = JSON.parse(this.responseText);
          if (res.msg == "ok") {
            Swal.fire({
              icon: "success",
              timer: 2500,
              title: "Venta anulada exitosamente",
              showConfirmButton: false
            });
            
  document.getElementById("idcliente").value = "";
  document.getElementById("dni").value = "";
  document.getElementById("nombrecliente").value = "";
  document.getElementById("telefono").value = "";
    document.getElementById("direccion").value = "";
    document.getElementById("removerCliente").classList.add("invisible");
    document.getElementById("removerCliente").setAttribute("disabled", "");
            cargarDetalleVenta();
            
          } else {
            Swal.fire({
              icon: "error",
              title: res.msg,
            });
          }
        }
      };
    }
  });
}


function listarHistorialCompras() {
  if (document.getElementById("tblHistorialCompra")) {
    tblMedidas = $("#tblHistorialCompra").DataTable({
      destroy: true,
      responsive: true,

      language: {
        url: base_url + "Assets/json/español.json",
      },
      ajax: {
        url: base_url + "Compras/listarHistorial",
        dataSrc: "",
      },
      columns: [
        {
          data: "id",
        },
        {
          data: "fecha",
        },
        {
          data: "nombre",
        },
        {
          data: "total",
        },
        {
          data: "estado",
        },
        {
          data: "acciones",
        },
      ],
    });
  }
}

function listarHistorialVentas() {
  if (document.getElementById("tblHistorialVenta")) {
    tblHistorialVenta = $("#tblHistorialVenta").DataTable({
      destroy: true,
      responsive: true,

      language: {
        url: base_url + "Assets/json/español.json",
      },
      ajax: {
        url: base_url + "Compras/listarHistorialVentas",
        dataSrc: "",
      },
      
      order: [[0, 'desc']],
      columns: [
        {
          data: "id",
        },
        {
          data: "fecha",
        },
        {
          data: "usuario",
        },
        {
          data: "cliente",
        },
        {
          data: "total",
        },
        {
          data: "estado",
        },
        {
          data: "acciones",
        },
      ],
    });
  }
}

function AnularVentaId(id){
  Swal.fire({
    title: "¿Está seguro de anular la venta con el ID "+id,
    icon: "warning",
    showCancelButton: true,
    cancelButtonText: "No",
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Sí",
  }).then((result) => {
    if (result.isConfirmed) {
      const url = base_url + "Compras/anularVentaEstado/"+id;
      const http = new XMLHttpRequest();
      http.open("GET", url, true);
      http.send();
      http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
          const res = JSON.parse(this.responseText);
          if (res == "ok") {
            Swal.fire({
              icon: "success",
              timer: 2500,
              title: "Venta anulada exitosamente",
              showConfirmButton: false
            });
            tblHistorialVenta.ajax.reload(null, false);
          } else {
            Swal.fire({
              icon: "error",
              title: res,
            });
          }
        }
      };
    }
  });
}

function pagadoVenta(id) {
  Swal.fire({
    title: "¿Está seguro de actualizar la venta con ID "+id+" a pagado?",
    icon: "warning",
    showCancelButton: true,
    cancelButtonText: "No",
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Sí",
  }).then((result) => {
    if (result.isConfirmed) {
      const url = base_url + "Compras/pagadoVenta/" + id;
      const http = new XMLHttpRequest();
      http.open("GET", url, true);
      http.send();
      http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
          const res = JSON.parse(this.responseText);
          if (res == "ok") {
            Swal.fire({
              icon: "success",
              timer: 2500,
              title: "Cambiando estado",
              text: "El estado se cambió a pagado exitosamente",
            });
            tblHistorialVenta.ajax.reload(null, false);
          } else {
            Swal.fire({
              icon: "error",
              title: res,
            });
          }
        }
      };
    }
  });
}


function pendienteVenta(id) {
  Swal.fire({
    title: "¿Está seguro de actualizar la venta con ID "+id+" a pendiente?",
    icon: "warning",
    showCancelButton: true,
    cancelButtonText: "No",
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Sí",
  }).then((result) => {
    if (result.isConfirmed) {
      const url = base_url + "Compras/pendienteVenta/" + id;
      const http = new XMLHttpRequest();
      http.open("GET", url, true);
      http.send();
      http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
          const res = JSON.parse(this.responseText);
          if (res == "ok") {
            Swal.fire({
              icon: "success",
              timer: 2500,
              title: "Cambiando estado",
              text: "El estado se cambió a pendiente exitosamente",
            });
            tblHistorialVenta.ajax.reload(null, false);
          } else {
            Swal.fire({
              icon: "error",
              title: res,
            });
          }
        }
      };
    }
  });
}


function modificarEmpresa(){
 const frm = document.getElementById("frmEmpresa");
 const url = base_url + "Administracion/modificar";
 const http = new XMLHttpRequest();
 http.open("POST", url, true);
 http.send(new FormData(frm));
 http.onreadystatechange = function () {
   if (this.readyState == 4 && this.status == 200) {
    
      const res = JSON.parse(this.responseText);
     if (res == "ok") {
       Swal.fire({
         icon: "success",
         timer: 2500,
         title: "Configuración actualizado exitosamente",
         showConfirmButton: false
       });
       
     } else {
       Swal.fire({
         icon: "error",
         title: res,
       });
     }
   }
 };
}
