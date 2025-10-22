function showAlert(message, type = "blue", title = "Información", onOk = null, timeout = 0) {
    const jc = $.alert({
        title,
        content: message,
        type,
        theme: 'material',
        autoClose: false,
        buttons: {
            ok: {
                text: "Aceptar",
                btnClass: "btn-info",
                action: () => {
                    if (typeof onOk === "function") onOk();
                },
            },
        },
    });

    if (timeout && Number(timeout) > 0) {
        setTimeout(() => {
            try {
                jc.close();
                if (typeof onOk === "function") onOk();
            } catch (_) { }
        }, Number(timeout));
    }

    return jc;
}

function confirmStore(functionToCall, message='¿Está seguro de guardar el registro? Esta acción no podrá ser revertida.') {
    $.confirm({
        title: 'Confirmar acción',
        content: message,
        type: 'blue',
        theme: 'material',
        buttons: {
            Cancelar: { 
                text: 'Cancelar',
                btnClass: 'btn-danger',
                action: function() { }
            },
            Guardar: {
                text: 'Guardar',
                btnClass: 'btn-primary',
                action: async function() {
                    if (typeof functionToCall === "function") {
                        await functionToCall(); // soporta funciones async
                    }
                },
            }
        }
    });
}

function confirmUpdate(functionToCall, message='¿Está seguro de actualizar el registro? Esta acción no podrá ser revertida.') {
    $.confirm({
        title: 'Confirmar acción',
        content: message,
        type: 'blue',
        theme: 'material',
        buttons: {
            Cancelar: {
                text: 'Cancelar',
                btnClass: 'btn-danger',
                action: function() { }
            },
            Actualizar: {
                text: 'Actualizar',
                btnClass: 'btn-primary',
                action: async function() {
                    if (typeof functionToCall === "function") {
                        await functionToCall(); // soporta funciones async
                    }
                },
            }
        }
    });
}

function confirmDestroy(functionToCall, message='¿Está seguro de eliminar el registro? Esta acción no podrá ser revertida.') {
    $.confirm({
        title: 'Confirmar acción',
        content: message,
        type: 'blue',
        theme: 'material',
        buttons: {
            Cancelar: {
                text: 'Cancelar',
                btnClass: 'btn-danger',
                action: function() { }
            },
            Eliminar: {
                text: 'Eliminar',
                btnClass: 'btn-primary',
                action: async function() {
                    if (typeof functionToCall === "function") {
                        await functionToCall(); // soporta funciones async
                    }
                }
            }
        }
    });
}
