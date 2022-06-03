<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once 'conecta.php';
require_once('../TCPDF/tcpdf.php');

<<<<<<< HEAD
=======

>>>>>>> d370eb6ffcd9e653a23efeb1bf77c696475f50ae
//$formatter = new NumeroALetras();
//$formatter->apocope = true;

function numtoletras($xcifra)
{
    $xarray = array(0 => "Cero",
        1 => "UN", "DOS", "TRES", "CUATRO", "CINCO", "SEIS", "SIETE", "OCHO", "NUEVE",
        "DIEZ", "ONCE", "DOCE", "TRECE", "CATORCE", "QUINCE", "DIECISEIS", "DIECISIETE", "DIECIOCHO", "DIECINUEVE",
        "VEINTI", 30 => "TREINTA", 40 => "CUARENTA", 50 => "CINCUENTA", 60 => "SESENTA", 70 => "SETENTA", 80 => "OCHENTA", 90 => "NOVENTA",
        100 => "CIENTO", 200 => "DOSCIENTOS", 300 => "TRESCIENTOS", 400 => "CUATROCIENTOS", 500 => "QUINIENTOS", 600 => "SEISCIENTOS", 700 => "SETECIENTOS", 800 => "OCHOCIENTOS", 900 => "NOVECIENTOS"
    );
//
    $xcifra = trim($xcifra);
    $xlength = strlen($xcifra);
    $xpos_punto = strpos($xcifra, ".");
    $xaux_int = $xcifra;
    $xdecimales = "00";
    if (!($xpos_punto === false)) {
        if ($xpos_punto == 0) {
            $xcifra = "0" . $xcifra;
            $xpos_punto = strpos($xcifra, ".");
        }
        $xaux_int = substr($xcifra, 0, $xpos_punto); // obtengo el entero de la cifra a covertir
        $xdecimales = substr($xcifra . "00", $xpos_punto + 1, 2); // obtengo los valores decimales
    }

    $XAUX = str_pad($xaux_int, 18, " ", STR_PAD_LEFT); // ajusto la longitud de la cifra, para que sea divisible por centenas de miles (grupos de 6)
    $xcadena = "";
    for ($xz = 0; $xz < 3; $xz++) {
        $xaux = substr($XAUX, $xz * 6, 6);
        $xi = 0;
        $xlimite = 6; // inicializo el contador de centenas xi y establezco el límite a 6 dígitos en la parte entera
        $xexit = true; // bandera para controlar el ciclo del While
        while ($xexit) {
            if ($xi == $xlimite) { // si ya llegó al límite máximo de enteros
                break; // termina el ciclo
            }

            $x3digitos = ($xlimite - $xi) * -1; // comienzo con los tres primeros digitos de la cifra, comenzando por la izquierda
            $xaux = substr($xaux, $x3digitos, abs($x3digitos)); // obtengo la centena (los tres dígitos)
            for ($xy = 1; $xy < 4; $xy++) { // ciclo para revisar centenas, decenas y unidades, en ese orden
                switch ($xy) {
                    case 1: // checa las centenas
                        if (substr($xaux, 0, 3) < 100) { // si el grupo de tres dígitos es menor a una centena ( < 99) no hace nada y pasa a revisar las decenas
                            
                        } else {
                            $key = (int) substr($xaux, 0, 3);
                            if (TRUE === array_key_exists($key, $xarray)){  // busco si la centena es número redondo (100, 200, 300, 400, etc..)
                                $xseek = $xarray[$key];
                                $xsub = subfijo($xaux); // devuelve el subfijo correspondiente (Millón, Millones, Mil o nada)
                                if (substr($xaux, 0, 3) == 100)
                                    $xcadena = " " . $xcadena . " CIEN " . $xsub;
                                else
                                    $xcadena = " " . $xcadena . " " . $xseek . " " . $xsub;
                                $xy = 3; // la centena fue redonda, entonces termino el ciclo del for y ya no reviso decenas ni unidades
                            }
                            else { // entra aquí si la centena no fue numero redondo (101, 253, 120, 980, etc.)
                                $key = (int) substr($xaux, 0, 1) * 100;
                                $xseek = $xarray[$key]; // toma el primer caracter de la centena y lo multiplica por cien y lo busca en el arreglo (para que busque 100,200,300, etc)
                                $xcadena = " " . $xcadena . " " . $xseek;
                            } // ENDIF ($xseek)
                        } // ENDIF (substr($xaux, 0, 3) < 100)
                        break;
                    case 2: // checa las decenas (con la misma lógica que las centenas)
                        if (substr($xaux, 1, 2) < 10) {
                            
                        } else {
                            $key = (int) substr($xaux, 1, 2);
                            if (TRUE === array_key_exists($key, $xarray)) {
                                $xseek = $xarray[$key];
                                $xsub = subfijo($xaux);
                                if (substr($xaux, 1, 2) == 20)
                                    $xcadena = " " . $xcadena . " VEINTE " . $xsub;
                                else
                                    $xcadena = " " . $xcadena . " " . $xseek . " " . $xsub;
                                $xy = 3;
                            }
                            else {
                                $key = (int) substr($xaux, 1, 1) * 10;
                                $xseek = $xarray[$key];
                                if (20 == substr($xaux, 1, 1) * 10)
                                    $xcadena = " " . $xcadena . " " . $xseek;
                                else
                                    $xcadena = " " . $xcadena . " " . $xseek . " Y ";
                            } // ENDIF ($xseek)
                        } // ENDIF (substr($xaux, 1, 2) < 10)
                        break;
                    case 3: // checa las unidades
                        if (substr($xaux, 2, 1) < 1) { // si la unidad es cero, ya no hace nada
                            
                        } else {
                            $key = (int) substr($xaux, 2, 1);
                            $xseek = $xarray[$key]; // obtengo directamente el valor de la unidad (del uno al nueve)
                            $xsub = subfijo($xaux);
                            $xcadena = " " . $xcadena . " " . $xseek . " " . $xsub;
                        } // ENDIF (substr($xaux, 2, 1) < 1)
                        break;
                } // END SWITCH
            } // END FOR
            $xi = $xi + 3;
        } // ENDDO

        if (substr(trim($xcadena), -5, 5) == "ILLON") // si la cadena obtenida termina en MILLON o BILLON, entonces le agrega al final la conjuncion DE
            $xcadena.= " DE";

        if (substr(trim($xcadena), -7, 7) == "ILLONES") // si la cadena obtenida en MILLONES o BILLONES, entoncea le agrega al final la conjuncion DE
            $xcadena.= " DE";

        // ----------- esta línea la puedes cambiar de acuerdo a tus necesidades o a tu país -------
        if (trim($xaux) != "") {
            switch ($xz) {
                case 0:
                    if (trim(substr($XAUX, $xz * 6, 6)) == "1")
                        $xcadena.= "UN BILLON ";
                    else
                        $xcadena.= " BILLONES ";
                    break;
                case 1:
                    if (trim(substr($XAUX, $xz * 6, 6)) == "1")
                        $xcadena.= "UN MILLON ";
                    else
                        $xcadena.= " MILLONES ";
                    break;
                case 2:
                    if ($xcifra < 1) {
                        //$xcadena = "CERO PESOS $xdecimales/100 M.N.";
                        $xcadena = "CERO";
                    }
                    if ($xcifra >= 1 && $xcifra < 2) {
                        //$xcadena = "UN PESO $xdecimales/100 M.N. ";
                        $xcadena = "UN";
                    }
                    if ($xcifra >= 2) {
                        //$xcadena.= " PESOS $xdecimales/100 M.N. "; //
                        $xcadena.= "";
                    }
                    break;
            } // endswitch ($xz)
        } // ENDIF (trim($xaux) != "")
        // ------------------      en este caso, para México se usa esta leyenda     ----------------
        $xcadena = str_replace("VEINTI ", "VEINTI", $xcadena); // quito el espacio para el VEINTI, para que quede: VEINTICUATRO, VEINTIUN, VEINTIDOS, etc
        $xcadena = str_replace("  ", " ", $xcadena); // quito espacios dobles
        $xcadena = str_replace("UN UN", "UN", $xcadena); // quito la duplicidad
        $xcadena = str_replace("  ", " ", $xcadena); // quito espacios dobles
        $xcadena = str_replace("BILLON DE MILLONES", "BILLON DE", $xcadena); // corrigo la leyenda
        $xcadena = str_replace("BILLONES DE MILLONES", "BILLONES DE", $xcadena); // corrigo la leyenda
        $xcadena = str_replace("DE UN", "UN", $xcadena); // corrigo la leyenda
    } // ENDFOR ($xz)
    return trim($xcadena);
}

// END FUNCTION

function subfijo($xx)
{ // esta función regresa un subfijo para la cifra
    $xx = trim($xx);
    $xstrlen = strlen($xx);
    if ($xstrlen == 1 || $xstrlen == 2 || $xstrlen == 3)
        $xsub = "";
    //
    if ($xstrlen == 4 || $xstrlen == 5 || $xstrlen == 6)
        $xsub = "MIL";
    //
    return $xsub;
}

// END FUNCTION


