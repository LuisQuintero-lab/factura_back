<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Client;

class Invoice extends Model
{
    use HasFactory;

    //Variables para usar el api de factura.com
    private $apiUrl = 'https://sandbox.factura.com/api/';
    private $apiKey = 'JDJ5JDEwJDhhOVM4bzhXZWlSaFBoMVlUNmJuWHVuNnVQczFaZGlaQlVIakd3U3FuM1g0NG1iWVNtWTQu';
    private $apiSecret = 'JDJ5JDEwJGM1S05VVzA2dzhyOU9oSDRNVlBOei5CZ3BRZmpIVlpqUFBZc1ZiWDEzV1BRWm9tbll0eHEy';
    private $fPlugin = '9d4095c8f7ed5785cb14c0e3b033eeb8252416ed';

    public function getClient()
    {
        return new Client ([
            'base_uri' => $this->apiUrl,
            'headers' => [
                'Content-Type' => 'application/json',
                'F-PLUGIN' => $this->fPlugin,
                'F-Api-Key' => $this->apiKey,
                'F-Secret-Key' => $this->apiSecret,
            ],
            'verify' => false, // desactiva la verificacion SSL porque no deja conectar :(
        ]);
    }

    public function getInvoices($body)
    {
        $client = $this->getClient();

        $response = $client->get('v4/cfdi/list', [
            'json' => $body
        ]);

        $data = json_decode($response->getBody(), true);

        $listaSeries = [
            [
                "SerieName" => "FA",
                "SerieType" => "factura",
                "SerieDescription" => "Factura",
            ],
            [
                "SerieName" => "AB",
                "SerieType" => "factura",
                "SerieDescription" => "Factura",
            ],
            [
                "SerieName" => "PA",
                "SerieType" => "pago",
                "SerieDescription" => "Complemento Pago",
            ],
            [
                "SerieName" => "CI",
                "SerieType" => "carta_porte_ingreso",
                "SerieDescription" => "Carta Porte de Ingreso",
            ],
            [
                "SerieName" => "P",
                "SerieType" => "arrendamiento",
                "SerieDescription" => "Recibo de Arrendamiento",
            ],
            [
                "SerieName" => "COP",
                "SerieType" => "pago",
                "SerieDescription" => "Complemento Pago",
            ],
            [
                "SerieName" => "NDB",
                "SerieType" => "nota_debito",
                "SerieDescription" => "Nota de Débito",
            ],
            [
                "SerieName" => "RT",
                "SerieType" => "retencion",
                "SerieDescription" => "Retención",
            ],
            [
                "SerieName" => "NC",
                "SerieType" => "nota_cargo",
                "SerieDescription" => "Nota de Cargo",
            ],
            [
                "SerieName" => "FH",
                "SerieType" => "factura_hotel",
                "SerieDescription" => "Factura de Hotel",
            ],
            [
                "SerieName" => "NOM",
                "SerieType" => "nomina",
                "SerieDescription" => "Nómina",
            ],
            [
                "SerieName" => "N",
                "SerieType" => "nota_credito",
                "SerieDescription" => "Nota de Crédito",
            ],
            [
                "SerieName" => "C",
                "SerieType" => "carta_porte",
                "SerieDescription" => "Carta Porte",
            ],
            [
                "SerieName" => "F",
                "SerieType" => "factura",
                "SerieDescription" => "Factura",
            ],
        ];

        // Recorremos los elementos en el nodo data y agregamos los campos adicionales a la respuesta
        foreach ($data['data'] as &$invoice) {
            // Obtenemos el valor en Folio
            $folio = $invoice['Folio'];
            $serieDescription = null;

            // Dvidimos el valor de el campo en 2 para Serie y FolioNumero
            $parts = explode(' ', $folio);
            $serie = $parts[0];
            $folioNumero = $parts[1];

            // Ciclo para buscar la serie y obtener que tipo de documento es
            foreach ($listaSeries as $serieInfo) {
                if($serieInfo['SerieName'] === $serie) {
                    $serieDescription = $serieInfo['SerieDescription'];
                    break;
                }
            }

            // Agregamos los campos nuevos al json
            $invoice['Serie'] = $serie;
            $invoice['FolioNumero'] = $folioNumero;
            $invoice['TipoDocumento'] = $serieDescription;
        }

        return $data;
    }

    public function detailInvoice($uid)
    {
        $client = $this->getClient();

        $response = $client->get('v4/cfdi/uid/' . $uid);

        $data = json_decode($response->getBody(), true);

        return $data;
    }

    public function emailInvoice($uid)
    {
        $client = $this->getClient();

        $response = $client->post('v4/cfdi40/' . $uid . '/email/');

        $data = json_decode($response->getBody(), true);

        return $data;
    }

    public function cancelInvoice($uid, $body)
    {
        $client = $this->getClient();

        $response = $client->post('v4/cfdi40/' . $uid . '/cancel', [
            'json' => $body
        ]);

        $data = json_decode($response->getBody(), true);

        return $data;
    }

    public function storeInvoice($body)
    {
        $client = $this->getClient();
        $response = $client->post('v4/cfdi40/create', [
            'json' => $body
        ]);

        $data = json_decode($response->getBody(), true);

        return $data;
    }

    public function getClients()
    {
        $client = $this->getClient();
        $response = $client->get('v1/clients');

        $data = json_decode($response->getBody(), true);

        return $data;
    }

    public function getCfdiUse()
    {
        $client = $this->getClient();
        $response = $client->get('v4/catalogo/UsoCfdi');

        $data = json_decode($response->getBody(), true);

        return $data;
    }

    public function getSeries()
    {
        $client = $this->getClient();
        $response = $client->get('v4/series');

        $data = json_decode($response->getBody(), true);

        return $data;
    }

    public function getPayWay()
    {
        $client = $this->getClient();
        $response = $client->get('v3/catalogo/FormaPago');

        $data = json_decode($response->getBody(), true);

        return $data;
    }




}
