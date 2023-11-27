<?php

namespace App\Http\Controllers;

use App\Imports\CalificacionesImport;
use App\Models\BoletaParcial1;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
class BoletaParcial1Controller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index2()
    {
        $boletas = BoletaParcial1::all();

        return view('alumnos.index', compact('boletas'));
    }


    public function index(){

        $parcial1 = BoletaParcial1::all();

        return response()->json([

            'message' => 'boletas solicitadas correctamente',
            'data' => $parcial1,


        ]);


    }
    
                                                                                                                                                                                                                                                                                                                                                            

    public function import(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xls,xlsx,csv,json',
            'grupo' => 'required|in:Grupo A,Grupo B,Grupo C,Grupo D,Grupo E,Grupo F,Grupo G', // Ajusta según tus grupos permitidos        
        ]);
    
        $grupo = $request->input('grupo');
    
        // Lógica para determinar el modelo (tabla) según el grupo
        $modelo = null;
    
        switch ($grupo) {
            case 'Grupo A':
                // Asigna el modelo correspondiente al Grupo A
                $modelo = new BoletaParcial1(); // Ejemplo, ajusta según tus necesidades
                break;
            case 'Grupo B':

                return response()->json(['error' => 'No se encontró un modelo correspondiente al grupo'], 422);

                break;
            // Añade más casos según sea necesario
            default:
                // Maneja el caso en que no se haya encontrado un modelo correspondiente
                return response()->json(['error' => 'No se encontró un modelo correspondiente al grupo'], 422);
        }
    
        try {
            // Subir el archivo a la tabla correspondiente al Grupo
            Excel::import(new CalificacionesImport($modelo), $request->file('excel_file'));
    
            return response()->json(['message' => 'Archivo Excel subido con éxito']);
        } catch (\Exception $e) {
            Log::error('Mensaje de error personalizado: ' . $e->getMessage(), ['trace' => $e->getTrace()]);
    
            return response()->json(['error' => 'Error al subir el archivo. Consulta los logs para más detalles.'], 422);
        }
    }

    public function generarPdf()
    {
        // Obtén todas las boletas parciales 1 en lotes de tamaño 10 (puedes ajustar el tamaño según sea necesario)
        BoletaParcial1::chunk(20, function ($boletas) {
            // Verifica si hay boletas antes de continuar
            if ($boletas->isEmpty()) {
                return response()->json(['message' => 'No hay boletas disponibles'], 404);
            }
    
            // Crea el directorio 'boletas' si no existe
            $boletasDirectory = public_path('boletas');
            
            if (!is_dir($boletasDirectory)) {
                mkdir($boletasDirectory, 0755, true);
            }
    
            // Itera sobre cada boleta y genera el PDF
            foreach ($boletas as $boleta) {
                try {
                    $data = ['boleta_parcial1' => $boleta];
                    $pdf = PDF::loadView('boletaparcial1', $data);
    
                    // Guarda el PDF en el directorio 'boletas' con un nombre único
                    $pdfPath = public_path("boletas/{$boleta->matricula}_boleta.pdf");
                    $pdf->save($pdfPath);
    
                    // Almacenar la ruta del archivo en la base de datos
                    $boleta->update(['pdf_path' => "boletas/{$boleta->matricula}_boleta.pdf"]);
    
                    // Liberar memoria después de procesar cada boleta
                    unset($data, $pdf);
    
                } catch (\Exception $e) {
                    // Manejar la excepción (puedes loguear el error, enviar un correo electrónico, etc.)
                    Log::error("Error generando PDF para boleta {$boleta->matricula}: " . $e->getMessage());
                }
            }
        });
    
        return response()->json(['message' => 'Boletas generadas correctamente'], 200);
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(BoletaParcial1 $boletaParcial1)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BoletaParcial1 $boletaParcial1)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BoletaParcial1 $boletaParcial1)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BoletaParcial1 $boletaParcial1)
    {
        //
    }
}
