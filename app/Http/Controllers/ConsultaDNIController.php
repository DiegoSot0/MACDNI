<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ConsultaDNIController extends Controller
{
    public function consultarDNI(Request $request)
    {
        $dni = $request->input('dni');
        $url = 'https://api.apis.net.pe/v1/dni?numero=' . $dni;

        // Iniciar sesión cURL
        $ch = curl_init($url);

        // Configurar opciones de cURL
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Realizar la solicitud y obtener la respuesta
        $response = curl_exec($ch);

        // Cerrar la sesión cURL
        curl_close($ch);

        // Decodificar la respuesta JSON
        $data = json_decode($response, true);

        // Verificar si hay un error en la respuesta
        if (isset($data['error'])) {
            return response()->json(['error' => true, 'message' => $data['message']]);
        }

        // Procesar los datos y enviar la respuesta JSON
        return response()->json([
            'error' => false,
            'nombres' => $data['nombres'],
            'apellidoPaterno' => $data['apellidoPaterno'],
            'apellidoMaterno' => $data['apellidoMaterno'],
            // Agregar otros datos que desees incluir en la respuesta
        ]);
    }
}
