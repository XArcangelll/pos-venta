let tblUsuarios;
let tblClientes;
document.addEventListener("DOMContentLoaded", function(){

    listarUsuarios();
    listarClientes();
});
function frmLogin(e){
    e.preventDefault();
    const usuario = document.getElementById("usuario");
    const clave = document.getElementById("clave");
    if(usuario.value == ""){
        clave.classList.remove("is-invalid");
        usuario.classList.add("is-invalid");
        usuario.focus();
    }else if(clave.value == ""){
        usuario.classList.remove("is-invalid");
        clave.classList.add("is-invalid");
        clave.focus();
    }else{
        const url = base_url + "Usuarios/validar";
        const frm = document.getElementById("frmLogin");
        const http = new XMLHttpRequest();
        http.open("POST",url,true);
        http.send(new FormData(frm));
        http.onreadystatechange = function(){
            if(this.readyState == 4 && this.status == 200){
                console.log = this.responseText;
                    const res = JSON.parse(this.responseText);
                    if(res == "ok"){
                        window.location = base_url + "Usuarios";
                       /* Swal.fire({
                            icon: "success",
                            title: 'Ingresando al Sistema',
                          }
                          )
                          .then(function(){
                            window.location = base_url + "Usuarios";
                          })*/
                       
                       
                    }else{
                        document.getElementById("alerta").classList.remove("d-none");
                        document.getElementById("alerta").innerHTML = res;
                    }
            }
        };

    }
}

function listarUsuarios(){

    if(document.getElementById("tblUsuarios")){
        tblUsuarios = $('#tblUsuarios').DataTable( {
            destroy:true,
            responsive:true,
           
            language: {
                url: base_url + "Assets/json/español.json",
            },
                ajax: {
                    url: base_url + "Usuarios/listar",
                    dataSrc: ''
                },
                columns: [ {
                    'data' : 'id'},{
                    'data' : 'usuario'},{
                    'data' : 'nombre'},{
                    'data' : 'caja' },{
                        'data' : 'estado' },{
                    'data' : 'acciones'
                } 
                 ]
            } );
    }

   
}

function cerrarFormUser(e){
    e.preventDefault();
    document.getElementById('frmUsuario').reset();
}

function registrarUser(e){
    e.preventDefault();
    const usuario = document.getElementById("usuario");
    const clave = document.getElementById("clave");
    const nombre = document.getElementById("nombre");
    const confirmar = document.getElementById("confirmar");
    const caja = document.getElementById("caja");
    if(usuario.value == ""  || nombre.value == ""  || caja.value == "" ){
      
        Swal.fire(
           {icon : 'error',
            title: 'Todos los Campos son obligatorios',
            timer: 2500,
            showConfirmButton: false
        }
        )

    }else if(clave.value != confirmar.value){
        Swal.fire(
            {icon : 'error',
             title: 'Las Contraseñas no coinciden',
             timer: 2500,
             showConfirmButton: false
         }
         )
    }else{
        const url = base_url + "Usuarios/registrar";
        const frm = document.getElementById("frmUsuario");
        const http = new XMLHttpRequest();
        http.open("POST",url,true);
        http.send(new FormData(frm));
        http.onreadystatechange = function(){
                        if(this.readyState == 4 && this.status == 200){
                            const res = JSON.parse(this.responseText);
                            if(res == "ok"){
                                Swal.fire(
                                    {icon : 'success',
                                     title: 'Usuario registrado con éxito',
                                     timer: 2500,
                                     showConfirmButton: false
                                 }
                                 )
                                 frm.reset();
                                 $("#nuevo_usuario").modal("hide");
                                 tblUsuarios.ajax.reload(null,false);
                                    
                            }else if(res == "modificado"){
                                Swal.fire(
                                    {icon : 'success',
                                     title: 'Usuario modificado con éxito',
                                     timer: 2500,
                                     showConfirmButton: false
                                 }
                                 )
                                 frm.reset();
                                 $("#nuevo_usuario").modal("hide");
                                 tblUsuarios.ajax.reload(null,false);
                            }else{
                                Swal.fire(
                                    {icon : 'error',
                                     title: res,
                                     timer: 2500,
                                     showConfirmButton: false
                                 }
                                 )
                            }
                        }
        };
    

    }
}

function btnNuevoUser(){
    document.getElementById("claves").classList.remove("d-none");
    document.getElementById("my-modal-title").innerHTML = "Nuevo Usuario";
    document.getElementById("btnAccion").innerHTML = "Registrar";
    document.getElementById("id").value = "";
    const frm = document.getElementById("frmUsuario");
    frm.reset();
}

