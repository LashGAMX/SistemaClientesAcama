<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AIController extends Controller
{
    //
    public function ask(Request $request)
    {
        $question = $request->input('message');

        $response = Http::post('https://rolling-region-plots-science.trycloudflare.com/api/chat', [
            'model' => 'llama3.1:8b',
            'messages' => [
                ['role' => 'user', 'content' => $question]
            ]
        ]);

        return $response->json();
    }
}
