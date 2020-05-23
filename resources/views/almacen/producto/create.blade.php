@extends ('layouts.admin')
@section('contenido')
   <div class="row">
       <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
           <h3>Nuevo Producto</h3>
           @if(count($errors)>0)
           <div class="alert alert-danger">
               <ul>
                   @foreach($errors->all() as $error)
                   <li>{{$error}}</li>
                   @endforeach
               </ul>
           </div>
           @endif
        </div>
    </div>
            <!--'files'=>true:Sirve para decirle al servidor que estamos preparados para recibir imagenes
            en el formulario-->
           {!!Form::open(array('url'=>'almacen/producto','method'=>'POST','autocomplete'=>'off','files'=>true))!!}
           {!!Form::token()!!}
        <div class="row">
        <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
            <div class="form-group">
                <label for="nombre">Nombre artículo</label>
                <input type="text" name="nombre" required value="{{old('nombre')}}" class="form-control" placeholder="Nombre:">
            </div>
        </div>

        <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
            <div class="form-group">
                <label for="">Categoria</label>
                <select name="idTipoProducto" class="form-control" id="">
                @foreach($tipoProductos as $tp)
                <option value="{{$tp->idTipoProducto}}">{{$tp->tipo}}</option>
                @endforeach
                </select>
            </div>
        </div>

        <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
            <div class="form-group">
                    <label for="presentacion">Presentación</label>
                    <input type="text" name="presentacion"  value="{{old('presentacion')}}" class="form-control" placeholder="Presentación del artículo">
                </div>
        </div> 

        
        <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
            <div class="form-group">
                    <label for="stock">Stock</label>
                    <input type="text" name="stock" value="{{old('stock')}}" class="form-control" placeholder="Stock del artículo:" disabled>
            </div>
        </div>

        <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
            <div class="form-group">
                    <label for="imagen">Imagen</label>
                    <input type="file" name="imagen" class="form-control">
            </div>
        </div>

        <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
            <div class="form-group">
                    <label for="codigo">Código</label>
                    <input type="text" name="codigo" id="codigobar" required value="{{old('codigo')}}" class="form-control" placeholder="Codigo del artículo:">
                    <br>
                    <button class="btn btn-success" type="button" onclick="generarBarcode()">Generar</button>
                <button class="btn btn-info" onclick="imprimir()"type="button">imprimir</button>
                <div id="print">
                    <svg id="barcode"></svg>
                </div>
            </div>
        </div>

        </div>
        <div class="col-lg-6 col-lg-6 col-sm-6 col-md-6 col-xs-12">
            <div class="form-group">
                <br>
                    <button class="btn btn-primary" type="submit">Guardar</button>
                    <button class="btn btn-danger" type="reset">Cancelar</button>
                </br>
            </div>
        </div>
    </div>

           
{!!Form::close()!!}
@push ('scripts')
<script src="{{asset('js/JsBarcode.all.min.js')}}"></script>
<script src="{{asset('js/jquery.PrintArea.js')}}"></script>
<script>
function generarBarcode()
{   
    codigo=$("#codigobar").val();
    JsBarcode("#barcode", codigo, {
    format: "pharmacode",
    font: "OCRB",
    fontSize: 18,
    textMargin: 0
    });
}
$('#liAlmacen').addClass("treeview active");
$('#liArticulos').addClass("active");


//Código para imprimir el svg
function imprimir()
{
    $("#print").printArea();
}

</script>
@endpush
       
@stop