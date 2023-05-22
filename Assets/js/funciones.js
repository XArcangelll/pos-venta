let tblUsuarios;
document.addEventListener("DOMContentLoaded", function(){

 listarUsuarios();

});


function listarUsuarios(){
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
                    const res = JSON.parse(this.responseText);
                    if(res == "ok"){
                        window.location = base_url + "Usuarios";
                    }else{
                        document.getElementById("alerta").classList.remove("d-none");
                        document.getElementById("alerta").innerHTML = res;
                    }
            }
        };

    }
}

function cerrarForm(){
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