@extends ('layouts.admin')
@section('contenido')
   <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <h3>Nueva Venta</h3>
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
            {!!Form::open(array('url'=>'ventas/venta','method'=>'POST','autocomplete'=>'off'))!!}
            {!!Form::token()!!}
        <div class="row">
            <div class="pane panel-prmary">
                <div class="panel-body">
                    <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
                        <div class="form-group">
                            <label>Producto</label>
                            <select name="pidProducto" class="form-control selectpicker" id="pidProducto" data-live-search="true">
                                @foreach($productos as $producto)
                                <option value="{{$producto->idProducto}}_{{$producto->stock}}_{{$producto->precioPromedio}}">
                                    {{$producto->producto}}
                                </option>
                                @endforeach
                            </select>
                        </div>                        
                    </div>

                    <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
                        <div class="form-group">
                            <label for="cantidad">Cantidad</label>
                            <input type="number" name="pcantidad" id="pcantidad" class="form-control" placeholder="cantidad:"/>                
                        </div>
                    </div>
                    <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
                        <div class="form-group">
                            <label for="stock">Stock</label>
                            <input type="number" disabled name="pstock" id="pstock" class="form-control" placeholder="Stock"/>                
                        </div>
                    </div>
                    <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
                        <div class="form-group">
                            <label for="precioVenta">Precio Venta</label>
                            <input type="number" disabled name="pprecioVenta" id="pprecioVenta" class="form-control" placeholder="P. Venta"/> 
                        </div>
                    </div>
                    <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
                        <div class="form-group">                            
                            <button type="button" name="bt_add" id="bt_add" class="btn btn-primary">
                                Agregar
                            </button> 
                        </div>
                    </div>
                    <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                        <table id="detalles" class="table table-striped table-bordered table-condensed table-hover">
                            <thead style="background-color: #A9D0F5;">
                                <th>Opciones</th>
                                <th>Productos</th>
                                <th>Cantidad</th>
                                <th>Precio Venta</th>
                                <th>Subtotal</th>
                            </thead>
                            <tfoot>
                                <th>TOTAL</th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th><h4 id="total">$ 0.00</h4><input type="hidden" name="totalVenta" id="totalVenta"></th>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12" id="guardar">
                <div class="form-group">
                    <input name="_token" value="{{csrf_token()}}" type="hidden"/>
                    <button class="btn btn-primary" type="submit">Guardar</button>
                    <button class="btn btn-danger" type="reset">Cancelar</button>
                </div>
            </div>
        </div>        
{!!Form::close()!!} 
@push('scripts')
<script>
  
    $(document).ready(function(){
       $("#bt_add").click(function(){
            agregar();
       });
    });
    var cont=0;
    var total=0;
    var subtotal=[];
    $("#guardar").hide();
    $("#pidProducto").change(mostrarValores);

    function mostrarValores()
    {
        datosProducto=document.getElementById('pidProducto').value.split('_');
        $("#pprecioVenta").val(datosProducto[2]);
        $("#pstock").val(datosProducto[1]);
    }

    function agregar(){
        datosProducto=document.getElementById('pidProducto').value.split('_');
        
        idProducto = datosProducto[0];
        producto = $("#pidProducto option:selected").text();
        cantidad = parseInt($("#pcantidad").val());
        precioVenta = $("#pprecioVenta").val();
        stock = $("#pstock").val();
        
        if(idProducto!="" && cantidad!="" && cantidad>0 && precioVenta!=""){
            if(stock >=cantidad){
            subtotal[cont] = (cantidad*precioVenta);
            total = total+subtotal[cont];
            
            var fila = '<tr class="selected" id="fila'+cont+'"><td><button type="button" class="btn btn-warning" onclick="eliminar('+cont+');">x</button></td><td><input type="hidden" name="idProducto[]" value="'+idProducto+'">'+producto+'</td><td><input type="number" name="cantidad[]" value="'+cantidad+'"></td><td><input type="number" name="precioVenta[]" value="'+precioVenta+'"></td><td>'+subtotal[cont]+'</td></tr>';
            cont++;
            limpiar();
            $("#total").html("$: "+total);
            $("#totalVenta").val(total);
            evaluar();
            $('#detalles').append(fila);
        }else{
            alert('No hay producto suficiente en el almacen');
        }
        }else{
            alert("Error al ingresar el detalle de la venta, revise los datos del producto");
        }
    }
    
    function limpiar(){
        $("#pcantidad").val("");
        $("#pprecioVenta").val("");
    }
    function evaluar(){
        if(total>0){
            $("#guardar").show();
        }else{
            $("#guardar").hide();
        }
    }
    function eliminar(index){
        total = total-subtotal[index];
        $("#total").html("S/."+total);
        $("#totalVenta").val(total);
        $("#fila"+index).remove();
        evaluar();
    }
</script>

@endpush
@stop