//Se reciben los datos del POST
$juridica=$_POST['juridica'];
$usuario=$_POST['usuario'];
$ruc=$_POST['ruc'];
$natural=$_POST['natural'];
$paterno=$_POST['paterno'];
$materno=$_POST['materno'];
$nombre__ususario=$_POST['nombre__ususario'];
$tipo__identificacion=$_POST['tipo__identificacion'];
$num__identificacion=$_POST['num__identificacion'];
$nacimiento=$_POST['nacimiento'];
$provincia=$_POST['provincia'];
$ciudad=$_POST['ciudad'];
$direccion=$_POST['direccion'];
$celular=$_POST['celular'];
$telefono=$_POST['telefono'];
$email=$_POST['email'];
$checkboxMailSi=($_POST['checkboxMailSi']='on' ?  1:0);
$tipo__cuenta=$_POST['tipo__cuenta'];
$num__cuenta=$_POST['num__cuenta'];
$banco=$_POST['banco'];
$patrocinador__paterno=$_POST['patrocinador__paterno'];
$patrocinador__materno=$_POST['patrocinador__materno'];
$nombre__patrocinador=$_POST['nombre__patrocinador'];
$celular_patrocinador=$_POST['celular-patrocinador'];
$telefono__patrocinador=$_POST['telefono__patrocinador'];
$codigo__patrocinador=$_POST['codigo__patrocinador'];
$fecha__solicitud=$_POST['fecha__solicitud'];



//instanciamos la clase conexion
$_conexion = new conexion;
//creamos la consulta SELECT
$query= "INSERT INTO man_suscripcion (juridica,usuario,ruc,rnatural,paterno,materno,nombreusuario,tipoidentificacion,numidentificacion,nacimiento,provincia,ciudad,direccion,celular,telefono,email,checkboxMailSi,tipocuenta,numcuenta,banco,p_paterno,p_materno,p_nombre,p_celular,p_telefono,p_codigo,fechasolicitud,creadodate) VALUES (";
$query.="'".$juridica."',";
$query.="'".$usuario."',";
$query.="'".$ruc."',";
$query.="'".$natural."',";
$query.="'".$paterno."',";
$query.="'".$materno."',";
$query.="'".$nombre__ususario."',";
$query.="'".$tipo__identificacion."',";
$query.="'".$num__identificacion."',";
$query.="'".$nacimiento."',";
$query.="'".$provincia."',";
$query.="'".$ciudad."',";
$query.="'".$direccion."',";
$query.="'".$celular."',";
$query.="'".$telefono."',";
$query.="'".$email."',";
$query.="".$checkboxMailSi.",";
$query.="'".$tipo__cuenta."',";
$query.="'".$num__cuenta."',";
$query.="'".$banco."',";
$query.="'".$patrocinador__paterno."',";
$query.="'".$patrocinador__materno."',";
$query.="'".$nombre__patrocinador."',";
$query.="'".$celular_patrocinador."',";
$query.="'".$telefono__patrocinador."',";
$query.="'".$codigo__patrocinador."',";
$query.="'".$fecha__solicitud."',";
$query.="CURDATE());";
//echo $query;
$datosRecibidos = $_conexion->nonQueryId($query);
/*imprimimos el resultado es importante reclacar que este metodo retorna las filas afectadas,
En este caso si el INSERT se ha realizado la respuesta sera 1 si no la respuesta sera 0 */
print_r($datosRecibidos);

function number_words($valor,$desc_moneda, $sep, $desc_decimal) {
     $arr = explode(".", $valor);
     $entero = $arr[0];
     if (isset($arr[1])) {
         $decimos = strlen($arr[1]) == 1 ? $arr[1] . '0' : $arr[1];
     }

     $fmt = new \NumberFormatter('es', \NumberFormatter::SPELLOUT);
     if (is_array($arr)) {
         $num_word = ($arr[0]>=1000000) ? "{$fmt->format($entero)} de $desc_moneda" : "{$fmt->format($entero)} $desc_moneda";
         if (isset($decimos) && $decimos > 0) {
             $num_word .= " $sep  {$fmt->format($decimos)} $desc_decimal";
         }
     }
     return $num_word;
}

