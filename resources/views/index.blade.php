<!DOCTYPE html>
<html lang="es">

<head>
    <title>Consulta de DNI</title>
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Estilos CSS personalizados para aumentar el tamaño del texto */
        .text-large {
            font-size: 38px;
            /* Ajusta el tamaño del texto según tus preferencias */
        }

        .container-box {
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 10px;
            background-color: #f9f9f9;
            /* Cambia el color de fondo a gris */
            text-align: center;
            /* Centra horizontalmente */
        }

        .custom-border {
            border: 2px solid black;
            /* Cambia el color del borde según tus preferencias */
        }

        .image-container img {
            width: 45%;
            /* Hace que la imagen ocupe todo el ancho de su contenedor */
        }

        i {
            font-family: FontAwesome;
            font-style: normal;
            color: #FFF;
        }
    </style>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Agrega el meta tag CSRF -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <div class="container mt-4">
        <div class="container-box custom-border mt-4">
            <div class="text-center">
                <div class="image-container">
                    <img src="{{ asset('maclogo.png') }}" alt="Imagen" class="img-fluid m-5">
                </div>
                <form method="POST" action="/consulta-dni">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="text" id="dni" autocomplete="off" name="dni"
                            class="form-control text-large" oninput="validarDNI(this)" inputmode="numeric" required>
                        <div class="input-group-append">
                            <button id="clearDNI" class="btn btn-danger btn-outline-secondary" type="button"><i
                                    class="fas fa-times"></i></button>
                        </div>
                    </div>
                    <button type="button" id="prueba" class="btn btn-primary m-2 p-2 text-large">Consultar</button>
                </form>
            </div>
            <br>
            <br>
            <div>
                <label id="nombreCompleto" class="text-large" style="color: black;"></label>
            </div>
            <div>
                <label id="errorMensaje" class="text-large" style="color: red;"></label>
            </div>
            <div>
                <button id="copiarNombre" class="btn btn-warning m-4 p-2 text-large" style="display: none;">Copiar
                    Nombre
                </button>
                <button id="copiarDNI" class="btn btn-info m-4 p-2 text-large" style="display: none;">Copiar
                    DNI</button>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $("#prueba").click(function() {
                consultarDNI();
            });

            // Resto de tu código JavaScript existente...

            function consultarDNI() {
                var dni = $("#dni").val();

                $.ajax({
                    type: "POST",
                    url: "{{ url('/consulta-dni') }}",
                    data: {
                        'dni': dni
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: 'json',
                    success: function(data) {
                        if (data.error) {
                            $("#nombreCompleto").empty();
                            $("#errorMensaje").html(data.message);
                            $("#copiarNombre").hide();
                            $("#copiarDNI").hide();
                        } else {
                            $("#errorMensaje").empty();
                            $("#nombreCompleto").html(data.nombres + " " + data.apellidoPaterno + " " +
                                data.apellidoMaterno);
                            $("#copiarNombre").show();
                            $("#copiarDNI").show();
                        }
                    },
                    error: function() {
                        $("#nombreCompleto").empty();
                        $("#copiarNombre").hide();
                        $("#copiarDNI").hide();
                        $("#errorMensaje").html('Error en la consulta del DNI');
                    }
                });
            }
        });
    </script>
</body>

</html>
