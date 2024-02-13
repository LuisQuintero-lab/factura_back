<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Invoice $invoice, Request $request)
    {
        $body = [
            'year'=> '',
            'rfc'=> '',
            'type_document'=> '',
            'page'=> 1,
            'per_page'=> 15
        ];
        
        $apiData = $invoice->getInvoices($request->all());

        return response()->json($apiData);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Invoice $invoice, Request $request)
    {
        // Validar los datos del request
        $request->validate([
            'Receptor.UID' => 'required|string',
            'TipoDocumento' => 'required|string',
            'Conceptos' => 'required|array',
            'Conceptos.*.ClaveProdServ' => 'required|string',
            'Conceptos.*.Cantidad' => 'required|numeric',
            'Conceptos.*.ClaveUnidad' => 'required|string',
            'Conceptos.*.ValorUnitario' => 'required|numeric',
            'Conceptos.*.Descripcion' => 'required|string',
            'UsoCFDI' => 'required|string',
            'Serie' => 'required|numeric',
            'FormaPago' => 'required|string',
            'MetodoPago' => 'required|string',
            'Moneda' => 'required|string',
            'EnviarCorreo' => 'required|boolean',
        ]);
        /* Esto ya no tiene sentido seria reasignar las variables de nuevo a el mismo nombre porque las envio ya formateadas
        // Crear un nuevo invoice con los datos del request
        $invoiceData = [
        'receptor_uid' => $request->input('Receptor')['UID'],
        'tipo_documento' => $request->input('TipoDocumento'),
        'uso_cfdi' => $request->input('UsoCFDI'),
        'serie' => $request->input('Serie'),
        'forma_pago' => $request->input('FormaPago'),
        'metodo_pago' => $request->input('MetodoPago'),
        'moneda' => $request->input('Moneda'),
        'enviar_correo' => $request->input('EnviarCorreo'),

        ];

        // Crear y asociar los conceptos al invoice
        $conceptos = [];
        foreach ($request->input('Conceptos') as $conceptoData) {
            $concepto = [
                'clave_prod_serv' => $conceptoData['ClaveProdServ'],
                'cantidad' => $conceptoData['Cantidad'],
                'clave_unidad' => $conceptoData['ClaveUnidad'],
                'unidad' => $conceptoData['Unidad'],
                'valor_unitario' => $conceptoData['ValorUnitario'],
                'descripcion' => $conceptoData['Descripcion'],
                'impuestos' => $conceptoData['Impuestos'] ?? [],
            ];

            $conceptos[] = $concepto;
        }
    
        $invoiceData['conceptos'] = $conceptos;
        */
    // Retornar una respuesta indicando que el invoice se creó exitosamente
    $apiData = $invoice->storeInvoice($request->all());
    return response()->json($apiData);

    //return response()->json(['message' => 'Invoice creado exitosamente'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice, $uid)
    {
        $apiData = $invoice->detailInvoice($uid);

        return response()->json($apiData);
    }

    /**
     * Cancel the specified resource from the list.
     */
    public function cancel(Invoice $invoice, Request $request, $uid)
    {

        $apiData = $invoice->cancelInvoice($uid, $request->all());

        return response()->json($apiData);
    }

    /**
     * Send the specified resource via email.
     */
    public function send(Invoice $invoice, $uid)
    {
        $apiData = $invoice->emailInvoice($uid);

        return response()->json($apiData);
    }

    public function clients(Invoice $invoice)
    {
        $apiData = $invoice->getClients();

        return response()->json($apiData);
    }

    public function cfdiUse(Invoice $invoice)
    {
        $apiData = $invoice->getCfdiUse();

        return response()->json($apiData);
    }

    public function series(Invoice $invoice)
    {
        $apiData = $invoice->getSeries();

        return response()->json($apiData);
    }

    public function payWay(Invoice $invoice)
    {
        $apiData = $invoice->getPayWay();

        return response()->json($apiData);
    }

}
