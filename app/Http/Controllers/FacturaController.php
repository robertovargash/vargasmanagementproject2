<?php

namespace App\Http\Controllers;

use App\Models\Factura;
use App\Models\Cliente;
use App\Models\Facturaelemento;
use App\Models\Proveedor;
use App\Models\Solicitude;
use App\Models\Tproducto;
use Illuminate\Http\Request;
use PDF;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Redirect;

class FacturaController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:gestion_facturas', ['only' => ['index','store','edit','update','imprimir','cancelar','firmar','pagar']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $facturas = Factura::all();
        $clientes = Cliente::all();
        $proveedor = Proveedor::first();
        $title = "Facturas";
        return view('facturas.index', compact('facturas','title','clientes','proveedor'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $factura = Factura::create($request->all());
        $factura->numero = Factura::count();
        $factura->fecha = now();
        $factura->save();
        return redirect()->route('facturas.edit',$factura)->with('success', 'Factura insertada!!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Factura  $factura
     * @return \Illuminate\Http\Response
     */
    public function show(Factura $factura)
    {
        //
    }

    public function imprimir(Factura $factura)
    {
        //este funciona bien, pero solo en chrome
        $title = "Factura ".$factura->id;
        // return view('vales.valepdf',compact('title','vale','mercancias'));
        $importetotal = 0;
        $pdf = PDF::loadView('vales.pdf', compact('title','factura','importetotal'));
    	return $pdf->setPaper('chart','landscape')->stream('Factura'.$factura->id.'.pdf');
    }

    public function importar(Request $request, Factura $factura)
    {
        $solicitude = Solicitude::find($request->solicitud_id);
        foreach ($solicitude->solicitudproductos as $key => $sproducto) {
            $facturaitem = New Facturaelemento();
            $facturaitem->cantidad = $sproducto->cantidad;
            $facturaitem->precio = $sproducto->tproducto->valorbruto;
            $facturaitem->um = "U";
            $facturaitem->descripcion = $sproducto->tproducto->descripcion;
            $facturaitem->tipo = 0;
            $facturaitem->factura_id = $factura->id;
            $facturaitem->save();
        }
        $url = URL::route('facturas.edit',$factura) . '#cardfacturaelementos';
        return Redirect::to($url)->with('success','Elementos importados!!!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Factura  $factura
     * @return \Illuminate\Http\Response
     */
    public function edit(Factura $factura)
    {
        $title = "Facturas";
        if ($factura->estado == 0) {
            $tproductos = Tproducto::all();
            $solicitudes = Solicitude::where('solicitudes.estado','=',3)->get();
            return view('facturas.edit',compact('title','factura','tproductos','solicitudes'));
        }else {
            return redirect()->route('facturas.index')->with('error','La factura no se puede modificar, no está en proceso');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Factura  $factura
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Factura $factura)
    {
        $factura->update($request->all());
        return redirect()->route('facturas.index')->with('success','Factura modificada!!.');    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Factura  $factura
     * @return \Illuminate\Http\Response
     */
    public function destroy(Factura $factura)
    {
        //
    }

    public function cancelar(Request $request)
    {
        $factura_id=$request->input('id');
        $factura = Factura::find($factura_id);
        if ($factura->estado == 0) {
            $factura->estado = 3;
            $factura->save();
            return redirect()->route('facturas.index')->with('success','Factura cancelada');
        }else {
            return redirect()->route('facturas.index')->with('error','No se puede cancelar, no esta en proceso');
        }

    }
    public function firmar(Request $request)
    {
        $factura_id=$request->input('id');
        $factura = Factura::find($factura_id);
        if ($factura->estado == 0) {
            $factura->estado = 1;
            $factura->entrega = Auth::user()->name;
            $factura->save();
            return redirect()->route('facturas.index')->with('success','Factura confirmada');
        }else {
            return redirect()->route('facturas.index')->with('error','No se puede confirmar, no esta en proceso');
        }
    }
    public function pagar(Request $request)
    {
        $factura_id=$request->input('id');
        $factura = Factura::findOrFail($factura_id);
        if ($factura->estado == 1) {
            $factura->estado = 2;
            $factura->save();
            return redirect()->route('facturas.index')->with('success','Factura pagada');
        }else{
            return redirect()->route('facturas.index')->with('success','No se puede entregar, no esta firmada');
        }
    }
}
