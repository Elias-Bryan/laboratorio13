@if(Session::has('Mensaje')){{
    Session::get('Mensaje')
    
}}
@endif
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
   integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<a href="{{url('productos/create')}}" class="btn btn-success">Registrar Producto</a>
<br/>
<br/>

<table class="table table-dark">

    <thead class="thead-dark">
        <tr>
            <th>#</th>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>Edad</th>
            <th>Celular</th>
            <th>Foto</th>
            
        </tr>
    </thead>

    <tbody>
    @foreach($productos as $producto)
        <tr>
            <td>{{$loop->iteration}}</td>

          
            <td>{{$producto->Nombre}}</td>
            <td>{{$producto->Categoria}}</td>
            <td>{{$producto->Cantidad}}</td>
            <td>{{$producto->Precio}}</td>
            <td>
            <img src="{{$producto->Foto}}" alt="" width="200">
            <td>
            <td>
            
            <a  class="btn btn-success" href="{{ url('/productos/'.$producto->id.'/edit')}}">
            Editar
            </a>

            
            <form method="post" action="{{ url('/productos/'.$producto->id)}}">
            {{csrf_field()}}
            {{ method_field('DELETE')}}
            <br>
            <button class="btn btn-danger" type="submit" onclick="return confirm('¿Borrar?');">Borrar</button>

            </form>
            
            </td>
        </tr>
    @endforeach
    </tbody>

</table>