function contratomembresia($usuario,$juridica,$ruc,$num__identificacion,$natural,$paterno,$materno,$nombre__ususario,$tipo__identificacion,$nacimiento,$provincia,$ciudad,$direccion,$celular,$telefono,$email,$checkboxMailSi,$tipo__cuenta,$num__cuenta,$banco,$patrocinador__paterno,$patrocinador__materno,$nombre__patrocinador,$celular_patrocinador,$telefono__patrocinador,$codigo__patrocinador,$fecha__solicitud){
    ob_clean(); 
    // create new PDF document
    
    //fecha solicitud
    $fechaseparada=explode("-", $fecha__solicitud);
    $dia=$fechaseparada[2];
    $mes=$fechaseparada[1];
    $anio=$fechaseparada[0];
    
    $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");

    //echo $meses[$mes-1]; 
    
    
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    // set document information
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Administrator');
    $pdf->SetTitle('CONTRATO DE MEMBRESIA');
    $pdf->SetSubject('MEMBRESIA');
    $pdf->SetKeywords('MEMBRESIA');

    // set default header data
<<<<<<< HEAD
    //$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 006', PDF_HEADER_STRING);

=======
   
    //$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 000', PDF_HEADER_STRING);
    
>>>>>>> d370eb6ffcd9e653a23efeb1bf77c696475f50ae
    // set header and footer fonts
    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

    // set default monospaced font
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    // set margins
    //$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetMargins(PDF_MARGIN_LEFT, 20, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

    // set auto page breaks
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

    // set image scale factor
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    // set some language-dependent strings (optional)
    if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
        require_once(dirname(__FILE__).'/lang/eng.php');
        $pdf->setLanguageArray($l);
    }
    
    //$pdf->SetPrintHeader(false); 
    $pdf->SetPrintFooter(false);

    // ---------------------------------------------------------

    // set font
    $pdf->SetFont('dejavusans', '', 10);

    // add a page
    $pdf->AddPage();

    // writeHTML($html, $ln=true, $fill=false, $reseth=false, $cell=false, $align='')
    // writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=0, $reseth=true, $align='', $autopadding=true)

    // create some HTML content

    $preinicio='<h1><div style="text-align:center">CONTRATO DE MEMBRESÍA ENTRE TEDU Y EL ASOCIADO INDEPENDIENTE</div></h1>';
    $codigousuario='<p>Codigo de usuario:'.$usuario.'</p>';
    $tabla='<table border="1" cellspacing="3" cellpadding="4">
        <tr  bgcolor= "#000000">
            <th colspan="2" style="text-align:center;color:white;">DATOS DEL SOLICITANTE</th>
        </tr>
        <tr>
            <td>Persona jurídica:'.$juridica.'</td>
            <td>RUC:'.$ruc.'</td>
        </tr> 
        <tr>
            <td colspan="2">Persona Natural:'.$natural.'</td>
           
        </tr>    
    </table>
    ';

    $tabla1='<table border="1" cellspacing="3" cellpadding="4">
        <tr  bgcolor= "#000000">
            <th colspan="3" style="text-align:center;color:white;">A. IDENTIFICACIÓN</th>
        </tr>
        <tr>
            <td>1er. Apellido:</td>
            <td>2do. Apellido:</td>
            <td>Nombres:</td>
        </tr>
        <tr>
            <td>'.$paterno.'</td>
            <td>'.$materno.'</td>
            <td>'.$nombre__ususario.'</td>
        </tr> 
        <tr>
            <td>Tipo de identificación:</td>
            <td>Fecha de nacimiento (dd/mm/aaaa):</td>  
            <td>Lugar de nacimiento (ciudad y provincia):</td>       
        </tr>
        <tr>
            <td>'.$tipo__identificacion.' : '.$num__identificacion.'</td>
            <td>'.$nacimiento.'</td>
            <td>'.$ciudad.' - '.$provincia.'</td>
        </tr>   
        <tr>
            <td colspan="3">Dirección domiciliaria (ciudad y provincia):'.$direccion.'</td>
               
        </tr> 
        <tr>
            <td>Celular:</td>
            <td>Teléfono:</td>  
            <td>Correo electrónico:</td>                 
        </tr>
        <tr>
            <td>'.$celular.'</td>
            <td>'.$telefono.'</td>
            <td>'.$email.'</td>
        </tr>
        <tr>
            <td colspan="3">¿Acepto recibir la información relacionada con la red de mercadeo TEDU en la dirección de correo suministrada? '.($checkboxMailSi=1?'SI':'NO').' </td>
               
        </tr> 
        <tr>
            <td>Número de cuenta bancaria:</td>
            <td>Tipo de cuenta:</td>  
            <td>Banco:</td>                 
        </tr>
        <tr>
            <td>'.$num__cuenta.'</td>
            <td>'.$tipo__cuenta.'</td>
            <td>'.$banco.'</td>
        </tr>

    </table>';
    
   
    $tabla2='
    <table border="1" cellspacing="3" cellpadding="4">
        <tr  bgcolor= "#000000">
            <th colspan="3" style="text-align:center;color:white;">B. DATOS DEL PATROCINADOR</th>
        </tr>
        <tr>
            <td>1er. Apellido:</td>
            <td>2do. Apellido:</td>
            <td>Nombres:</td>
        </tr> 
        <tr>
            <td>'.$patrocinador__paterno.'</td>
            <td>'.$patrocinador__materno.'</td>
            <td>'.$nombre__patrocinador.'</td>
        </tr>
        <tr>
            <td>Celular:</td>
            <td>Teléfono:</td>  
            <td>Código de usuario:</td>                 
        </tr>
        <tr>
            <td>'.$celular_patrocinador.'</td>
            <td>'.$telefono__patrocinador.'</td>
            <td>'.$codigo__patrocinador.'</td>
        </tr>
        <tr>
            <td colspan="3" style="text-align:center">Fecha de solicitud (dd/mm/aaaa): '.$fecha__solicitud.'</td>
                         
        </tr>    
    </table><br>';
    
    $html = '<h2><div style="text-align:center">CONTRATO DE MEMBRESÍA</div></h2>';
    
    $inicio='<p style="text-align:justify">Por una parte, entre <strong>TEDU S.A.S.</strong>, identificada con el RUC No. 0993370883001, con domicilio en la ciudad de Guayaquil, en adelante <strong>TEDU</strong>; y por la otra parte, el <strong>ASOCIADO INDEPENDIENTE</strong>, persona natural o jurídica, quien actúa en nombre propio y se identifica como aparece en el pie de firma, acuerdan celebrar el presente CONTRATO DE MEMBRESÍA, teniendo en cuenta lo estipulado en el Plan de Afiliación y políticas de comercialización, los Términos y Condiciones del Uso del sitio web de la empresa, el Contrato de Membresía, el Contrato Multinivel y el Código de Ética de <strong>TEDU</strong>, documentos que hacen parte integrante del presente acuerdo de voluntades, y de acuerdo a lo convenido en las siguientes cláusulas:</p>';
    
    $primera='<p style="text-align:justify"><strong>PRIMERA: SOLICITUD DE ACEPTACIÓN COMO ASOCIADO INDEPENDIENTE.-</strong> Con el llenado y firma del presente contrato, solicito mi aceptación como <strong>ASOCIADO INDEPENDIENTE</strong> a la red de mercadeo <strong>TEDU</strong>, aceptando desde ya todos los términos y condiciones estipulados en este documento, en el Plan de Afiliación y políticas de comercialización, los Términos y Condiciones del Uso del sitio web de la empresa, el Contrato de Membresía, el Contrato Multinivel y el Código de Ética de <strong>TEDU</strong>.</p>';
    $segunda='<p style="text-align:justify"><strong>SEGUNDA: ACEPTACIÓN COMO ASOCIADO INDEPENDIENTE.- TEDU</strong> aceptará con absoluta autonomía, independencia, discrecionalidad al nuevo <strong>ASOCIADO INDEPENDIENTE</strong>, por lo tanto, al aceptar la solicitud de afiliación generará un Código de Usuario que será único para cada <strong>ASOCIADO INDEPENDIENTE</strong>. El Código de Usuario quedará activo en el momento en que se verifique efectivamente el pago del <strong>KIT DE INICIO</strong> por parte del <strong>ASOCIADO INDEPENDIENTE</strong>. Por consiguiente, la aceptación del Contrato de Membresía por parte del <strong>ASOCIADO INDEPENDIENTE</strong> y el pago del <strong>KIT DE INICIO</strong>, son dos de los requisitos que se requieren para pertenecer a la red de mercadeo <strong>TEDU</strong>.</p>';
    $tercera='<p style="text-align:justify"><strong>TERCERA: KIT DE INICIO</strong>.- Es una membresía que incluye dentro del costo inicial de participación, los materiales de capacitación, así como referencias y guías de información en relación a cómo hacer el negocio; por lo tanto, el <strong>ASOCIADO INDEPENDIENTE</strong> al solicitar su aceptación en la red de mercadeo <strong>TEDU</strong>, acepta pagar el precio del <strong>KIT DE INICIO</strong> el cual conocerá previamente.
    <br><br>Con la entrega del <strong>KIT DE INICIO</strong> se hará la entrega de los siguientes documentos, los cuales deberán ser firmados y devueltos (Contrato Multinivel y Contrato de Membresía) a <strong>TEDU</strong> por parte del <strong>ASOCIADO INDEPENDIENTE</strong> en señal de aceptación, perfeccionándose así el ingreso a la red de mercadeo <strong>TEDU</strong>.</p>';
    $cuarta='<p style="text-align:justify"><strong>CUARTA: VIGENCIA</strong>.- El presente contrato tendrá vigencia de un (1) año, el cual se renovará indefinidamente siempre que el <strong>ASOCIADO INDEPENDIENTE</strong> cumpla con los términos y condiciones estipulados en los diferentes documentos que hacen parte de la red de mercadeo <strong>TEDU</strong>.
    <br><br>Cualquiera de las partes podrá dar por terminada la vinculación como <strong>ASOCIADO INDEPENDIENTE</strong> a la red de mercadeo <strong>TEDU</strong>, por cualquier motivo, siempre respetándose los derechos que se hayan adquirido, por cualquiera de las partes.</p>';
    $quinta='<p style="text-align:justify"><strong>QUINTA: CESIÓN.- El ASOCIADO INDEPENDIENTE</strong> tiene prohibido ceder el presente Contrato de Membresía, salvo autorización de manera expresa y por escrito de <strong>TEDU</strong>.</p>';
    $sexta='<p style="text-align:justify"><strong>SEXTA: PROMOCIÓN DE PRODUCTOS Y SERVICIOS</strong>.- Con la firma del presente contrato acepto consumir, promocionar y vender los productos y servicios que oferta TEDU, respetando el Código de Ética y el Plan de Afiliación y política de comercialización de <strong>TEDU</strong>.</p>';
    $septima='<p style="text-align:justify"><strong>SÉPTIMA: BENEFICIOS.- El ASOCIADO INDEPENDIENTE</strong> declara conocer y aceptar que los beneficios que reciba como resultado de las labores de promoción y comercialización de productos y servicios en la red de mercadeo de <strong>TEDU</strong>, se obtendrán como resultado de la venta de dichos productos y servicios dentro de su red de negocios y por la vinculación de personas a la misma.</p>';
    $octava='<p style="text-align:justify"><strong>OCTAVA: INDEPENDENCIA LABORAL</strong>.- Todas las actividades que desarrolle el <strong>ASOCIADO INDEPENDIENTE</strong> se encontrarán enmarcadas dentro de las denominadas actividades propias del mercadeo en red o de mercadeo multinivel, y regidas por el derecho mercantil. Por consiguiente, no existirá en lo absoluto ningún vinculo de subordinación o dependencia laboral entre <strong>TEDU</strong> y el <strong>ASOCIADO INDEPENDIENTE</strong>, quien desarrollará su labor bajo su propio riesgo y cuenta, de manera independiente, asumiendo todos los riesgos y gastos que generen dicha actividad, así como los impuestos que legalmente tenga que pagar a la administración tributaria del país. Tampoco el <strong>ASOCIADO INDEPENDIENTE</strong> será considerado como un agente, representante, franquiciado, socio, empleado, contratista, fiduciario, ni beneficiario de <strong>TEDU</strong>.</p>';
    $novena='<p style="text-align:justify"><strong>NOVENA: VERACIDAD.- El ASOCIADO INDEPENDIENTE</strong> manifiesta que toda la información consignada en el presente Contrato de Membresía, corresponde a la verdad, que los datos personales suministrados serán los que se deberán tener en cuenta para todos los efectos de comunicación y notificación relacionados con sus actividades comerciales de acuerdo al Plan de Afiliación.</p>';
    $decima='<p style="text-align:justify"><strong>DÉCIMA: INDEMNIDAD.- El ASOCIADO INDEPENDIENTE</strong> se compromete a defender y a mantener indemne a TEDU de cualquier responsabilidad que pudiera derivarse de su actividad comercial.</p>';
    $onceava='<p style="text-align:justify"><strong>DÉCIMA PRIMERA: LEY APLICABLE</strong>.- El presente acuerdo se regirá por las leyes ecuatorianas.</p>';
    $final1='<p style="text-align:justify">En señal de conformidad el <strong>ASOCIADO INDEPENDIENTE</strong> suscribe el presente Contrato de Membresía en dos (2) ejemplares con la misma validez jurídica cada uno, a los '.numtoletras($dia).' ('.$dia.') días del mes de '.strtoupper($meses[$mes-1]).' del año '.numtoletras($anio).' ('.$anio.').</p>';
    $final2='<p style="text-align:justify">Nombre del <strong>ASOCIADO INDEPENDIENTE</strong>:'.$paterno.' '.$materno.' '.$nombre__ususario.'</p>';
    $final3='<p style="text-align:justify">Documento de identidad, cédula de ciudadanía:'.$ruc.'</p><br><br><br>';
    $final4='
    
    <p style="text-align:center">Firma del <strong>ASOCIADO INDEPENDIENTE</strong></p>';
    $texto=$preinicio.$codigousuario.$tabla.$tabla1.$tabla2.$html.$inicio.$primera.$segunda.$tercera.$cuarta.$quinta.$sexta.$septima.$octava.$novena.$decima.$onceava.$final1.$final2.$final3.$final4;
    // output the HTML content
    $pdf->writeHTML($texto, true, false, true, false, '');
    // reset pointer to the last page
    
    
    //multinivel
    $preinicio='<h1><div style="text-align:center">CONTRATO MULTINIVEL TEDU</div></h1>';
        
    $inicio='<p style="text-align:justify">Por una parte, entre <strong>TEDU S.A.S.</strong>, identificada con el RUC No. 0993370883001, con domicilio en la ciudad de Guayaquil, en adelante TEDU; y por la otra parte, el <strong>ASOCIADO INDEPENDIENTE</strong>, persona natural o jurídica, quien actúa en nombre propio y se identifica como aparece en el pie de su firma, acuerdan celebrar el presente <strong>CONTRATO MULTINIVEL</strong>, teniendo en cuenta lo estipulado en el Plan de Afiliación, los Términos y Condiciones, el Contrato Multinivel, y el Código de Ética de <strong>TEDU</strong>, documentos que hacen parte integrante del presente acuerdo de voluntades, y de acuerdo a lo convenido en las siguientes cláusulas:</p>';
    
    $primera='<p style="text-align:justify"><strong>PRIMERA: OBJETO</strong>.- Mediante la suscripción del presente acuerdo de voluntades, se conviene vincular al <strong>ASOCIADO INDEPENDIENTE</strong> a la red de mercadeo <strong>TEDU</strong> para que desarrolle de manera totalmente independiente, sin que exista ningún tipo de relación laboral, las actividades propias del mercadeo en red o mercadeo multinivel.</p>';
    $segunda='<p style="text-align:justify"><strong>SEGUNDA: INGRESO</strong>.- Podrán hacer parte de la red de mercadeo <strong>TEDU</strong> cualquier persona natural o jurídica, y para ello el futuro <strong>ASOCIADO INDEPENDIENTE</strong> deberá:</p>
    <ol type="a">
        <li>Registrarse mediante el llenado y aceptación de un Contrato de Membresía, en el sitio web de la empresa.</li>
        <li>Leer, llenar, firmar en señal de conformidad y entregar a TEDU, los siguientes documentos de ser el caso:
            <ol type="i">
                <li>Plan de Afiliación</li>
                <li>Términos y Condiciones de Uso del sitio web de la empresa</li>
                <li>Contrato de membresía</li>
                <li>Contrato de multinivel</li>
                <li>Código de Ética</li>
            </ol>
            <br>Los documentos enunciados en este literal estarán disponibles en el sitio web de la empresa.
         </li>
        <li style="text-align:justify">Adquirir un KIT DE INICIO, que es una membresía que incluye dentro del costo inicial de participación, los materiales de capacitación, así como referencias y guías de información en relación a cómo hacer el negocio.<br><br><strong>TEDU</strong> tendrá el derecho de aceptar o rechazar el ingreso de un aspirante a <strong>ASOCIADO INDEPENDIENTE</strong>, a su entera discreción. En caso de que <strong>TEDU</strong> rechace el ingreso, lo notificará por escrito al aspirante y reembolsará el costo del <strong>KIT DE INICIO</strong>.</li>
    </ol>
    ';
   
    $tercera='<p style="text-align:justify"><strong>TERCERA: FORMA, PERIODICIDAD Y REQUISITOS PARA EL PAGO</strong>.- Los pagos correspondientes a los beneficios que obtenga el <strong>ASOCIADO INDEPENDIENTE</strong> de acuerdo al Plan de Afiliación, se realizarán en dinero mediante consignación en la cuenta bancaria que suministre el <strong>ASOCIADO INDEPENDIENTE</strong>. Para el pago será necesario que el <strong>ASOCIADO INDEPENDIENTE</strong> presente la respectiva factura.
    <br><br>Todos los beneficios a que tenga derecho el <strong>ASOCIADO INDEPENDIENTE</strong> serán cancelados de acuerdo a lo estipulado en el Plan de Afiliación de la siguiente manera:
    <ol type="1">
            <li>Las comisiones por la venta de membresías, que hagan de manera directa los <strong>ASOCIADOS INDEPENDIENTES</strong>, serán pagadas dentro de la semana inmediatamente siguiente a su causación.</li>
            <li>Las comisiones adicionales por las ventas de membresías que hagan las personas que pertenecen a la red del <strong>ASOCIADO INDEPENDIENTE</strong>, serán pagadas dentro de la semana inmediatamente siguiente a su causación.</li>
            <li>Para cobrar las comisiones se deberá estar activo en el mes inmediatamente anterior. Por ejemplo, <strong>para recibir las comisiones del mes de febrero, el ASOCIADO INDEPENDIENTE deberá estar activo al mes de enero.</strong></li>
            <li>Cada <strong>ASOCIADO INDEPENDIENTE</strong> tendrá una billetera virtual en la que se registrarán los saldos de crédito a su favor por las transacciones que le reporten beneficio, tales como ventas de membresías, de productos, ventas que realizan las personas que formen parte de sus niveles de organización.</li>
            <li>Todos los beneficios se pagarán sobre el precio de cada producto, es decir, sin tener en cuenta los impuestos de ley; además, los beneficios que obtenga cualquier <strong>ASOCIADO INDEPENDIENTE</strong> serán pagados sobre el cálculo que se haga una vez que se realicen todas las deducciones tributarias.</li>
    </ol></p>  
    ';
    
    $cuarta='<p style="text-align:justify"><strong>CUARTA: DERECHOS DEL ASOCIADO INDEPENDIENTE</strong>.- A partir de que TEDU acepte el presente Contrato, el suscriptor será nombrado <strong>ASOCIADO INDEPENDIENTE</strong>. Un <strong>ASOCIADO INDEPENDIENTE</strong> tiene el derecho no exclusivo para:
    <ol type="a">
            <li>Registrar <strong>ASOCIADOS INDEPENDIENTES</strong> para que lleven a cabo compras y participen de todos los beneficios del Plan de Afiliación de <strong>TEDU</strong>.</li>
            <li>Conseguir, instruir, mantener y promocionar a sus propios <strong>ASOCIADOS INDEPENDIENTES</strong>, enseñándoles el sistema de mercadeo en red de <strong>TEDU</strong>, así como mostrarles todos los documentos y demás beneficios contemplados en el Plan de Afiliación de <strong>TEDU</strong>.</li>
            <li>Recibir toda la documentación de carácter informativo o promocional relacionada con el Plan de Afiliación de <strong>TEDU</strong>.</li>
            <li>Obtener beneficios económicos o en especie de acuerdo al Plan de Afiliación de <strong>TEDU</strong> y de acuerdo a su posición dentro de la red de mercadeo. También podrá participar de todos los incentivos que la red de mercadeo <strong>TEDU</strong> establezca de acuerdo a las metas que se fijen.</li>
            <li><strong>El ASOCIADO INDEPENDIENTE</strong> tiene derecho a recibir las capacitaciones y todas las instrucciones que sean necesarias para el desarrollo de su red de mercadeo; también tiene derecho a recibir capacitación precisa sobre los productos y el Plan de Afiliación.</li>
            <li>Un <strong>ASOCIADO INDEPENDIENTE</strong> está autorizado para realizar actividades de patrocinio y/o ayuda de otros <strong>ASOCIADOS INDEPENDIENTES</strong>.</li>
            <li>Participar en todos los programas y oportunidades que la red de mercadeo <strong>TEDU</strong> a su entera y absoluta discreción determine en cualquier momento.</li>
            <li>El <strong>ASOCIADO INDEPENDIENTE</strong> podrá obtener y usar la información personal de todos los <strong>ASOCIADOS INDEPENDIENTES</strong> que se encuentren dentro de su red de mercadeo, únicamente con el fin de desarrollar su negocio de mercadeo en red.</li>
            <li><strong>El ASOCIADO INDEPENDIENTE</strong> tiene derecho a ser informado con precisión por parte de TEDU, de las características de los productos o servicios que promocionará.</li>
            <li>El <strong>ASOCIADO INDEPENDIENTE</strong> podrá terminar en cualquier tiempo y de forma unilateral el vínculo contractual, mediante escrito dirigido a <strong>TEDU</strong>.</li>
            <li><strong>El ASOCIADO INDEPENDIENTE</strong> tiene derecho a recibir de manera oportuna e integral en cantidad y calidad, los bienes y servicios ofrecidos por <strong>TEDU<strong>.</li>

    </ol> </p>
    ';
    
    
    $quinta='<p style="text-align:justify"><strong>QUINTA: OBLIGACIONES DEL ASOCIADO INDEPENDIENTE</strong>.- Desde el momento que el <strong>ASOCIADO INDEPENDIENTE</strong> suscribe el presente contrato, tendrá las siguientes obligaciones:
    <ol type="a">
        <li>Respetar y cumplir a cabalidad las disposiciones contenidas en el presente contrato.</li>
        <li>Ceñirse a lo estipulado en el Plan de Afiliación.</li>
        <li>Utilizar el sitio web de <strong>TEDU</strong> de acuerdo a los Términos y Condiciones de Uso.</li>
        <li>Cumplir y hacer respetar el Código de Ética de <strong>TEDU</strong>.</li>
        <li>Llevar a cabo cualquier actividad de mercadeo en red respetando las políticas de comercialización contenidas en el Plan de Afiliación de <strong>TEDU</strong>.</li>
        <li>Recibir todos los beneficios del Plan de Afiliación de <strong>TEDU</strong>, de acuerdo a lo convenido.</li>
        <li>Capacitarse y ejercer las actividades de mercadeo en red.</li>
        <li>El <strong>ASOCIADO INDEPENDIENTE</strong> deberá actualizar todos los datos personales que haya registrado ante <strong>TEDU</strong> cuando dichos datos hayan cambiado, de forma oportuna.</li>
        <li>El <strong>ASOCIADO INDEPENDIENTE</strong> se compromete a realizar todas sus actividades de comercialización de manera totalmente independiente, sin que exista vínculo laboral con <strong>TEDU</strong>.</li>
    </ol>    
    </p>';

    $sexta='<p style="text-align:justify"><strong>SEXTA: DERECHOS DE TEDU.- A TEDU</strong> le asisten los siguientes derechos:
    <ol type="a">
        <li>Se reserva el derecho de tomar medidas correctivas, como son por ejemplo la suspensión temporal o terminación unilateral del presente contrato cuando el <strong>ASOCIADO INDEPENDIENTE</strong> no respete lo convenido, de acuerdo a las cláusulas estipuladas en éste contrato o en cualquiera de los documentos que lo conforman.</li>
        <li><strong>TEDU</strong> de manera autónoma, en cualquier momento y previa comunicación a sus <strong>AFILIADOS INDEPENDIENTES</strong>, podrá realizar las modificaciones que considere pertinentes al Plan de Afiliación y a los demás documentos que hacen parte del convenio.</li>
        <li>Únicamente para los fines previstos en el desarrollo de su objeto social, la red de mercadeo <strong>TEDU</strong> registrará y mantendrá una base de datos de todos los <strong>ASOCIADOS INDEPENDIENTES</strong>.</li>
        <li><strong>TEDU</strong> a su entera discreción podrá promocionar directamente sus productos o servicios con cualquier <strong>ASOCIADO INDEPENDIENTE</strong>, incluyendo propaganda, publicidad, promociones especiales para acumular puntos, beneficios, programas de fidelización, y/o cualquier estrategia de mercadeo y ventas.</li>
    </ol>  
    </p>';
    
    $septima='<p style="text-align:justify"><strong>SÉPTIMA: OBLIGACIONES DE TEDU</strong>.-
    <ol type="a">
        <li>Pagar oportunamente todos los beneficios contemplados en el Plan de Afiliación.</li>
        <li>Hacer buen uso de los datos personales proporcionados por los <strong>ASOCIADOS INDEPENDIENTES</strong>, de acuerdo con las normas vigentes en la materia.</li>
        <li>Comunicar oportunamente al <strong>ASOCIADO INDEPENDIENTE</strong> cualquier modificación que realice al Plan de Afiliación y las políticas de comercialización, los Términos y Condiciones de uso del sitio web, el Contrato de Membresía, el Contrato Multinivel y el Código de Ética de <strong>TEDU</strong> y entregar dichos documentos.</li>
        <li>Instruir al <strong>ASOCIADO INDEPENDIENTE</strong> respecto del Plan de Afiliación y las políticas de comercialización, los Términos y Condiciones de Uso del sitio web de <strong>TEDU</strong>, el Contrato de Membresía, el Contrato Multinivel y el Código de Ética de <strong>TEDU</strong> y entregar dichos documentos.</li>
        <li><strong>TEDU</strong> responderá de forma precisa las preguntas, consultas y solicitudes de aclaración formuladas por los <strong>ASOCIADOS INDEPENDIENTES</strong>, antes, durante y después de su vinculación. Las respuestas a las preguntas, consultas o solicitudes de aclaración se harán a la dirección, correo electrónico u otros medios que suministre el <strong>ASOCIADO INDEPENDIENTE</strong> que la formule, dentro de diez (10) días hábiles siguientes a su recepción.</li>
    
    </ol>
    </p>';
    
    $octava='<p style="text-align:justify"><strong>OCTAVA: AUTORIZACIÓN DE DATOS PERSONALES</strong>.- Con la suscripción del presente acuerdo de voluntades, el <strong>ASOCIADO INDEPENDIENTE</strong>, manifiesta expresamente que comprende, acepta y autoriza a <strong>TEDU</strong>, para usar y/o transferir y/o ceder la información personal contenida en el presente contrato, así como toda la información que se genere en desarrollo de sus actividades dentro de la red de mercadeo, a terceras personas, incluyendo otros <strong>ASOCIADOS INDEPENDIENTES</strong>, de acuerdo con las normas que regulan la materia.</p>';
    
    $novena='<p style="text-align:justify"><strong>NOVENA: VIGENCIA</strong>.- El presente contrato tendrá una vigencia de un (1) año desde el momento en que se genere el Código de Usuario. No obstante lo anterior, para que el contrato entre en vigencia deberá ser firmado por el <strong>ASOCIADO INDEPENDIENTE</strong>.
    <br><br>Una vez terminada la vigencia, el contrato se renovará de manera automática por igual periodo, a menos que cualquiera de las Partes decida ponerle fin.
    </p>';
    
    
    $decima='<p style="text-align:justify"><strong>DÉCIMA: TERMINACIÓN DEL CONTRATO</strong>.- <strong>El ASOCIADO INDEPENDIENTE</strong> o <strong>TEDU</strong> podrán dar por terminado el contrato de acuerdo a las siguientes situaciones:
    <ol type="a">
        <li>En cualquier momento y por cualquier motivo mediante aviso por escrito a la otra parte con treinta (30) días de anticipación.</li>
        <li>De manera inmediata sin necesidad de resolución judicial, mediante simple notificación, cuando alguna de las partes incumpla cualquiera de las estipulaciones contempladas en el presente contrato, por violación de lo contenido en el Plan de Afiliación y las políticas de comercialización, los Términos y Condiciones de Uso del sitio web de <strong>TEDU</strong> y el Código de Ética de TEDU; documentos que hacen parte integrante del presente acuerdo de voluntades.</li>
    </ol>  
    </p>';
    
       
    $onceava='<p style="text-align:justify"><strong>DÉCIMA PRIMERA: LIQUIDACIÓN DE BENEFICIOS</strong>.- Una vez se dé por terminado el Contrato, se procederá dentro de los quince (15) días hábiles siguientes, a realizar la entrega de los beneficios a que tenga derecho el <strong>ASOCIADO INDEPENDIENTE</strong> de acuerdo al Plan de Afiliación.</p>';
    $doceava='<p style="text-align:justify"><strong>DÉCIMA SEGUNDA: MODIFICACIÓN DE CLÁUSULAS Y DOCUMENTOS INTEGRANTES.- TEDU</strong> a su entera discreción podrá modificar total o parcialmente los términos del presente Contrato, así como el Plan de Afiliación y las políticas de comercialización, los Términos y Condiciones de Uso del sitio web de la empresa, el Contrato de Membresía y el Código de Ética de <strong>TEDU</strong>, notificando previamente al <strong>ASOCIADO INDEPENDIENTE</strong> para que las acepte expresamente.
    <br><br>Cualquier modificación de las cláusulas del Contrato o de los documentos que hacen parte integral del presente convenio, entrarán a regir desde el momento en que sean aceptadas expresamente por el <strong>ASOCIADO INDEPENDIENTE</strong>.
    <br><br>Si el <strong>ASOCIADO INDEPENDIENTE</strong> no acepta expresamente cualquier modificación, se entenderá que da por terminado el Contrato de manera inmediata, sin que pueda ser sujeto de ningún tipo de sanción o penalidad y siempre manteniendo los beneficios a que tenga derecho hasta ese momento, de acuerdo al Plan de Afiliación.   
    </p>';
   
    $treceava='<p style="text-align:justify"><strong>DÉCIMA TERCERA: INDEPENDENCIA</strong>.- El <strong>ASOCIADO INDEPENDIENTE</strong> entiende, y acepta que el presente Contrato no lo convierte en un empleado, trabajador, representante, agente o franquiciatario de <strong>TEDU</strong> y por tanto, no existirá en lo absoluto ningún vínculo laboral o relación de subordinación entre el <strong>ASOCIADO INDEPENDIENTE</strong> y <strong>TEDU</strong>. De acuerdo a lo anterior, todas las labores que desarrolle el <strong>ASOCIADO INDEPENDIENTE</strong> se enmarcarán dentro de las actividades propias del mercadeo en red o de mercadeo multinivel.';
    $catorceava='<p style="text-align:justify"><strong>DÉCIMA CUARTA: LEY APLICABLE</strong>.- El presente Contrato se regirá por las leyes del Ecuador, particularmente por el Código de Comercio, Código Civil y demás normas complementarias relacionadas con la materia.';
    $quinceava='<p style="text-align:justify"><strong>DÉCIMA QUINTA: CLÁUSULA COMPROMISORIA</strong>.- Cualquiera cuestión o controversia originadas en este contrato o relacionadas con él, serán resueltas por arbitraje en el Centro de Arbitraje y Mediación de la Cámara de Comercio de Guayaquil, de acuerdo con las reglas de la Ley de Arbitraje y Mediación y del Reglamento de dicho Centro. Las partes convienen además en lo siguiente: 
    <ol type="a">
        <li>Los árbitros serán seleccionados conforme lo establecido en la Ley de Arbitraje y Mediación.</li>
        <li>Las partes renuncian a la jurisdicción ordinaria, se obligan a acatar el laudo que expida el Tribunal Arbitral  y se compromete a no interponer ningún recurso en contra del mismo. </li>
        <li>Para la ejecución de  medidas cautelares el Tribunal Arbitral está facultado  para solicitar de los funcionarios públicos, judiciales, policiales y administrativos su cumplimiento, sin que sea necesario recurrir a juez ordinario alguno.</li>
        <li>El Tribunal Arbitral está integrado por un árbitro.</li>
        <li>El procedimiento arbitral será confidencial.</li>
        <li>El lugar de arbitraje será las instalaciones del Centro de Arbitraje y Mediación de la Cámara de Comercio de Guayaquil.</li>
    </ol>    
         
    ';
   
    $dieciseisava='<p style="text-align:justify"><strong>DÉCIMA SEXTA: LIMITE DE RESPONSABILIDAD.- TEDU</strong> no será responsable por los daños o perjuicios que cause el <strong>ASOCIADO INDEPENDIENTE</strong> a terceros durante el desarrollo de sus actividades de mercadeo en red, por lo tanto, el <strong>ASOCIADO INDEPENDIENTE</strong> se compromete a responder por cualquier daño, queja, demanda, gasto o indemnización que pudiera generar frente a terceros, como resultado de sus labores de mercadeo en red en contravención de lo aquí previsto o de cualquiera de los documentos que hacen parte del presente Contrato.</p>';
    $diecisieteava='<p style="text-align:justify"><strong>DÉCIMA SÉPTIMA: CONVENIO TOTAL</strong>.- El presente acuerdo de voluntades y los documentos que hacen parte integral del mismo, constituye un acuerdo total entre <strong>TEDU</strong> y el <strong>ASOCIADO INDEPENDIENTE</strong> y prevalece sobre cualquier otro acuerdo sobre el mismo objeto contractual, escrito u oral, que anteriormente se haya celebrado entre <strong>TEDU</strong> y el <strong>ASOCIADO INDEPENDIENTE</strong>.</p>';
    $dieciochoava='<p style="text-align:justify"><strong>DÉCIMA OCTAVA: NOTIFICACIONES</strong>.- Todas las comunicaciones que se deriven del presente Contrato o de sus documentos integrantes deberán hacerse por escrito a la dirección física o electrónica registrada por el <strong>ASOCIADO INDEPENDIENTE</strong>, también serán válidas las notificaciones realizadas en las publicaciones oficiales de <strong>TEDU</strong>, bien sea en revistas, folletos, guías o a través de su página web. Cualquier notificación que un <strong>ASOCIADO INDEPENDIENTE</strong> requiera hacer a <strong>TEDU</strong>, deberá realizarla únicamente a las direcciones electrónicas institucionales dispuestas para dicho efecto.</p>';
    $diecinueveava='<p style="text-align:justify"><strong>DÉCIMA NOVENA: ACEPTACIÓN DEL CONTRATO Y DOCUMENTOS INCORPORADOS.- El ASOCIADO INDEPENDIENTE</strong> reconoce haber leído y entendido completamente el presente contrato y acuerda sujetarse a él. Asimismo, reconoce haber recibido el <strong>KIT DE INICIO</strong> el cual incluye el <strong>CONTRATO MULTINIVEL</strong> y el <strong>CONTRATO DE MEMBRESÍA</strong>. En caso que exista cualquier inconsistencia entre las disposiciones de éste Contrato y los demás documentos incorporados, las partes se sujetarán a lo establecido en el presente documento.</p>';


    $final1='<p style="text-align:justify">En señal de conformidad las partes suscriben el presente contrato en dos (2) ejemplares con la misma validez jurídica cada uno, a los '.numtoletras($dia).' ('.$dia.') días del mes de '.strtoupper($meses[$mes-1]).' del año '.numtoletras($anio).' ('.$anio.')</p>';
   
    $final4='
    <table border="0">
        <tr><br><br>
            <th>EL ASOCIADO INDEPENDIENTE</th>
            <th>TEDU<br><br></th><br><br>
        </tr>
        <tr>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td style="text-align:left">Nombre:'.$paterno.' '.$materno.' '.$nombre__ususario.'<br>CC:'.$num__identificacion.'<br>Teléfono:'.$telefono.'<br>Celular:'.$celular.'<br>Dirección: '.$direccion.'<br>Ciudad: '.$ciudad.'<br>E-mail: '.$email.'<br></td>
            <td>
                Verónica Vanessa Rodríguez Tablas<br>
                Gerente General<br>
                TEDU S.A.S.<br>
                RUC No. 0993370883001<br>
            </td>
        </tr>
    </table>
    ';
    
    $texto2=$preinicio.$inicio.$primera.$segunda.$tercera.$cuarta.$quinta.$sexta.$septima.$octava.$novena.$decima.$onceava.$doceava.$treceava.$catorceava.$quinceava.$dieciseisava.$diecisieteava.$dieciochoava.$diecinueveava.$final1.$final4;
    $pdf->AddPage();
    $pdf->writeHTML($texto2, true, false, true, false, '');
    //
    
    $pdf->lastPage();

    // ---------------------------------------------------------

    //Close and output PDF document
    $pdf->Output('contratos.pdf', 'I');

    //============================================================+
    // END OF FILE
    //============================================================+
    
    //header("location: https://teduemprende.com/"); 
    
}

