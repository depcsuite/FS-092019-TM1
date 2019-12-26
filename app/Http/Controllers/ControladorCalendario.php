<?php

namespace App\Http\Controllers;

use App\Evento;
use Illuminate\Http\Request;
use App\Entidades\Sistema\Usuario;
use App\Entidades\Sistema\Patente;
//use MaddHatter\LaravelFullcalendar\Facades\Calendar;

require app_path().'/start/constants.php';
use Session;

class ControladorCalendario extends Controller
{
        public function index()
        {
        $titulo = "Calendario";
        if(Usuario::autenticado() == true)
        {
            if(!Patente::autorizarOperacion("MENUCONSULTA")) {
                $codigo = "MENUCONSULTA";
                $mensaje = "No tiene permisos para la operaci&oacute;n.";
                return view ('sistema.pagina-error', compact('titulo', 'codigo', 'mensaje'));
            } else {
                //$aTramites = \DB::table('formularios')->select('idformulario','nombre', 'descripcion', 'url')->get();
               
                $eventos = [];
                $data = Evento::all();
                if($data->count())
                 {
                    foreach ($data as $key => $value) 
                    {
                     $eventos[] = Calendar::event(
                            $value->title,
                            true,
                            new \DateTime($value->start_date),
                            new \DateTime($value->end_date.'+1 day'),
                            null,
                            // Add color
                         [
                             'color' => '#000000',
                             'textColor' => '#008000',
                         ]
                        );
                    }
                }
                $calendar = Calendar::addEvents($eventos);
               
                return view('calendario.calendario', compact('calendar'));
            }
        } else {
            return redirect('login');
        }
    }
}
