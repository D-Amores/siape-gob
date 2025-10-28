<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Classes\clsImprimir;
use App\Models\Asset;
use App\Models\PersonnelAsset;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

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

        $vhtml = '<h2 style="text-align: center;">Detalle del Activo Asignado</h2>';

        // Información general del activo
        $vhtml .= '<h4>Información General</h4>';
        $vhtml .= '<table style="width: 100%; font-size: 12px; border-collapse: collapse;">';
        $vhtml .= '<tr><td>Inventario:</td><td>' . $asset->inventory_number . '</td></tr>';
        $vhtml .= '<tr><td>Modelo:</td><td>' . $asset->model . '</td></tr>';
        $vhtml .= '<tr><td>Serie:</td><td>' . $asset->serial_number . '</td></tr>';
        $vhtml .= '<tr><td>Marca:</td><td>' . $asset->brand->name . '</td></tr>';
        $vhtml .= '<tr><td>Categoría:</td><td>' . $asset->category->name . '</td></tr>';
        $vhtml .= '<tr><td>Tipo:</td><td>' . ($asset->type ?? '—') . '</td></tr>';
        $vhtml .= '<tr><td>Creado:</td><td>' . $asset->created_at->format('d/m/Y') . '</td></tr>';
        $vhtml .= '</table>';

        // Estado del bien
        $vhtml .= '<h4>Estado del Activo</h4>';
        $vhtml .= '<p>' . $asset->status . '</p>';

        // Especificaciones técnicas
        $vhtml .= '<h4>Especificaciones Técnicas</h4>';
        $vhtml .= '<table style="width: 100%; font-size: 12px; border-collapse: collapse;">';
        $vhtml .= '<tr><td>CPU:</td><td>' . ($asset->cpu ?? '—') . '</td></tr>';
        $vhtml .= '<tr><td>Velocidad:</td><td>' . ($asset->speed ?? '—') . '</td></tr>';
        $vhtml .= '<tr><td>Memoria:</td><td>' . ($asset->memory ?? '—') . '</td></tr>';
        $vhtml .= '<tr><td>Almacenamiento:</td><td>' . ($asset->storage ?? '—') . '</td></tr>';
        $vhtml .= '</table>';

        // Descripción
        $descripcion = $asset->description ?: 'Sin descripción disponible';
        $vhtml .= '<h4>Descripción</h4>';
        $vhtml .= '<p>' . $descripcion . '</p>';

        // Información de la asignación
        $vhtml .= '<h4>Información de la Asignación</h4>';
        $vhtml .= '<table style="width: 100%; font-size: 12px; border-collapse: collapse;">';
        $vhtml .= '<tr><td>Fecha de Asignación:</td><td>' . $personnelAsset->assignment_date->format('d/m/Y') . '</td></tr>';
        $vhtml .= '<tr><td>Fecha de Confirmación:</td><td>' . ($personnelAsset->confirmation_date ? $personnelAsset->confirmation_date->format('d/m/Y') : 'Pendiente') . '</td></tr>';
        $vhtml .= '<tr><td>Asignador:</td><td>' . $personnelAsset->assigner->name . '</td></tr>';
        $vhtml .= '<tr><td>Receptor:</td><td>' . $personnelAsset->receiver->name . '</td></tr>';
        $vhtml .= '</table>';

        $vhtml .= '<p>Estado de la asignación: ' . 
            ($personnelAsset->confirmation_date ? 'Confirmada' : 'Pendiente') . 
        '</p>';

        $nombre_archivo = 'detalle_asignacion_' . $asset->inventory_number;

        $clsImprimir = new clsImprimir();
        return $clsImprimir->invitacionPDF($vhtml, 'I', 'detalle_asignacion_' . $asset->inventory_number);
    }
}