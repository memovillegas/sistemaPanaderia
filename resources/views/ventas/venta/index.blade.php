@extends ('layouts.admin')
@section('contenido')
<div class="row">
    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
        <h3>Listado de Ventas<a href="venta/create"><button class="btn btn-success">Nueva Venta</button></a></h3>
        @include('ventas.venta.search')
    </div>
</div>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-condensed table-hover">
                <thead>
                    <th>Id</th>
                    <th>Fecha</th>
                    <th>Total</th>
                    <th>Estado</th>
                    <th>Opciones</th>
                    
                </thead>
                @foreach($ventas as $ven)
                <tr>
                    <td>{{$ven->idVenta}}</td>
                    <td>{{$ven->fechaHora}}</td>
                    <td>{{$ven->totalVenta}}</td>
                    <td>{{$ven->activo}}</td>
                    <td>
                        <a href="{{URL::action('VentaController@show',$ven->idVenta)}}"><button class="btn btn-primary">Detalles</button></a>
                        <a href="" data-target="#modal-delete-{{$ven->idVenta}}" data-toggle="modal"><button class="btn btn-danger">Cancelar Venta</button></a>
                    </td>
                </tr>
                @include ('ventas.venta.modal')
                @endforeach
            </table>
        </div>
        <!--El metodo render sirve para hacer la paginacion en php-->
        {{$ventas->render()}}
    </div>
</div>
@endsection