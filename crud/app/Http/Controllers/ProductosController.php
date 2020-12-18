<?php

namespace App\Http\Controllers;

use App\Models\Productos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use JD\Cloudder\Facades\Cloudder;

class ProductosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $datos['productos']=Productos::paginate(5);
        return view('productos.index',$datos);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('productos.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        //$datosProductos=request()->all();

        $datosProductos=request()->except('_token');

        //------------Cloudinary
        $this->validate($request,[
            'Foto'=>'required|mimes:jpeg,bmp,jpg,png|between:1, 6000',
        ]);

        $image = $request->file('Foto');
        $name = $request->file('Foto')->getClientOriginalName();
        $image_name = $request->file('Foto')->getRealPath();;
        Cloudder::upload($image_name, null);
        list($width, $height) = getimagesize($image_name);
        $image_url= Cloudder::show(Cloudder::getPublicId(), ["width" => $width, "height"=>$height]);
        //temporal la de abajo--> obtiene el nombre de la imagen
        $image_name_un= Cloudder::getPublicId();

        //temporal para una consulta de un dato
        //$valoress = contactos::find('Nombre')->where('id','17')->first();
        $valoress = Productos::where('id',5)
        ->firstOr(['Nombre_foto'],function(){});
        
        //save to uploads directory
        $image->move(public_path("uploads"), $name);
        
        //if ($request->hasfile('Foto')){

        //    $datosProductos['Foto']=$request->file('Foto')->store('uploads','public');
        //}

        //Productos::insert($datosProductos);

        //obetner valores individualmente
        $nombre_nuevo = $request->input('Nombre');
        $categoria_nuevo = $request->input('Categoria');
        $cantidad_nuevo = $request->input('Cantidad');
        $precio_nuevo = $request->input('Precio');
            //insertar
            Productos::insert([
        'Nombre'  =>$nombre_nuevo ,
        'Categoria' => $categoria_nuevo,
        'Cantidad' =>$cantidad_nuevo ,
        'Precio' => $precio_nuevo, 
        'Foto' => $image_url,
        'Nombre_foto'=>$image_name_un]);
        //return response()->json($datosProductos);
        return redirect('productos')->with('Mensaje','Producto registrado exitosamente');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Productos  $productos
     * @return \Illuminate\Http\Response
     */
    public function show(Productos $productos)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Productos  $productos
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $producto=Productos::findOrFail($id);

        return view('productos.edit',compact('producto'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Productos  $productos
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $datosProductos=request()->except(['_token','_method']);

        //---------------img
        $this->validate($request,[
            'Foto'=>'required|mimes:jpeg,bmp,jpg,png|between:1, 6000',
        ]);
        $image = $request->file('Foto');
        $name = $request->file('Foto')->getClientOriginalName();
        $image_name = $request->file('Foto')->getRealPath();;
        Cloudder::upload($image_name, null);
        list($width, $height) = getimagesize($image_name);
        $image_url= Cloudder::show(Cloudder::getPublicId(), ["width" => $width, "height"=>$height]);
        $image_name_un= Cloudder::getPublicId();
        $image->move(public_path("uploads"), $name);    
        $nombre_nuevo = $request->input('Nombre');
        $categoria_nuevo = $request->input('Categoria');
        $cantidad_nuevo = $request->input('Cantidad');
        $precio_nuevo = $request->input('Precio');

        //elimina el dato de cloudinary--------------------------
        $valoress = Productos::where('id',$id)
        ->firstOr(['Nombre_foto'],function(){});
        //da formato
        $nombre_foto =$valoress->Nombre_foto;
        Cloudder::destroyImages($nombre_foto);

        //elimina----------------------------------------------

        Productos::where('id','=',$id)->update([
            'Nombre'  =>$nombre_nuevo ,
            'Categoria' => $categoria_nuevo,
            'Cantidad' =>$cantidad_nuevo ,
            'Precio' => $precio_nuevo, 
            'Foto' => $image_url,
            'Nombre_foto'=>$image_name_un]);


        //if ($request->hasfile('Foto')){

        //    $producto=Productos::findOrFail($id);

        //    Storage::delete('public/'.$producto->Foto);

        //    $datosProductos['Foto']=$request->file('Foto')->store('uploads','public');
        //}

        //Productos::where('id','=',$id)->update($datosProductos);

        $producto=Productos::findOrFail($id);
        //return view('productos.edit',compact('producto'));

        return redirect('productos')->with('Mensaje','Producto modificado exitosamente');


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Productos  $productos
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //

            //recoge el valor de imagen
        $valoress = Productos::where('id',$id)
        ->firstOr(['Nombre_foto'],function(){});
        //da formato
        $nombre_foto =$valoress->Nombre_foto;
        //eliminacloud
        Cloudder::destroyImages($nombre_foto);

        //elimina DB
        Productos::destroy($id);

       // $producto=Productos::findOrFail($id);

        //if(Storage::delete('public/'.$producto->Foto)){
        //    Productos::destroy($id);
        //};

        return redirect('productos')->with('Mensaje','Producto eliminado exitosamente');;
    }
}
