<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use ZipArchive;
use Dompdf\Options;
use Ilovepdf\Ilovepdf;
use GuzzleHttp\Client;



use Illuminate\Http\Request;

class PdfController extends Controller
{
    public function dividePDF(Request $request)
    {   
        $pdfRequest = $request->file('pdf');

        $apiKey = env('ILOVEPDF_API_KEY');
        $client = new Client();

        // Substitua 'file_url.pdf' pelo URL do PDF que você deseja dividir.
        $response = $client->post("https://api.ilovepdf.com/v1/start/split?tasks=[
            {
                'tool': 'split',
                'params': {
                    'file': $pdfRequest
                }
            }
        ]", [
            'headers' => [
                'Authorization' => "Bearer $apiKey",
            ]
        ]);

        $data = json_decode($response->getBody(), true);

    }
}
