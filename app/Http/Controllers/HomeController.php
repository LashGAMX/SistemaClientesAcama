<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Cotizacion;
use App\Models\TipoCuerpo;
use App\Models\TipoReporte;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    //
    public function index(){
    $model = DB::table('viewsolicitud2')
    ->select('viewsolicitud2.*') // Selecciona TODAS las columnas de la vista principal
    ->addSelect('solicitud_puntos.Punto') // Opcional: añade solo las columnas extras necesarias de la tabla unida
    ->join('solicitud_puntos', 'viewsolicitud2.Id_solicitud', '=', 'solicitud_puntos.Id_solicitud')
    // ... (otras condiciones) ...
    ->where('Id_cliente', session('Id_cliente'))
    ->where('Padre',0)
    ->orderBy('viewsolicitud2.Id_solicitud', 'desc') 
    ->get();

        $data = array(
            'model' => $model,
        );

        return view('home',$data);
    }
    public function home2(){
        return view('home2');
    }
    public function informes(){
        return view('informes');
    }
    public function comparacion()
    { 
        // $cliente = DB::table('ViewClienteGeneral')->where('Id_cliente',session('Id_cliente'))->where('stdCliente', NULL)->get();
        $sucursal = DB::table('sucursales_cliente')->where('Id_cliente', session('Id_cliente'))->get();
        $data = array(
            'sucursal' => $sucursal,
        );
        return view('comparacion',$data);
    }
    public function seguimiento(){
        return view('seguimiento');
    }
    public function getPreInforme(Request $res)
    {
    
        $solicitud = DB::table('viewsolicitud2')->where('Folio_servicio','LIKE','%'.$res->folio.'%')->where('Id_cliente',session('Id_cliente'))->get();
        $puntoMuestreo = DB::table('solicitud_puntos')->where('Id_solicitud', $solicitud[0]->Id_solicitud)->first();
        $datoExtra = "";
        if ($solicitud->count()) {
            $model = DB::table('ViewCodigoInforme')->where('Codigo','LIKE','%'.$res->folio.'%')->where('Num_muestra',1)->whereNotIn('Id_parametro', [67, 64, 358])->get();

            $punto = DB::table('solicitud_puntos')->where('Id_solicitud',$solicitud[0]->Id_solicitud)->get();

            $cotModel = DB::table('cotizacion')->where('Id_cotizacion', $solicitud[0]->Id_cotizacion)->first();
            @$tipoReporte = DB::table('ViewDetalleCuerpos')->where('Id_detalle', $cotModel->Tipo_reporte)->first();
            @$tipoReporte2 = DB::table('tipo_cuerpo')->where('Id_tipo',$cotModel->Tipo_reporte)->first();
            
            $limitesN = array();
                $limitesC = array();
            $comparacion = array();
            $aux = 0;
            $auxCom = "----";
            $limC = 0;
            foreach ($model as $item) {
                 switch ($item->Id_parametro) {

                    
                    case 97:
                        $limC = round($item->Resultado2);
                        break;
                    case 2:
                    case 42: // salmonela
                    case 57:
                    case 59:
                        if ($item->Resultado2 == 1) {
                            $limC = "PRESENTE";
                        } else {
                            $limC = "AUSENTE";
                        }
                        break;

                    case 14:
                        switch ($solicitud[0]->Id_norma) {
                            case 1:
                            case 27:
                            case 2:
                            case 4:
                            case 9:
                            case 21:
                            case 20:
                                $limC = number_format(@$item->Resultado2, 2, ".", "");
                                break;
                            default:

                                $limC = number_format(@$item->Resultado2, 1, ".", "");
                                break;
                        }
                        break;
                    case 110:
                    case 125:
                        $limC = number_format(@$item->Resultado2, 1, ".", "");
                        break;
                    case 26:
                    case 39:
                        @$limC = number_format(@$item->Resultado2, 2, ".", "");
                        break;
                    case 16:
                        if ($item->Resultado2 < $item->Limite) {
                            $limC = "< " . $item->Limite;
                        } else {
                            $limC = number_format(@$item->Resultado2, 0, ".", "");
                        }
                        break;
                    case 34:
                    case 84:
                    case 86:
                    case 32:
                    case 111:
                    case 109:
                    case 68:
                    case 57:
                        $limC = $item->Resultado2;
                        break;

                    case 78: 
                        // case 350:
                        if ($item->Resultado2 > 0) {
                            if ($item->Resultado2 > 8) {
                                $limC = '>' . 8;
                            } else {
                                $limC = $item->Resultado;
                            }
                        } else {
                            // $limC = "<" . $item->Limite;
                            $limC = "NO DETECTABLE";
                        }
                        break;
                    case 135:
                    case 134:
                        if ($item->Resultado2 > 0) {
                            if ($item->Resultado >= 8) {
                                $limC = "> 8";
                            } else {
                                $limC = $item->Resultado;
                            }
                        } else {
                            // $limC = "<" . $item->Limite;
                            $limC = "NO DETECTABLE";
                        }
                        break;
                    case 132:
                    //case 350:
                        if ($item->Resultado2 > 0) {
                            if ($item->Resultado >= 8) {
                                $limC = "> 8";
                            } else {
                                $limC = $item->Resultado;
                            }
                        } else {
                            // $limC = "<" . $item->Limite;
                            $limC = "< 1.1";
                        }
                        break;
                    case 350:
                          $limC = $item->Resultado;
                        // break;
                        break;
                    case 133:
                        if ($item->Resultado2 > 0) {
                            if ($item->Resultado >= 8) {
                                $limC = "> 8";
                            } else {
                                $limC = $item->Resultado;
                            }
                        } else {
                            $limC = "< " . $item->Limite;
                        }
                        break;
                    case 137: //AQUI VA NETZA
                        if ($item->Resultado2 < $item->Limite) {
                            $limC = "< " . $item->Limite;
                        } else {
                            $limC = number_format(@$item->Resultado2, 2, ".", "");
                        }
                        break;
                    case 65:
                        if ($item->Resultado2 < 3) {
                            $limC = "< 3";
                        } else {
                            $limC = number_format(@$item->Resultado2, 2, ".", "");
                        }
                        break;
                    case 66:
                    case 102:
                        // case 361:
                        if ($item->Resultado2 < $item->Limite) {
                            $limC = "< " . $item->Limite;
                        } else if ($item->Resultado2 > 3) {
                            $limC = ">3";
                        } else if ($item->Resultado2 == 3) {
                            $limC = "3";
                        } else {
                            $limC = number_format($item->Resultado2, 1, ".", "");
                        }
                        break;

                    case 58:
                    case 271:
                        $limC = $item->Resultado2;
                        break;
                    // case 271:
                    //     $limC = number_format(@$item->Resultado2, 1, ".", "");
                    //     break;
                    case 5:
                    case 11:
                    case 6:
                    case 70:
                    case 12:
                    case 35:
                    case 13:
                    case 15:
                    case 9:
                    case 10:
                    case 83:
                    case 4:
                    case 3:
                    case 103:
                    case 112:
                    case 218:
                    case 253:
                    case 252:
                    case 29:
                    case 51:
                    case 58:
                    case 115:
                    case 88:
                    case 161: //DQO soluble
                    case 71:
                    case 38: //ortofosfato
                    case 36: //fosfatros
                    case 46: //ssv
                    case 137: //Coliformes totales
                    case 251:
                    case 77:
                    case 30:
                    case 90:
                    case 33:
                    case 27:
                    case 28:
                    case 43:
                    case 44:
                    case 45:
                    case 47:
                    case 48:

                        if ($item->Resultado2 < $item->Limite) {
                            $limC = "< " . $item->Limite;
                        } else {
                            $limC = number_format(@$item->Resultado2, 2, ".", "");
                        }
                        break;

                    case 98:
                    case 89:
                        if ($item->Resultado2 < $item->Limite) {
                            $limC = "< " . $item->Limite;
                        } else {
                            $limC = number_format(@$item->Resultado2, 2, ".", "");
                        }
                        // Verificar si $limC es mayor a 10 y cambiar su valor
                        if (is_numeric($limC) && $limC > 10) {
                            $limC = ">10";
                        }

                        break;
                    case 370:
                    case 372:
                        if ($item->Resultado2 < $item->Limite) {
                            $limC = "< " . $item->Limite;
                        } else {
                            $limC = number_format(@$item->Resultado2, 2, ".", "");
                        }

                        if (is_numeric($limC) && $limC > 70) {
                            $limC = ">70";
                        }
                        break;

                    // case 271:
                    // audi
                    case 52:
                    case 250:
                    case 54:
                    case 130:
                    case 95:
                    case 113:
                        if ($item->Resultado2 < $item->Limite) {
                            $limC = "< " . $item->Limite;
                        } else {
                            $limC = number_format(@$item->Resultado2, 2, ".", "");
                        }
                        break;
                    case 361:
                        if ($item->Resultado2 < $item->Limite) {
                            $limC = "< " . $item->Limite;
                        } else {
                            $limC = number_format(@$item->Resultado2, 1, ".", "");
                        }
                        break;
                    case 227:
                        if ($item->Resultado2 < $item->Limite) {
                            $limC = "< " . $item->Limite;
                        } else {
                            $limC = $item->Resultado2;
                        }
                        break;
                    case 25:
                        if ($item->Resultado2 < $item->Limite) {
                            $limC = "< " . $item->Limite;
                        } else {
                            $limC = number_format(@$item->Resultado2, 3, ".", "");
                        }
                        break;
                    // case 64:
                    case 358:
                        switch ($solicitud[0]->Id_norma) {
                            case 1:
                            case 27:
                            case 33:
                            case 9:
                                switch ($item->Resultado2) {
                                    case 499:
                                        $limC = "< 500";
                                        break;
                                    case 500:
                                        $limC = "500";
                                        break;
                                    case 1000:
                                        $limC = "1000";
                                        break;
                                    case 1500:
                                        $limC = "> 1000";
                                        break;
                                    default:
                                        $limC =  number_format(@$item->Resultado2, 2, ".", "");
                                        break;
                                }
                                break;
                            default:
                                if ($item->Resultado2 < $item->Limite) {
                                    $limC = "< " . $item->Limite;
                                } else {
                                    $limC =  number_format(@$item->Resultado2, 2, ".", "");
                                }
                                break;
                        }
                        break;
                    case 64:
                        if ($item->Resultado2 < $item->Limite) {
                            $limC = "< " . $item->Limite;
                        } else {
                            $limC =  number_format(@$item->Resultado2, 2, ".", "");
                        }
                        break;

                    case 67: //conductividad  AQUI ME QUEDE 
                        switch ($solicitud[0]->Id_norma) {
                            case 1:
                            case 27:
                                if ($solicitud[0]->Id_servicio != 3) {
                                    if ($puntoMuestreo->Condiciones != 1) {
                                        if ($item->Resultado2 >= 3500) {
                                            $limC = "> 3500";
                                        } else {
                                            $limC = round($item->Resultado2);
                                        }
                                    } else {
                                        // $limC = round($item->Resultado2);
                                        if ($item->Resultado2 >= 3500) {
                                            $limC = "> 3500";
                                        } else {
                                            $limC = round($item->Resultado2);
                                        }
                                    }
                                } else {
                                    $limC = round($item->Resultado2);
                                }
                                break;
                            default:
                                $limC = round($item->Resultado2);
                                break;
                        }
                        break;
                    case 268: // sulfuros
                        if ($item->Resultado2 < $item->Limite) {
                            $limC = "< " . $item->Limite;
                        } else {
                            // echo "<br> Dato error ".$item->Resultado2;

                            $Resultado =  floatval($item->Resultado2);

                            $limC = number_format(@$Resultado, 2, ".", "");
                        }
                        break;
                    case 210:
                    case 195:
                    case 215:
                        if ($item->Resultado2 < $item->Limite) {
                            $limC = "< " . $item->Limite;
                        } else {
                            // echo "<br> Dato error ".$item->Resultado2;

                            $Resultado =  floatval($item->Resultado2);

                            $limC = number_format(@$Resultado, 4, ".", "");
                        }
                        break;
                    default:
                        if ($item->Resultado2 < $item->Limite) {
                            $limC = "< " . $item->Limite;
                        } else {
                            // echo "<br> Dato error ".$item->Resultado2;

                            $Resultado =  floatval($item->Resultado2);

                            $limC = number_format(@$Resultado, 3, ".", "");
                        }
                        break;
                }
                  switch ($solicitud[0]->Id_norma) { 
                    case 1:
                        @$limNo = DB::table('limitepnorma_001')->where('Id_categoria', $tipoReporte->Id_detalle)->where('Id_parametro', $item->Id_parametro)->get();
                        if ($limNo->count()) {
                            $aux = $limNo[0]->Prom_Dmax;
                        } else {
                            $aux = "N/A";
                        }
                        //comentarios
                        break;
                    case 2:
                        $limNo = DB::table('limitepnorma_002')->where('Id_parametro', $item->Id_parametro)->get();
                        if ($limNo->count()) {
                            switch (@$solicitud[0]->Id_promedio) {
                                case 1:
                                    $aux = $limNo[0]->Instantaneo;
                                    break;
                                case 2:
                                    $aux = $limNo[0]->PromM;
                                    break;
                                case 3:
                                    $aux = $limNo[0]->PromD;
                                    break;
                                default:
                                    $aux = $limNo[0]->PromD;
                                    break;
                            }
                    
                        } else {
                            $aux = "N/A";
                            $auxCon = "N/A";
                        }
                        break;
                    case 30:
                        $limNo = DB::table('limitepnorma_127')->where('Id_parametro', $item->Id_parametro)->get();
                        if ($limNo->count()) {
                            if ($limNo[0]->Per_min != "") {
                                $aux = $limNo[0]->Per_min . " - " . $limNo[0]->Per_max;
                            } else {
                                $aux = $limNo[0]->Per_max;
                            }
                        } else {
                            $aux = "N/A";
                        }
                        break;
                    case 7:
                        $limNo = DB::table('limitepnorma_201')->where('Id_parametro', $item->Id_parametro)->get();
                        if ($limNo->count()) {
                            if ($limNo[0]->Per_max != "") {
                                $aux = $limNo[0]->Per_max;
                            } else {
                                $aux = "N/A";
                            }
                        } else {
                            $aux = "N/A";
                        }
                        break;
                    case 27:
                        if($solicitud[0]->Siralab == 1){
                            $limNo = DB::table('limite001_2021')->where('Id_parametro', $item->Id_parametro)->where('Id_categoria', 1)->get();
                        }else{
                            $limNo = DB::table('limite001_2021')->where('Id_parametro', $item->Id_parametro)->where('Id_categoria', $solicitud[0]->Id_reporte2)->get();
                        }
                        if ($limNo->count()) {
                            $aux = $limNo[0]->Pd;
                        } else {
                            $aux = "N/A";
                        }
                        break;
                    case 365:
                        break;
                    default:

                        break;
                }
                array_push($limitesN, $aux);
                array_push($limitesC, $limC);
                array_push($comparacion, $auxCom);
            }
        
        }else{
            $model = array();
        }

        $data = array(
            'limitesC' => $limitesC,
            'punto' => $punto[0],
            'comparacion' => $comparacion,
            'solicitud' => $solicitud[0],
            'model' => $model,
            'limitesN' => $limitesN,
        );
        return response()->json($data);
    }
    public function getInforme(Request $res)
    {
        
    }
    public function getPunto(Request $res)
    {
        $sw = false;
        $punto1 = DB::table('puntos_muestreo')->where('Id_sucursal', $res->id)->get();
        $punto2 = DB::table('puntos_muestreogen')->where('Id_sucursal', $res->id)->get();
       

        $data = array(
            'punto1' => $punto1,
            'punto2' => $punto2,
        );
        return response()->json($data);
    }
