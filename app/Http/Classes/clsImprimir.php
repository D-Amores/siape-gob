<?php
    namespace App\Http\Classes;

    class clsImprimir
    {
        private $right_logo;
        private $left_logo;

        public function __construct()
        {
            $this->left_logo= public_path()."/css/img/SAyBG.png";
        }
	
        private function encabezado($titulo="")
        {   
            $html = '
                <table width="100%">
                    <tr>
                        <td width="30%" style="text-align: left; vertical-align: middle;">
                            <img src=' . $this->left_logo . ' width="30%" />
                        </td>
                        <td width="70%" style="text-align: right;">
                            Secretaría Anticorupción y Buen Gobierno<br>
                            Coordinación de Verificación de la Supervisión Externa de la Obra Pública Estatal
                        </td>                                       
                    </tr>
                </table>';
        
            return $html;
        }

        private function pie() 
        {
            $html = '       
                <table style="font-size:10px; text-align: right;" width="100%">
                    <tr>
                        <td>
                            Blvd. Los Castillos No. 410, Fracc. Montes Azules C.P. 29056 <br />
                            Conmutador: (961) 61 8 75 30, Teléfono: Quejas y denuncias 800-900-9000 <br />
                            https://anticorrupcionybg.gob.mx <br />
                            Tuxtla Gutiérrez, Chiapas.
                        </td>
                    </tr>
                </table>';
            return $html;
        }

        /**
         * Genera el PDF y retorna el contenido como string
         */
        public function generarPDF($contenido, $nameFile='reporte_rtec')
        {
            $mpdf = new \Mpdf\Mpdf([     
                'tempDir' => public_path()."/pdf/tmp",     
                'margin_left' => 20,
                'margin_right' => 15,
                'margin_top' => 40,
                'margin_bottom' => 30,
                'margin_header' => 10,
                'margin_footer' => 10,
                'format' => 'Letter'
            ]);

            $html = $contenido;

            $stylesheet = file_get_contents(public_path()."/css/print.css");                
            $mpdf->SetProtection(array('print'));
            $mpdf->SetTitle("Acuse Invitación");
            $mpdf->SetAuthor("Secretaría de la Honestidad y Función Pública");
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->SetHTMLHeader($this->encabezado());
            $mpdf->SetHTMLFooter($this->pie());
            $mpdf->WriteHTML($stylesheet, 1);
            $mpdf->WriteHTML($html);

            // Retornar el contenido del PDF como string
            return $mpdf->Output('', 'S');
        }

        /**
         * Descarga el PDF al navegador
         */
        public function descargarPDF($pdfContent, $nameFile = 'reporte_rtec')
        {
            return response($pdfContent)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="' . $nameFile . '.pdf"')
                ->header('Content-Length', strlen($pdfContent));
        }
    }
?>
