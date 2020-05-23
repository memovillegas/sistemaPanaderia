<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use DB;
class DashBoardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
         /*Compras por mes*/
        $comprasmes=DB::select('SELECT monthname(pp.fechaHora) as mes, sum(dpp.cantidad*dpp.precioCompra) as totalmes from pedidoproducto pp inner join detallepedidoproducto dpp on pp.idPedidoProducto=dpp.idPedidoProducto where pp.estatus="Activo" group by monthname(pp.fechaHora) order by month(pp.fechaHora) desc limit 12');
        /*Ventas por mes*/
        $ventasmes=DB::select('SELECT monthname(v.fechaHora) as mes, sum(v.totalVenta) as totalmes from venta v where v.activo=1 group by monthname(v.fechaHora) order by month(v.fechaHora) desc limit 12');
          /*Ventas por dia*/
        $ventasdia=DB::select('SELECT DATE(v.fechaHora) as dia, sum(v.totalVenta) as totaldia from venta v where v.activo=1 group by v.fechaHora order by day(v.fechaHora) desc limit 15');
          /*Producto mas vendido*/
        $productosvendidos=DB::select('SELECT p.nombre as producto,sum(rv.cantidad) as cantidad from producto p inner join reporte_venta rv on p.idProducto=rv.idProducto inner join venta v on rv.idVenta=v.idVenta where v.activo=1 and year(v.fechaHora)=year(curdate()) group by p.nombre order by sum(rv.cantidad) desc limit 10');

        $totales=DB::select('SELECT (select ifnull(sum(dpp.cantidad*dpp.precioCompra),0) from pedidoproducto pp inner join detallepedidoproducto dpp on pp.idPedidoProducto=dpp.idPedidoProducto where DATE(pp.fechaHora)=curdate() and pp.estatus="Activo") as totalPedidos, (select ifnull(sum(v.totalVenta),0) from venta v where DATE(v.fechaHora)=curdate() and v.activo=1) as totalventa');

            return view('dashboard',["comprasmes"=>$comprasmes,"ventasmes"=>$ventasmes,"ventasdia"=>$ventasdia,"productosvendidos"=>$productosvendidos,"totales"=>$totales]);
    }
}
