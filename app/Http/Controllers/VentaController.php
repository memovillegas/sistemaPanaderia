<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use App\Http\Requests\VentaFormRequest;
use App\Venta;
use App\ReporteVenta;
use DB;

use Carbon\Carbon;
use Response;
use Illuminate\Support\Collection;
class VentaController extends Controller
{
    public function __construct(){
        // $this->middleware('auth');
   }
    public function index(Request $request)
    {
       if($request){
         $query = trim($request->get('searchText'));
         $ventas=DB::table('venta as v')
         ->join('reporte_venta as rv','v.idVenta','=','rv.idVenta')
         ->select('v.idVenta','v.fechaHora','v.activo','v.totalVenta')
         ->where('v.fechaHora','LIKE','%'.$query.'%')
         ->orderBy('v.idVenta','desc')
         ->groupBy('v.idVenta','v.fechaHora','v.activo','v.totalVenta')
         ->paginate(7);
         return view('ventas.venta.index',["ventas"=>$ventas,"searchText"=>$query]);
        }
    }
    public function create()
    {
     $productos = DB::table('producto as pro')
            ->join('detallepedidoproducto as dpp','pro.idProducto','=','dpp.idProducto')
            ->select(DB::raw('CONCAT(pro.nombre, " - ",pro.codigo) AS producto'),'pro.idProducto','pro.stock',DB::raw('avg(dpp.precioVenta)as precioPromedio'))
            ->where('pro.estatus','=','Activo')
            ->where('pro.stock','>','0')
            ->groupBy('producto','pro.idProducto','pro.stock')
            ->get();
        return view("ventas.venta.create",["productos"=>$productos]);
    }

    public function store(VentaFormRequest $request)
    {
    
         DB::beginTransaction();
        $venta=new Venta;
        $venta->totalVenta=$request->get('totalVenta');
         $mytime = Carbon::now('America/Mexico_City');
         //toDateTimeString convertir a un formato de fecha y hora para almacenarlo en el modelo y la tabla ingreso
         $venta->fechaHora=$mytime->toDateTimeString();
         $venta->activo='1';
         $venta->save();

        //Variables de Detalle de venta
         $idProducto = $request->get('idProducto');
         $cantidad = $request->get('cantidad');
         $precioVenta = $request->get('precioVenta');

         $cont = 0;

         while($cont < count($idProducto)){
             $detalle = new ReporteVenta();
             $detalle->idVenta= $venta->idVenta; 
             $detalle->idProducto= $idProducto[$cont];
             $detalle->cantidad= $cantidad[$cont];
             $detalle->precioVenta= $precioVenta[$cont];
             $detalle->save();
             $cont=$cont+1;            
        

         DB::commit();

        }
            //Rollback anular la transaccion
           DB::rollback();
        

        return Redirect::to('ventas/venta');
    }

    public function show($id)
    {
            $venta=DB::table('venta as v')
            ->join('reporte_venta as rv','v.idVenta','=','rv.idVenta')
            ->select('v.idVenta','v.fechaHora','v.activo','v.totalVenta')
            ->where('v.idVenta','=',$id)
            ->groupBy('v.idVenta', 'v.fechaHora','v.activo','v.totalVenta')
            ->first();

        $detalles=DB::table('reporte_venta as r')
             ->join('producto as pro', 'r.idProducto','=','pro.idProducto')
             ->select('pro.nombre as producto','r.cantidad','r.precioVenta')
             ->where('r.idVenta','=',$id)
             ->get();
        return view("ventas.venta.show",["venta"=>$venta,"detalles"=>$detalles]);
    }

    public function destroy($id)
    {
        $venta=Venta::findOrFail($id);
        $venta->activo='0';
        $venta->update();
        return Redirect::to('ventas/venta');
    } 
}