function btnEditarUser(id){
    document.getElementById("my-modal-title").innerHTML = "Actualizar Usuario";
    document.getElementById("btnAccion").innerHTML = "Modificar";
    document.getElementById("claves").classList.add("d-none");
    document.getElementById("usuario").value = "cargando...";                     
    document.getElementById("nombre").value = "cargando...";

    const url = base_url + "Usuarios/editar/"+id;
    const http = new XMLHttpRequest();
    http.open("GET",url,true);
    http.send();
    http.onreadystatechange = function(){
                    if(this.readyState == 4 && this.status == 200){
                            const res = JSON.parse(this.responseText);
                            
                            document.getElementById("id").value = res.id;
                             document.getElementById("usuario").value = res.usuario;                     
                             document.getElementById("nombre").value = res.nombre;
                             document.getElementById("caja").value = res.id_caja;
                            
                    }
    };




}

function btnEliminarUser(id){
    Swal.fire({
        title: '¿Está seguro de eliminar el Usuario con el ID '+id+' ?',
        text: 'El Usuario no se eliminará de forma permanente, solo cambiará a estado inactivo.',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'No',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí'
      }).then((result) => {
        if (result.isConfirmed) {
            const url = base_url + "Usuarios/eliminar/"+id;
            const http = new XMLHttpRequest();
            http.open("GET",url,true);
            http.send();
            http.onreadystatechange = function(){
                            if(this.readyState == 4 && this.status == 200){
                                  const res = JSON.parse(this.responseText);
                                  if(res == "ok"){
                                    Swal.fire({
                                        icon: "success",
                                        timer: 2500,
                                        title: 'Eliminado',
                                        text: 'El Usuario ha sido eliminado exitosamente.'
                                      }
                                      )
                                      tblUsuarios.ajax.reload(null,false);
                                  }else{
                                    Swal.fire({
                                        icon: "error",
                                        title: res,
                                      }
                                      )
                                  }
                                    
                            }
            };    

        }
      })
}

function btnReingresarUser(id){
    Swal.fire({
        title: '¿Está seguro de reingresar el Usuario con el ID '+id+' ?',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'No',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí'
      }).then((result) => {
        if (result.isConfirmed) {
            const url = base_url + "Usuarios/reingresar/"+id;
            const http = new XMLHttpRequest();
            http.open("GET",url,true);
            http.send();
            http.onreadystatechange = function(){
                            if(this.readyState == 4 && this.status == 200){
                                  const res = JSON.parse(this.responseText);
                                  if(res == "ok"){
                                    Swal.fire({
                                        icon: "success",
                                        timer: 2500,
                                        title: 'Reingresado',
                                        text: 'El Usuario ha sido reingresado exitosamente.'
                                      }
                                      )
                                      tblUsuarios.ajax.reload(null,false);
                                  }else{
                                    Swal.fire({
                                        icon: "error",
                                        title: res,
                                      }
                                      )
                                  }
                                    
                            }
            };    

        }
      })
}

//codigo para clientes

function listarClientes(){

    if(document.getElementById("tblClientes")){
        tblClientes = $('#tblClientes').DataTable( {
            destroy:true,
            responsive:true,
           
            language: {
                url: base_url + "Assets/json/español.json",
            },
                ajax: {
                    url: base_url + "Clientes/listar",
                    dataSrc: ''
                },
                columns: [ {
                    'data' : 'id'},{
                    'data' : 'dni'},{
                    'data' : 'nombre'},{
                    'data' : 'telefono' },{
                        'data' : 'direccion' },{
                        'data' : 'estado' },{
                    'data' : 'acciones'
                } 
                 ]
            } );
    }

   
}

function cerrarFormCliente(e){
    e.preventDefault();
    document.getElementById('frmCliente').reset();
}

