document.addEventListener('DOMContentLoaded', function () {
    const toggleButtons = document.querySelectorAll('input[name="toggle_extension"]');
    toggleButtons.forEach(function (button) {
        button.addEventListener('click', function (event) {
            const action = event.target.value;
            const confirmationMessage = action === 'Activar' 
                ? '¿Estás seguro de que deseas activar esta extensión?' 
                : '¿Estás seguro de que deseas desactivar esta extensión?';
            if (!confirm(confirmationMessage)) {event.preventDefault();}
        });
    });
});