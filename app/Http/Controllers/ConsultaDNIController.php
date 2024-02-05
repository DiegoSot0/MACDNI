<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ConsultaDNIController extends Controller
{
    public function consultarDNI(Request $request)
    {
        // Validar el formulario, si es necesario
        $request->validate([
            'dni' => 'required|numeric', // Ajusta las reglas de validación según tus necesidades
        ]);

        // Obtener el DNI del formulario
        $dni = $request->input('dni');

        // Primera búsqueda en la API externa
        $urlApiExterna = 'https://api.apis.net.pe/v1/dni?numero=' . $dni;
        $dataApiExterna = $this->hacerConsultaApi($urlApiExterna);

        // Verificar si la primera búsqueda tiene resultados
        if (isset($dataApiExterna['error'])) {
            // Si hay un error, intentar la segunda búsqueda
            $urlSegundoApi = 'https://macexpress2.pcm.gob.pe/AtencionCiudadano/AtenderCiudadano/listarciudadano?dni=' . $dni;
            $dataSegundoApi = $this->hacerConsultaApi($urlSegundoApi);

            // Verificar si la segunda búsqueda tiene resultados
            if (!empty($dataSegundoApi['DNI'])) {
                // Procesar los datos y enviar la respuesta JSON
                return response()->json([
                    'error' => false,
                    'nombres' => $dataSegundoApi['NOMBRE'],
                    'apellidoPaterno' => $dataSegundoApi['APELLIDO_PATERNO'],
                    'apellidoMaterno' => $dataSegundoApi['APELLIDO_MATERNO'],
                    // Agregar otros datos que desees incluir en la respuesta
                ]);
            }
        }

        // Procesar los datos de la primera búsqueda y enviar la respuesta JSON
        return response()->json([
            'error' => false,
            'nombres' => $dataApiExterna['nombres'],
            'apellidoPaterno' => $dataApiExterna['apellidoPaterno'],
            'apellidoMaterno' => $dataApiExterna['apellidoMaterno'],
            // Agregar otros datos que desees incluir en la respuesta
        ]);
    }

    // Función para hacer la consulta a la API
    private function hacerConsultaApi($url)
    {
        // Iniciar sesión cURL
        $ch = curl_init($url);

        // Configurar opciones de cURL
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Realizar la solicitud y obtener la respuesta
        $response = curl_exec($ch);

        // Cerrar la sesión cURL
        curl_close($ch);

        // Decodificar la respuesta JSON
        return json_decode($response, true);
    }
}
