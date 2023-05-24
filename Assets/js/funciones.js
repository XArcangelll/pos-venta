let tblUsuarios;
let tblClientes;
let tblCajas;
let tblCategorias;
let tblMedidas;
let tblProductos;
document.addEventListener("DOMContentLoaded", function () {
  listarUsuarios();
  listarClientes();
  listarCajas();
  listarCategorias();
  listarMedidas();
  listarProductos();
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
          data: "precio_compra",
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
}

function registrarProducto(e) {
  e.preventDefault();
  const codigo = document.getElementById("codigo");
  const descripcion = document.getElementById("descripcion");
  const precio_compra = document.getElementById("precio_compra");
  const precio_venta = document.getElementById("precio_venta")
  const cantidad = document.getElementById("cantidad");
  const medida = document.getElementById("medida");
  const categoria = document.getElementById("categoria");
  if (codigo.value == "" || descripcion.value == "" || precio_compra.value == "" || precio_venta.value == "" || cantidad.value == "" || medida.value == "" || categoria.value == "") {
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
  document.getElementById("precio_compra").value = 0.00;
  document.getElementById("precio_venta").value = 0.00;
  document.getElementById("cantidad").value = 0.00;

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
      document.getElementById("cantidad").value = res.cantidad;
      document.getElementById("medida").value = res.id_medida;
      document.getElementById("categoria").value = res.id_categoria;
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
