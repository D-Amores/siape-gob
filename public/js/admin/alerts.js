function showAlert(message, type = "blue", title = "Información", onOk = null, timeout = 0) {
    const jc = $.alert({
        title,
        content: message,
        type,
        theme: 'material',
        autoClose: false,
        buttons: {
            ok: {
                text: "OK",
                btnClass: "btn-primary",
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

function confirmStore(functionToCall) {
    $.confirm({
        title: 'Confirmar acción',
        content: '¿Está seguro de guardar el registro? Esta acción no podrá ser revertida.',
        type: 'blue',
        theme: 'material',
        buttons: {
            Cancelar: function() { },
            Guardar: async function() {
                if (typeof functionToCall === "function") {
                    await functionToCall(); // soporta funciones async
                }
            }
        }
    });
}
