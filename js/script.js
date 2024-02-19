$(document).ready(function() {
    $('.link-dark').on('click', function(e) {
        e.preventDefault();

        var formName = $(this).data('form');

        $.ajax({
            url: formName + '.php',
            type: 'GET',
            success: function(response) {
                $('#contenido').html(response);
            },
            error: function(error) {
                console.log('Error al cargar el formulario:', error);
            }
        });
    });
});