function registrarCliente(e){
    e.preventDefault();
    const DNI = document.getElementById("dni");
    const telefono = document.getElementById("telefono");
    const nombre = document.getElementById("nombre");
    const direccion = document.getElementById("direccion");
    if(DNI.value == ""  || nombre.value == ""  || telefono.value == "" || direccion.value == "" ){
      
        Swal.fire(
           {icon : 'error',
            title: 'Todos los Campos son obligatorios',
            timer: 2500,
            showConfirmButton: false
        }
        )

    }else{
        const url = base_url + "Clientes/registrar";
        const frm = document.getElementById("frmCliente");
        const http = new XMLHttpRequest();
        http.open("POST",url,true);
        http.send(new FormData(frm));
        http.onreadystatechange = function(){
                        if(this.readyState == 4 && this.status == 200){
                            const res = JSON.parse(this.responseText);
                            if(res == "ok"){
                                Swal.fire(
                                    {icon : 'success',
                                     title: 'Cliente registrado con éxito',
                                     timer: 2500,
                                     showConfirmButton: false
                                 }
                                 )
                                 frm.reset();
                                 $("#nuevo_cliente").modal("hide");
                                 tblClientes.ajax.reload(null,false);
                                    
                            }else if(res == "modificado"){
                                Swal.fire(
                                    {icon : 'success',
                                     title: 'Cliente modificado con éxito',
                                     timer: 2500,
                                     showConfirmButton: false
                                 }
                                 )
                                 frm.reset();
                                 $("#nuevo_cliente").modal("hide");
                                 tblClientes.ajax.reload(null,false);
                            }else{
                                Swal.fire(
                                    {icon : 'error',
                                     title: res,
                                     timer: 2500,
                                     showConfirmButton: false
                                 }
                                 )
                            }
                        }
        };
    

    }
}

function btnNuevoCliente(){
    document.getElementById("my-modal-title").innerHTML = "Nuevo Cliente";
    document.getElementById("btnAccion").innerHTML = "Registrar";
    document.getElementById("id").value = "";
    const frm = document.getElementById("frmCliente");
    frm.reset();
}

function btnEditarCliente(id){
    document.getElementById("my-modal-title").innerHTML = "Actualizar Cliente";
    document.getElementById("btnAccion").innerHTML = "Modificar";                  
    document.getElementById("nombre").value = "cargando...";                   
    document.getElementById("direccion").value = "cargando...";

    const url = base_url + "Clientes/editar/"+id;
    const http = new XMLHttpRequest();
    http.open("GET",url,true);
    http.send();
    http.onreadystatechange = function(){
                    if(this.readyState == 4 && this.status == 200){
                            const res = JSON.parse(this.responseText);
                            
                            document.getElementById("id").value = res.id;
                             document.getElementById("dni").value = res.dni;                     
                             document.getElementById("nombre").value = res.nombre;
                             document.getElementById("direccion").value = res.direccion;
                             document.getElementById("telefono").value = res.telefono;
                            
                    }
    };




}

function btnEliminarCliente(id){
    Swal.fire({
        title: '¿Está seguro de eliminar el Cliente con el ID '+id+' ?',
        text: 'El Cliente no se eliminará de forma permanente, solo cambiará a estado inactivo.',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'No',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí'
      }).then((result) => {
        if (result.isConfirmed) {
            const url = base_url + "Clientes/eliminar/"+id;
            const http = new XMLHttpRequest();
            http.open("GET",url,true);
            http.send();
            http.onreadystatechange = function(){
                            if(this.readyState == 4 && this.status == 200){
                                  const res = JSON.parse(this.responseText);
                                  if(res == "ok"){
                                    Swal.fire({
                                        icon: "success",
                                        timer: 2500,
                                        title: 'Eliminado',
                                        text: 'El Cliente ha sido eliminado exitosamente.'
                                      }
                                      )
                                      tblClientes.ajax.reload(null,false);
                                  }else{
                                    Swal.fire({
                                        icon: "error",
                                        title: res,
                                      }
                                      )
                                  }
                                    
                            }
            };    

        }
      })
}

function btnReingresarCliente(id){
    Swal.fire({
        title: '¿Está seguro de reingresar el Cliente con el ID '+id+' ?',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'No',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí'
      }).then((result) => {
        if (result.isConfirmed) {
            const url = base_url + "Clientes/reingresar/"+id;
            const http = new XMLHttpRequest();
            http.open("GET",url,true);
            http.send();
            http.onreadystatechange = function(){
                            if(this.readyState == 4 && this.status == 200){
                                  const res = JSON.parse(this.responseText);
                                  if(res == "ok"){
                                    Swal.fire({
                                        icon: "success",
                                        timer: 2500,
                                        title: 'Reingresado',
                                        text: 'El Cliente ha sido reingresado exitosamente.'
                                      }
                                      )
                                      tblClientes.ajax.reload(null,false);
                                  }else{
                                    Swal.fire({
                                        icon: "error",
                                        title: res,
                                      }
                                      )
                                  }
                                    
                            }
            };    

        }
      })
}