public function getComparar(Request $res)
{
    $folios = array();
    $parametros = array();
    $resultados = array();
    $solicitud = DB::table('viewsolicitud2')
                    ->where('Id_sucursal', $res->id)
                    ->where('Id_cliente', session('Id_cliente'))
                    ->where('Padre', 0)
                    ->where('Cancelado', 0)
                    ->whereBetween('Fecha_muestreo', [$res->fechaIni, $res->fechaFin])
                    ->get();

    foreach ($solicitud as $item) {
        $puntosTemp = DB::table('solicitud_puntos')
            ->where('Id_solicitud', $item->Id_solicitud)
            ->where('Id_muestreo', $res->punto)
            ->get();
        
        if ($puntosTemp->count()) {
            // Guardamos el folio en el array de folios
            array_push($folios, $item->Folio_servicio);

            // Obtenemos los parámetros de esa solicitud
            $tempParametros = DB::table('ViewCodigoInforme')
                                ->where('Id_solicitud', $item->Id_solicitud)
                                ->where('Num_muestra', 1)
                                ->whereNotIn('Id_parametro', [67, 64, 358])
                                ->get();
            
            // Y guardamos el array de parámetros en el array final
            array_push($parametros, $tempParametros);
        }
    }
    
    $data = array(
        'folios' => $folios,
        'solicitud' => $solicitud,
        'parametros' => $parametros,
    );

    return response()->json($data);
}
  public function ask(Request $request)
    {
        $prompt = $request->input('prompt');

        $ollama = Http::timeout(300)->post('http://127.0.0.1:11434/api/chat', [
            'model' => 'deepseek-r1',
            'messages' => [
                ['role' => 'user', 'content' => $prompt]
            ]
        ]);

        return response()->json([
            'status' => 'ok',
            'response' => $ollama['message']['content'] ?? 'Sin respuesta'
        ]);
    }
        public function getSeguimiento(Request $res)
    {
        $model = DB::table('ViewSolicitud2')->where('Id_solicitud',$res->id)->first();
        $proceso = DB::table('ViewProcesoAnalisis')->where('Id_solicitud',$res->id)->first();
        $campo = DB::table('solicitudes_generadas')->where('Id_solicitud',$res->id)->first();
        $informe = DB::table('impresion_informe')->where('Id_solicitud',$res->id)->get();
        $codigo = DB::table('ViewCodigoRecepcion')->where('Id_solicitud',$res->id)->get();
        $data = array(
            'codigo' => $codigo,
            'proceso' => $proceso,
            'campo' => $campo,
            'informe' => $informe,
            'model' => $model,
        );
        return response()->json($data);
    }
    public function getbuscarFolio(Request $res)
    {
        $model = DB::table('solicitudes')->where('Folio_servicio','LIKE','%'.$res->folio.'%')->where('Padre',1)->first();
        $puntos = DB::table('solicitud_puntos')->where('Id_solPadre',$model->Id_solicitud)->get();
        $data = array(
            'puntos' => $puntos,
            'model' => $model,
        );
        return response()->json($data);
    }
}
