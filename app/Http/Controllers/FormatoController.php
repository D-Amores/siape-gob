<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Classes\clsImprimir;
use App\Models\Asset;
use App\Models\PersonnelAsset;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class FormatoController extends Controller
{
    private $route = 'impresion';

    public function __construct() { }

    public function pdfAsignacion($id)
    {  
        $user = Auth::user();

        // Traer la asignación con sus relaciones
        $personnelAsset = PersonnelAsset::with(['asset', 'asset.brand', 'asset.category', 'assigner', 'receiver'])
            ->find($id);

        if (!$personnelAsset) {
            abort(404, 'Asignación no encontrada.');
        }

        // Verificar que el usuario que aceptó sea el que descarga
        if ($personnelAsset->receiver_id !== $user->personnel_id) {
            abort(403, 'No tienes permisos para generar este PDF.');
        }

        $asset = $personnelAsset->asset;

        // Construir nombres completos
        $assigner_name = optional($personnelAsset->assigner)
            ? trim("{$personnelAsset->assigner->last_name} {$personnelAsset->assigner->middle_name} {$personnelAsset->assigner->name}")
            : 'Desconocido';

        $receiver_name = $personnelAsset->receiver
            ? trim("{$personnelAsset->receiver->last_name} {$personnelAsset->receiver->middle_name} {$personnelAsset->receiver->name}")
            : 'Desconocido';

        $vhtml = '
        <div class="titulo2">Detalle del Activo Asignado</div>
        <div class="subtitulo">Sistema de Inventario y Asignación de Personal y Equipos</div>

        <br>

        <table class="table_dts_dec">
            <tr class="variable"><td colspan="2" class="td_dec titulo_modulos">INFORMACIÓN GENERAL</td></tr>
            <tr><td class="td_dec td_infor">Inventario</td><td class="td_dec">'.htmlspecialchars($asset->inventory_number).'</td></tr>
            <tr><td class="td_dec td_infor">Modelo</td><td class="td_dec">'.htmlspecialchars($asset->model).'</td></tr>
            <tr><td class="td_dec td_infor">Serie</td><td class="td_dec">'.htmlspecialchars($asset->serial_number).'</td></tr>
            <tr><td class="td_dec td_infor">Marca</td><td class="td_dec">'.htmlspecialchars($asset->brand->name ?? "—").'</td></tr>
            <tr><td class="td_dec td_infor">Categoría</td><td class="td_dec">'.htmlspecialchars($asset->category->name ?? "—").'</td></tr>
            <tr><td class="td_dec td_infor">Tipo</td><td class="td_dec">'.htmlspecialchars($asset->type ?? "—").'</td></tr>
            <tr><td class="td_dec td_infor">Fecha de Registro</td><td class="td_dec">'.($asset->created_at ? $asset->created_at->format("d/m/Y") : "Sin fecha").'</td></tr>
        </table>

        <table class="table_dts_dec">
            <tr class="variable"><td colspan="2" class="td_dec titulo_modulos">ESPECIFICACIONES TÉCNICAS</td></tr>
            <tr><td class="td_dec td_infor">CPU</td><td class="td_dec">'.htmlspecialchars($asset->cpu ?? "—").'</td></tr>
            <tr><td class="td_dec td_infor">Velocidad</td><td class="td_dec">'.htmlspecialchars($asset->speed ?? "—").'</td></tr>
            <tr><td class="td_dec td_infor">Memoria</td><td class="td_dec">'.htmlspecialchars($asset->memory ?? "—").'</td></tr>
            <tr><td class="td_dec td_infor">Almacenamiento</td><td class="td_dec">'.htmlspecialchars($asset->storage ?? "—").'</td></tr>
        </table>

        <table class="table_dts_dec">
            <tr class="variable"><td colspan="2" class="td_dec titulo_modulos">DESCRIPCIÓN</td></tr>
            <tr><td colspan="2" class="td_dec">'.nl2br(htmlspecialchars($asset->description ?: "Sin descripción disponible")).'</td></tr>
        </table>

        <table class="table_dts_dec">
            <tr class="variable"><td colspan="2" class="td_dec titulo_modulos">INFORMACIÓN DE LA ASIGNACIÓN</td></tr>
            <tr><td class="td_dec td_infor">Fecha de Asignación</td><td class="td_dec">'.($personnelAsset->assignment_date ? $personnelAsset->assignment_date->format("d/m/Y") : "Sin fecha").'</td></tr>
            <tr><td class="td_dec td_infor">Fecha de Confirmación</td><td class="td_dec">'.($personnelAsset->confirmation_date ? $personnelAsset->confirmation_date->format("d/m/Y") : "Pendiente").'</td></tr>
            <tr><td class="td_dec td_infor">Asignador</td><td class="td_dec">'.htmlspecialchars($assigner_name).'</td></tr>
            <tr><td class="td_dec td_infor">Receptor</td><td class="td_dec">'.htmlspecialchars($receiver_name).'</td></tr>
            <tr><td class="td_dec td_infor">Estado de la Asignación</td><td class="td_dec">'.($personnelAsset->confirmation_date ? "✔ Confirmada" : "Pendiente de confirmación").'</td></tr>
        </table>

        <br><br>

        <div class="firma-container">
            <div class="firma-linea"></div>
            <div class="firma-nombre">'.htmlspecialchars($receiver_name).'</div>
            <div class="firma-cargo">Receptor del Activo</div>
        </div>
        ';

        $nombre_archivo = 'detalle_asignacion_' . $asset->inventory_number;

        $clsImprimir = new clsImprimir();
        
        
        // Generar el PDF y obtener el contenido
        $pdfContent = $clsImprimir->generarPDF($vhtml, 'detalle_asignacion_' . $asset->inventory_number);
        
        // Guardar respaldo del PDF
        $this->guardarRespaldoPDF($pdfContent, $personnelAsset, $asset, $receiver_name);

        return $clsImprimir->descargarPDF($pdfContent, 'detalle_asignacion_' . $asset->inventory_number);
    }

    /**
     * Guarda el PDF en la carpeta de respaldo
     */
    private function guardarRespaldoPDF($pdfContent, $personnelAsset, $asset, $receiverName)
    {
        try {
            // Verificar si ya existe un documento de respaldo y eliminarlo
            if ($personnelAsset->path_respaldo_acceptance && 
                $personnelAsset->path_respaldo_acceptance !== 'No disponible' &&
                $personnelAsset->path_respaldo_acceptance !== 'Firmado') {
                
                if (Storage::disk('public')->exists($personnelAsset->path_respaldo_acceptance)) {
                    Storage::disk('public')->delete($personnelAsset->path_respaldo_acceptance);
                }
            }

            // Generar la estructura de carpetas
            $monthYear = Carbon::now()->locale('es')->translatedFormat('F-Y');
            $monthYear = $this->sanitizeFolderName($monthYear);

            // Sanitizar el nombre de la carpeta del usuario
            $userFolder = $this->sanitizeFolderName($receiverName);
            
            // Ruta base de almacenamiento para respaldos
            $basePath = "RESPALDO/{$monthYear}/{$userFolder}";
            
            // Generar nombre único para el archivo
            $fileName = 'respaldo_asignacion_' . $personnelAsset->id . '_' . $this->sanitizeFileName($asset->inventory_number) . '_' . time() . '.pdf';
            
            // Guardar el archivo
            $filePath = Storage::disk('public')->put($basePath . '/' . $fileName, $pdfContent);

            if ($filePath) {
                // Actualizar la asignación con la ruta del documento de respaldo
                $personnelAsset->update([
                    'path_respaldo_acceptance' => $basePath . '/' . $fileName
                ]);
            }

            return $filePath;

        } catch (\Exception $e) {
            Log::error('Error al guardar respaldo PDF: ' . $e->getMessage());
        }
    }

    /**
     * Sanitiza el nombre de la carpeta reemplazando espacios y caracteres especiales
     */
    private function sanitizeFolderName($name)
    {
        // Reemplazar espacios por guiones bajos
        $name = preg_replace('/\s+/', '_', $name);
        
        // Eliminar caracteres especiales excepto guiones bajos
        $name = preg_replace('/[^a-zA-Z0-9_-]/', '', $name);
        
        // Limitar la longitud
        $name = substr($name, 0, 100);
        
        return $name;
    }

    /**
     * Sanitiza el nombre del archivo
     */
    private function sanitizeFileName($name)
    {
        // Reemplazar espacios por guiones bajos
        $name = preg_replace('/\s+/', '_', $name);
        
        // Eliminar caracteres especiales excepto guiones bajos y puntos
        $name = preg_replace('/[^a-zA-Z0-9._-]/', '', $name);
        
        // Limitar la longitud
        $name = substr($name, 0, 50);
        
        return $name;
    }
}