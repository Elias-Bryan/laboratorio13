

<label for="Nombre">{{'Nombres'}}</label>
<input type="text" name="Nombre" id="Nombre" value="{{ isset($producto->Nombre)?$producto->Nombre:''}}">
</br>

<label for="Categoria">{{'Apellidos'}}</label>
<input type="text" name="Categoria" id="Categoria" value="{{ isset($producto->Categoria)?$producto->Categoria:''}}">
</br>

<label for="Cantidad">{{'Edad'}}</label>
<input type="text" name="Cantidad" id="Cantidad" value="{{ isset($producto->Cantidad)?$producto->Cantidad:''}}">
</br>

<label for="Precio">{{'Celular'}}</label>
<input type="text" name="Precio" id="Precio" value="{{ isset($producto->Precio)?$producto->Precio:''}}">
</br>

<label for="Foto">{{'Foto'}}</label>
@if(isset($producto))
<br/>
<img src="{{ asset('storage').'/'. $producto->Foto}}" class="img-thumbnail img-fluid" alt="" width="100">
<br/>
@endif
<input type="file" name="Foto" id="Foto" value="">
</br>

<input type="submit" value="{{ $Modo=='crear' ? 'Agregar' :'Modificar'}}">

<a href="{{url('productos')}}">Regresar</a>