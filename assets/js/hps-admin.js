jQuery(document).ready(function ($) {
    $('.hps-toggle-module').on('click', function (e) {
        e.preventDefault();
        var button = $(this);
        var moduleName = button.data('module-name');
        var moduleActive = button.data('module-active');
        $.ajax({
            url: hps_ajax_obj.ajax_url,
            type: 'POST',
            data: {
                action: 'toggle_module',
                module_name: moduleName,
                module_active: moduleActive,
                nonce: hps_ajax_obj.nonce
            },
            success: function (response) {
                if (response.success) {
                    if (moduleActive === 1) {
                        button.text('Desactivar');
                        button.data('module-active', 0);
                    } else {
                        button.text('Activar');
                        button.data('module-active', 1);
                    }
                } else {alert(response.data.message);}
            },
            error: function () {alert('Error al intentar cambiar el estado del módulo.');}
        });
    });
});
