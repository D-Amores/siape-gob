<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Classes\clsImprimir;
use App\Models\clsDLRtec;
use App\Models\User;

class FormatoController extends Controller
{
    private $route='impresion';
    public function __construct() { } 

    public function reporte_colegio()
    {  
        $vhtml ='';
        $clsImprimir=new clsImprimir;

        $vhtml.='<p style="text-align: center; font-size: 13px; line-height: 150%">';
        $vhtml.='   <b>REPORTE RTECS REGISTRADOR POR COLEGIOS<b>';
        $vhtml.='</p>';

        $vi=1;
        $_MDL_User=User::queryToDB([])->get();
        foreach($_MDL_User as $data) {
            
            if ( $data->id != 1 && $data->id != 2 && $data->id != 18 ) {
                $vhtml.='<p style="text-align: justify; font-size: 13px; line-height: 150%">';
                $vhtml.='   <b>'. $vi .'.- '. $data->name .'</b>';
                $vhtml.='</p>';

                $_MDL_Rtec=clsDLRtec::queryToDB(['id_users'=> $data->id])->get();
                if ( count($_MDL_Rtec) > 0 ) {
                    $vi_tabla=1;
                    // $vhtml.='<table  style="width: 100%; font-size: 10px;">';
                    // $vhtml.='	<thead>';
                    // $vhtml.='	    <tr>';
                    // $vhtml.='	        <th>#</th>';
                    // $vhtml.='	        <th>Folio</th>';
                    // $vhtml.='	        <th>Estatus</th>';
                    // $vhtml.='	        <th>Nombre RTEC</th>';
                    // $vhtml.='	        <th>Teléfono</th>';
                    // $vhtml.='	    </tr>';
                    // $vhtml.='	</thead>';
                    // $vhtml.='	<tbody>';
                    // foreach($_MDL_Rtec as $data_rtec) {
                    //     $vhtml.='    <tr>';
                    //     $vhtml.='        <td>'. ($vi_tabla + 1) .'</td>';
                    //     $vhtml.='        <td>'. $data_rtec->folio .'</td>';
                    //     $vhtml.='        <td>'. $data_rtec->status .'</td>';
                    //     $vhtml.='        <td>'. $data_rtec->nombre .'</td>';
                    //     $vhtml.='        <td>'. $data_rtec->telefono .'</td>';
                    //     $vhtml.='    </tr>';
                    // }
                    // $vhtml.='   </tbody>';
                    // $vhtml.='</table>';

                    $vhtml .= '<table style="width: 100%; font-size: 10px; border-collapse: collapse;">';
                    $vhtml .= '    <thead style="background-color: #f2f2f2;">';
                    $vhtml .= '        <tr>';
                    $vhtml .= '            <th style="border: 1px solid #000;">#</th>';
                    $vhtml .= '            <th style="border: 1px solid #000;">Folio</th>';
                    $vhtml .= '            <th style="border: 1px solid #000;">Estatus</th>';
                    $vhtml .= '            <th style="border: 1px solid #000;">Nombre RTEC</th>';
                    $vhtml .= '            <th style="border: 1px solid #000;">Teléfono</th>';
                    $vhtml .= '        </tr>';
                    $vhtml .= '    </thead>';
                    $vhtml .= '    <tbody>';
                    foreach ($_MDL_Rtec as $data_rtec) {
                        $vhtml .= '        <tr>';
                        $vhtml .= '            <td style="border: 1px solid #000;">' . ($vi_tabla++) . '</td>';
                        $vhtml .= '            <td style="border: 1px solid #000;">' . $data_rtec->folio . '</td>';
                        $vhtml .= '            <td style="border: 1px solid #000;">' . $data_rtec->status . '</td>';
                        $vhtml .= '            <td style="border: 1px solid #000;">' . $data_rtec->nombre . '</td>';
                        $vhtml .= '            <td style="border: 1px solid #000;">' . $data_rtec->telefono . '</td>';
                        $vhtml .= '        </tr>';
                    }
                    $vhtml .= '    </tbody>';
                    $vhtml .='</table>';
                
                }
                else {
                    $vhtml.='<p style="text-align: justify; font-size: 13px; ">';
                    $vhtml.='   Actualmente no hay información registrada.';
                    $vhtml.='</p>';
                }
                $vi++;
            }  
        }

        // $vhtml.='<h3 style="text-align: justify; line-height: 180%">';
        // $vhtml.='   Estimado/a: C. '. $vflInvitado->nombre;
        // $vhtml.='</h3>';

        // $vhtml.='<p style="text-align: justify; font-size: 14px; line-height: 180%">';
        // $vhtml.='   Es un honor para mí invitarles al evento <b>Apertura de Auditorías Colmena en municipios</b>, que se celebrará el <b>';
        // $vhtml.='   10 de Septiembre de 2024</b> a las 10:00 horas en <b>Expo Convenciones Chiapas</>,  ubicado en Blvd. Los Castillos N° 410, Villa Montes Azules';
        // $vhtml.='   (A un costado del Hotel Hilton Garden Inn).';
        // $vhtml.='</p>';

        // $vhtml.='<p style="text-align: justify; font-size: 14px; line-height: 180%">';
        // $vhtml.='   Su presencia será muy valiosa para nosotros, agradeceríamos mucho contar con su participación para enriquecer las discusiones y contribuir';
        // $vhtml.='   al éxito del evento.';
        // $vhtml.='</p>';

        
        
        $nombre_archivo=strtolower('reporte_'. strtr('rtecs_colegios', " ", "_"));

        return $clsImprimir->invitacionPDF($vhtml, 'I', $nombre_archivo);  
    }
}