function contratomultinivel($usuario,$juridica,$ruc,$num__identificacion,$natural,$paterno,$materno,$nombre__ususario,$tipo__identificacion,$nacimiento,$provincia,$ciudad,$direccion,$celular,$telefono,$email,$checkboxMailSi,$tipo__cuenta,$num__cuenta,$banco,$patrocinador__paterno,$patrocinador__materno,$nombre__patrocinador,$celular_patrocinador,$telefono__patrocinador,$codigo__patrocinador,$fecha__solicitud){
    // create new PDF document
    
    //fecha solicitud
    $fechaseparada=explode("-", $fecha__solicitud);
    $dia=$fechaseparada[2];
    $mes=$fechaseparada[1];
    $anio=$fechaseparada[0];
    
    $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
    
    ob_clean();
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    // set document information
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Administrator');
    $pdf->SetTitle('CONTRATO DE MULTINIVEL');
    $pdf->SetSubject('MULTINIVEL');
    $pdf->SetKeywords('MULTINIVEL');

    // set default header data
    //$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 006', PDF_HEADER_STRING);

    // set header and footer fonts
    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

    // set default monospaced font
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    // set margins
    //$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetMargins(PDF_MARGIN_LEFT, 20, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

    // set auto page breaks
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

    // set image scale factor
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    // set some language-dependent strings (optional)
    if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
        require_once(dirname(__FILE__).'/lang/eng.php');
        $pdf->setLanguageArray($l);
    }

    // ---------------------------------------------------------

    // set font
    $pdf->SetFont('dejavusans', '', 10);

    // add a page
    $pdf->AddPage();

    // writeHTML($html, $ln=true, $fill=false, $reseth=false, $cell=false, $align='')
    // writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=0, $reseth=true, $align='', $autopadding=true)

    // create some HTML content

    $preinicio='<h1><div style="text-align:center">CONTRATO MULTINIVEL TEDU</div></h1>';
        
    $inicio='<p style="text-align:justify">Por una parte, entre <strong>TEDU S.A.S.</strong>, identificada con el RUC No. 0993370883001, con domicilio en la ciudad de Guayaquil, en adelante TEDU; y por la otra parte, el <strong>ASOCIADO INDEPENDIENTE</strong>, persona natural o jurídica, quien actúa en nombre propio y se identifica como aparece en el pie de su firma, acuerdan celebrar el presente <strong>CONTRATO MULTINIVEL</strong>, teniendo en cuenta lo estipulado en el Plan de Afiliación, los Términos y Condiciones, el Contrato Multinivel, y el Código de Ética de <strong>TEDU</strong>, documentos que hacen parte integrante del presente acuerdo de voluntades, y de acuerdo a lo convenido en las siguientes cláusulas:</p>';
    
    $primera='<p style="text-align:justify"><strong>PRIMERA: OBJETO</strong>.- Mediante la suscripción del presente acuerdo de voluntades, se conviene vincular al <strong>ASOCIADO INDEPENDIENTE</strong> a la red de mercadeo <strong>TEDU</strong> para que desarrolle de manera totalmente independiente, sin que exista ningún tipo de relación laboral, las actividades propias del mercadeo en red o mercadeo multinivel.</p>';
    $segunda='<p style="text-align:justify"><strong>SEGUNDA: INGRESO</strong>.- Podrán hacer parte de la red de mercadeo <strong>TEDU</strong> cualquier persona natural o jurídica, y para ello el futuro <strong>ASOCIADO INDEPENDIENTE</strong> deberá:</p>
    <ol type="a">
        <li>Registrarse mediante el llenado y aceptación de un Contrato de Membresía, en el sitio web de la empresa.</li>
        <li>Leer, llenar, firmar en señal de conformidad y entregar a TEDU, los siguientes documentos de ser el caso:
            <ol type="i">
                <li>Plan de Afiliación</li>
                <li>Términos y Condiciones de Uso del sitio web de la empresa</li>
                <li>Contrato de membresía</li>
                <li>Contrato de multinivel</li>
                <li>Código de Ética</li>
            </ol>
            <br>Los documentos enunciados en este literal estarán disponibles en el sitio web de la empresa.
         </li>
        <li style="text-align:justify">Adquirir un KIT DE INICIO, que es una membresía que incluye dentro del costo inicial de participación, los materiales de capacitación, así como referencias y guías de información en relación a cómo hacer el negocio.<br><br><strong>TEDU</strong> tendrá el derecho de aceptar o rechazar el ingreso de un aspirante a <strong>ASOCIADO INDEPENDIENTE</strong>, a su entera discreción. En caso de que <strong>TEDU</strong> rechace el ingreso, lo notificará por escrito al aspirante y reembolsará el costo del <strong>KIT DE INICIO</strong>.</li>
    </ol>
    ';
   
    $tercera='<p style="text-align:justify"><strong>TERCERA: FORMA, PERIODICIDAD Y REQUISITOS PARA EL PAGO</strong>.- Los pagos correspondientes a los beneficios que obtenga el <strong>ASOCIADO INDEPENDIENTE</strong> de acuerdo al Plan de Afiliación, se realizarán en dinero mediante consignación en la cuenta bancaria que suministre el <strong>ASOCIADO INDEPENDIENTE</strong>. Para el pago será necesario que el <strong>ASOCIADO INDEPENDIENTE</strong> presente la respectiva factura.
    <br><br>Todos los beneficios a que tenga derecho el <strong>ASOCIADO INDEPENDIENTE</strong> serán cancelados de acuerdo a lo estipulado en el Plan de Afiliación de la siguiente manera:
    <ol type="1">
            <li>Las comisiones por la venta de membresías, que hagan de manera directa los <strong>ASOCIADOS INDEPENDIENTES</strong>, serán pagadas dentro de la semana inmediatamente siguiente a su causación.</li>
            <li>Las comisiones adicionales por las ventas de membresías que hagan las personas que pertenecen a la red del <strong>ASOCIADO INDEPENDIENTE</strong>, serán pagadas dentro de la semana inmediatamente siguiente a su causación.</li>
            <li>Para cobrar las comisiones se deberá estar activo en el mes inmediatamente anterior. Por ejemplo, <strong>para recibir las comisiones del mes de febrero, el ASOCIADO INDEPENDIENTE deberá estar activo al mes de enero.</strong></li>
            <li>Cada <strong>ASOCIADO INDEPENDIENTE</strong> tendrá una billetera virtual en la que se registrarán los saldos de crédito a su favor por las transacciones que le reporten beneficio, tales como ventas de membresías, de productos, ventas que realizan las personas que formen parte de sus niveles de organización.</li>
            <li>Todos los beneficios se pagarán sobre el precio de cada producto, es decir, sin tener en cuenta los impuestos de ley; además, los beneficios que obtenga cualquier <strong>ASOCIADO INDEPENDIENTE</strong> serán pagados sobre el cálculo que se haga una vez que se realicen todas las deducciones tributarias.</li>
    </ol></p>  
    ';
    
    $cuarta='<p style="text-align:justify"><strong>CUARTA: DERECHOS DEL ASOCIADO INDEPENDIENTE</strong>.- A partir de que TEDU acepte el presente Contrato, el suscriptor será nombrado <strong>ASOCIADO INDEPENDIENTE</strong>. Un <strong>ASOCIADO INDEPENDIENTE</strong> tiene el derecho no exclusivo para:
    <ol type="a">
            <li>Registrar <strong>ASOCIADOS INDEPENDIENTES</strong> para que lleven a cabo compras y participen de todos los beneficios del Plan de Afiliación de <strong>TEDU</strong>.</li>
            <li>Conseguir, instruir, mantener y promocionar a sus propios <strong>ASOCIADOS INDEPENDIENTES</strong>, enseñándoles el sistema de mercadeo en red de <strong>TEDU</strong>, así como mostrarles todos los documentos y demás beneficios contemplados en el Plan de Afiliación de <strong>TEDU</strong>.</li>
            <li>Recibir toda la documentación de carácter informativo o promocional relacionada con el Plan de Afiliación de <strong>TEDU</strong>.</li>
            <li>Obtener beneficios económicos o en especie de acuerdo al Plan de Afiliación de <strong>TEDU</strong> y de acuerdo a su posición dentro de la red de mercadeo. También podrá participar de todos los incentivos que la red de mercadeo <strong>TEDU</strong> establezca de acuerdo a las metas que se fijen.</li>
            <li><strong>El ASOCIADO INDEPENDIENTE</strong> tiene derecho a recibir las capacitaciones y todas las instrucciones que sean necesarias para el desarrollo de su red de mercadeo; también tiene derecho a recibir capacitación precisa sobre los productos y el Plan de Afiliación.</li>
            <li>Un <strong>ASOCIADO INDEPENDIENTE</strong> está autorizado para realizar actividades de patrocinio y/o ayuda de otros <strong>ASOCIADOS INDEPENDIENTES</strong>.</li>
            <li>Participar en todos los programas y oportunidades que la red de mercadeo <strong>TEDU</strong> a su entera y absoluta discreción determine en cualquier momento.</li>
            <li>El <strong>ASOCIADO INDEPENDIENTE</strong> podrá obtener y usar la información personal de todos los <strong>ASOCIADOS INDEPENDIENTES</strong> que se encuentren dentro de su red de mercadeo, únicamente con el fin de desarrollar su negocio de mercadeo en red.</li>
            <li><strong>El ASOCIADO INDEPENDIENTE</strong> tiene derecho a ser informado con precisión por parte de TEDU, de las características de los productos o servicios que promocionará.</li>
            <li>El <strong>ASOCIADO INDEPENDIENTE</strong> podrá terminar en cualquier tiempo y de forma unilateral el vínculo contractual, mediante escrito dirigido a <strong>TEDU</strong>.</li>
            <li><strong>El ASOCIADO INDEPENDIENTE</strong> tiene derecho a recibir de manera oportuna e integral en cantidad y calidad, los bienes y servicios ofrecidos por <strong>TEDU<strong>.</li>

    </ol> </p>
    ';
    
    
    $quinta='<p style="text-align:justify"><strong>QUINTA: OBLIGACIONES DEL ASOCIADO INDEPENDIENTE</strong>.- Desde el momento que el <strong>ASOCIADO INDEPENDIENTE</strong> suscribe el presente contrato, tendrá las siguientes obligaciones:
    <ol type="a">
        <li>Respetar y cumplir a cabalidad las disposiciones contenidas en el presente contrato.</li>
        <li>Ceñirse a lo estipulado en el Plan de Afiliación.</li>
        <li>Utilizar el sitio web de <strong>TEDU</strong> de acuerdo a los Términos y Condiciones de Uso.</li>
        <li>Cumplir y hacer respetar el Código de Ética de <strong>TEDU</strong>.</li>
        <li>Llevar a cabo cualquier actividad de mercadeo en red respetando las políticas de comercialización contenidas en el Plan de Afiliación de <strong>TEDU</strong>.</li>
        <li>Recibir todos los beneficios del Plan de Afiliación de <strong>TEDU</strong>, de acuerdo a lo convenido.</li>
        <li>Capacitarse y ejercer las actividades de mercadeo en red.</li>
        <li>El <strong>ASOCIADO INDEPENDIENTE</strong> deberá actualizar todos los datos personales que haya registrado ante <strong>TEDU</strong> cuando dichos datos hayan cambiado, de forma oportuna.</li>
        <li>El <strong>ASOCIADO INDEPENDIENTE</strong> se compromete a realizar todas sus actividades de comercialización de manera totalmente independiente, sin que exista vínculo laboral con <strong>TEDU</strong>.</li>
    </ol>    
    </p>';

    $sexta='<p style="text-align:justify"><strong>SEXTA: DERECHOS DE TEDU.- A TEDU</strong> le asisten los siguientes derechos:
    <ol type="a">
        <li>Se reserva el derecho de tomar medidas correctivas, como son por ejemplo la suspensión temporal o terminación unilateral del presente contrato cuando el <strong>ASOCIADO INDEPENDIENTE</strong> no respete lo convenido, de acuerdo a las cláusulas estipuladas en éste contrato o en cualquiera de los documentos que lo conforman.</li>
        <li><strong>TEDU</strong> de manera autónoma, en cualquier momento y previa comunicación a sus <strong>AFILIADOS INDEPENDIENTES</strong>, podrá realizar las modificaciones que considere pertinentes al Plan de Afiliación y a los demás documentos que hacen parte del convenio.</li>
        <li>Únicamente para los fines previstos en el desarrollo de su objeto social, la red de mercadeo <strong>TEDU</strong> registrará y mantendrá una base de datos de todos los <strong>ASOCIADOS INDEPENDIENTES</strong>.</li>
        <li><strong>TEDU</strong> a su entera discreción podrá promocionar directamente sus productos o servicios con cualquier <strong>ASOCIADO INDEPENDIENTE</strong>, incluyendo propaganda, publicidad, promociones especiales para acumular puntos, beneficios, programas de fidelización, y/o cualquier estrategia de mercadeo y ventas.</li>
    </ol>  
    </p>';
    
    $septima='<p style="text-align:justify"><strong>SÉPTIMA: OBLIGACIONES DE TEDU</strong>.-
    <ol type="a">
        <li>Pagar oportunamente todos los beneficios contemplados en el Plan de Afiliación.</li>
        <li>Hacer buen uso de los datos personales proporcionados por los <strong>ASOCIADOS INDEPENDIENTES</strong>, de acuerdo con las normas vigentes en la materia.</li>
        <li>Comunicar oportunamente al <strong>ASOCIADO INDEPENDIENTE</strong> cualquier modificación que realice al Plan de Afiliación y las políticas de comercialización, los Términos y Condiciones de uso del sitio web, el Contrato de Membresía, el Contrato Multinivel y el Código de Ética de <strong>TEDU</strong> y entregar dichos documentos.</li>
        <li>Instruir al <strong>ASOCIADO INDEPENDIENTE</strong> respecto del Plan de Afiliación y las políticas de comercialización, los Términos y Condiciones de Uso del sitio web de <strong>TEDU</strong>, el Contrato de Membresía, el Contrato Multinivel y el Código de Ética de <strong>TEDU</strong> y entregar dichos documentos.</li>
        <li><strong>TEDU</strong> responderá de forma precisa las preguntas, consultas y solicitudes de aclaración formuladas por los <strong>ASOCIADOS INDEPENDIENTES</strong>, antes, durante y después de su vinculación. Las respuestas a las preguntas, consultas o solicitudes de aclaración se harán a la dirección, correo electrónico u otros medios que suministre el <strong>ASOCIADO INDEPENDIENTE</strong> que la formule, dentro de diez (10) días hábiles siguientes a su recepción.</li>
    
    </ol>
    </p>';
    
    $octava='<p style="text-align:justify"><strong>OCTAVA: AUTORIZACIÓN DE DATOS PERSONALES</strong>.- Con la suscripción del presente acuerdo de voluntades, el <strong>ASOCIADO INDEPENDIENTE</strong>, manifiesta expresamente que comprende, acepta y autoriza a <strong>TEDU</strong>, para usar y/o transferir y/o ceder la información personal contenida en el presente contrato, así como toda la información que se genere en desarrollo de sus actividades dentro de la red de mercadeo, a terceras personas, incluyendo otros <strong>ASOCIADOS INDEPENDIENTES</strong>, de acuerdo con las normas que regulan la materia.</p>';
    
    $novena='<p style="text-align:justify"><strong>NOVENA: VIGENCIA</strong>.- El presente contrato tendrá una vigencia de un (1) año desde el momento en que se genere el Código de Usuario. No obstante lo anterior, para que el contrato entre en vigencia deberá ser firmado por el <strong>ASOCIADO INDEPENDIENTE</strong>.
    <br><br>Una vez terminada la vigencia, el contrato se renovará de manera automática por igual periodo, a menos que cualquiera de las Partes decida ponerle fin.
    </p>';
    
    
    $decima='<p style="text-align:justify"><strong>DÉCIMA: TERMINACIÓN DEL CONTRATO</strong>.- <strong>El ASOCIADO INDEPENDIENTE</strong> o <strong>TEDU</strong> podrán dar por terminado el contrato de acuerdo a las siguientes situaciones:
    <ol type="a">
        <li>En cualquier momento y por cualquier motivo mediante aviso por escrito a la otra parte con treinta (30) días de anticipación.</li>
        <li>De manera inmediata sin necesidad de resolución judicial, mediante simple notificación, cuando alguna de las partes incumpla cualquiera de las estipulaciones contempladas en el presente contrato, por violación de lo contenido en el Plan de Afiliación y las políticas de comercialización, los Términos y Condiciones de Uso del sitio web de <strong>TEDU</strong> y el Código de Ética de TEDU; documentos que hacen parte integrante del presente acuerdo de voluntades.</li>
    </ol>  
    </p>';
    
       
    $onceava='<p style="text-align:justify"><strong>DÉCIMA PRIMERA: LIQUIDACIÓN DE BENEFICIOS</strong>.- Una vez se dé por terminado el Contrato, se procederá dentro de los quince (15) días hábiles siguientes, a realizar la entrega de los beneficios a que tenga derecho el <strong>ASOCIADO INDEPENDIENTE</strong> de acuerdo al Plan de Afiliación.</p>';
    $doceava='<p style="text-align:justify"><strong>DÉCIMA SEGUNDA: MODIFICACIÓN DE CLÁUSULAS Y DOCUMENTOS INTEGRANTES.- TEDU</strong> a su entera discreción podrá modificar total o parcialmente los términos del presente Contrato, así como el Plan de Afiliación y las políticas de comercialización, los Términos y Condiciones de Uso del sitio web de la empresa, el Contrato de Membresía y el Código de Ética de <strong>TEDU</strong>, notificando previamente al <strong>ASOCIADO INDEPENDIENTE</strong> para que las acepte expresamente.
    <br><br>Cualquier modificación de las cláusulas del Contrato o de los documentos que hacen parte integral del presente convenio, entrarán a regir desde el momento en que sean aceptadas expresamente por el <strong>ASOCIADO INDEPENDIENTE</strong>.
    <br><br>Si el <strong>ASOCIADO INDEPENDIENTE</strong> no acepta expresamente cualquier modificación, se entenderá que da por terminado el Contrato de manera inmediata, sin que pueda ser sujeto de ningún tipo de sanción o penalidad y siempre manteniendo los beneficios a que tenga derecho hasta ese momento, de acuerdo al Plan de Afiliación.   
    </p>';
   
    $treceava='<p style="text-align:justify"><strong>DÉCIMA TERCERA: INDEPENDENCIA</strong>.- El <strong>ASOCIADO INDEPENDIENTE</strong> entiende, y acepta que el presente Contrato no lo convierte en un empleado, trabajador, representante, agente o franquiciatario de <strong>TEDU</strong> y por tanto, no existirá en lo absoluto ningún vínculo laboral o relación de subordinación entre el <strong>ASOCIADO INDEPENDIENTE</strong> y <strong>TEDU</strong>. De acuerdo a lo anterior, todas las labores que desarrolle el <strong>ASOCIADO INDEPENDIENTE</strong> se enmarcarán dentro de las actividades propias del mercadeo en red o de mercadeo multinivel.';
    $catorceava='<p style="text-align:justify"><strong>DÉCIMA CUARTA: LEY APLICABLE</strong>.- El presente Contrato se regirá por las leyes del Ecuador, particularmente por el Código de Comercio, Código Civil y demás normas complementarias relacionadas con la materia.';
    $quinceava='<p style="text-align:justify"><strong>DÉCIMA QUINTA: CLÁUSULA COMPROMISORIA</strong>.- Cualquiera cuestión o controversia originadas en este contrato o relacionadas con él, serán resueltas por arbitraje en el Centro de Arbitraje y Mediación de la Cámara de Comercio de Guayaquil, de acuerdo con las reglas de la Ley de Arbitraje y Mediación y del Reglamento de dicho Centro. Las partes convienen además en lo siguiente: 
    <ol type="a">
        <li>Los árbitros serán seleccionados conforme lo establecido en la Ley de Arbitraje y Mediación.</li>
        <li>Las partes renuncian a la jurisdicción ordinaria, se obligan a acatar el laudo que expida el Tribunal Arbitral  y se compromete a no interponer ningún recurso en contra del mismo. </li>
        <li>Para la ejecución de  medidas cautelares el Tribunal Arbitral está facultado  para solicitar de los funcionarios públicos, judiciales, policiales y administrativos su cumplimiento, sin que sea necesario recurrir a juez ordinario alguno.</li>
        <li>El Tribunal Arbitral está integrado por un árbitro.</li>
        <li>El procedimiento arbitral será confidencial.</li>
        <li>El lugar de arbitraje será las instalaciones del Centro de Arbitraje y Mediación de la Cámara de Comercio de Guayaquil.</li>
    </ol>    
         
    ';
   
    $dieciseisava='<p style="text-align:justify"><strong>DÉCIMA SEXTA: LIMITE DE RESPONSABILIDAD.- TEDU</strong> no será responsable por los daños o perjuicios que cause el <strong>ASOCIADO INDEPENDIENTE</strong> a terceros durante el desarrollo de sus actividades de mercadeo en red, por lo tanto, el <strong>ASOCIADO INDEPENDIENTE</strong> se compromete a responder por cualquier daño, queja, demanda, gasto o indemnización que pudiera generar frente a terceros, como resultado de sus labores de mercadeo en red en contravención de lo aquí previsto o de cualquiera de los documentos que hacen parte del presente Contrato.</p>';
    $diecisieteava='<p style="text-align:justify"><strong>DÉCIMA SÉPTIMA: CONVENIO TOTAL</strong>.- El presente acuerdo de voluntades y los documentos que hacen parte integral del mismo, constituye un acuerdo total entre <strong>TEDU</strong> y el <strong>ASOCIADO INDEPENDIENTE</strong> y prevalece sobre cualquier otro acuerdo sobre el mismo objeto contractual, escrito u oral, que anteriormente se haya celebrado entre <strong>TEDU</strong> y el <strong>ASOCIADO INDEPENDIENTE</strong>.</p>';
    $dieciochoava='<p style="text-align:justify"><strong>DÉCIMA OCTAVA: NOTIFICACIONES</strong>.- Todas las comunicaciones que se deriven del presente Contrato o de sus documentos integrantes deberán hacerse por escrito a la dirección física o electrónica registrada por el <strong>ASOCIADO INDEPENDIENTE</strong>, también serán válidas las notificaciones realizadas en las publicaciones oficiales de <strong>TEDU</strong>, bien sea en revistas, folletos, guías o a través de su página web. Cualquier notificación que un <strong>ASOCIADO INDEPENDIENTE</strong> requiera hacer a <strong>TEDU</strong>, deberá realizarla únicamente a las direcciones electrónicas institucionales dispuestas para dicho efecto.</p>';
    $diecinueveava='<p style="text-align:justify"><strong>DÉCIMA NOVENA: ACEPTACIÓN DEL CONTRATO Y DOCUMENTOS INCORPORADOS.- El ASOCIADO INDEPENDIENTE</strong> reconoce haber leído y entendido completamente el presente contrato y acuerda sujetarse a él. Asimismo, reconoce haber recibido el <strong>KIT DE INICIO</strong> el cual incluye el <strong>CONTRATO MULTINIVEL</strong> y el <strong>CONTRATO DE MEMBRESÍA</strong>. En caso que exista cualquier inconsistencia entre las disposiciones de éste Contrato y los demás documentos incorporados, las partes se sujetarán a lo establecido en el presente documento.</p>';


    $final1='<p style="text-align:justify">En señal de conformidad las partes suscriben el presente contrato en dos (2) ejemplares con la misma validez jurídica cada uno, a los '.numtoletras($dia).' ('.$dia.') días del mes de '.strtoupper($meses[$mes-1]).' del año '.numtoletras($anio).' ('.$anio.')</p>';
   
    $final4='
    <table border="0">
        <tr><br><br>
            <th>EL ASOCIADO INDEPENDIENTE</th>
            <th>TEDU<br><br></th><br><br>
        </tr>
        <tr>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td style="text-align:left">Nombre:'.$paterno.' '.$materno.' '.$nombre__ususario.'<br>CC:'.$num__identificacion.'<br>Teléfono:'.$telefono.'<br>Dirección: '.$direccion.'<br>Ciudad: '.$ciudad.'<br>E-mail: '.$email.'<br></td>
            <td>
                Verónica Vanessa Rodríguez Tablas<br>
                Gerente General<br>
                TEDU S.A.S.<br>
                RUC No. 0993370883001<br>
            </td>
        </tr>
    </table>
    ';
    $texto=$preinicio.$inicio.$primera.$segunda.$tercera.$cuarta.$quinta.$sexta.$septima.$octava.$novena.$decima.$onceava.$doceava.$treceava.$catorceava.$quinceava.$dieciseisava.$diecisieteava.$dieciochoava.$diecinueveava.$final1.$final4;
    // output the HTML content
    $pdf->writeHTML($texto, true, false, true, false, '');
    // reset pointer to the last page
    $pdf->lastPage();

    // ---------------------------------------------------------

    //Close and output PDF document
    $pdf->Output('multinivel.pdf', 'D');

    //============================================================+
    // END OF FILE
    //============================================================+
}


contratomembresia($usuario,$juridica,$ruc,$num__identificacion,$natural,$paterno,$materno,$nombre__ususario,$tipo__identificacion,$nacimiento,$provincia,$ciudad,$direccion,$celular,$telefono,$email,$checkboxMailSi,$tipo__cuenta,$num__cuenta,$banco,$patrocinador__paterno,$patrocinador__materno,$nombre__patrocinador,$celular_patrocinador,$telefono__patrocinador,$codigo__patrocinador,$fecha__solicitud);
//contratomultinivel($usuario,$juridica,$ruc,$num__identificacion,$natural,$paterno,$materno,$nombre__ususario,$tipo__identificacion,$nacimiento,$provincia,$ciudad,$direccion,$celular,$telefono,$email,$checkboxMailSi,$tipo__cuenta,$num__cuenta,$banco,$patrocinador__paterno,$patrocinador__materno,$nombre__patrocinador,$celular_patrocinador,$telefono__patrocinador,$codigo__patrocinador,$fecha__solicitud);

?>