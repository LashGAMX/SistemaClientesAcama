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

        $response = Http::post('http://127.0.0.1:11434/api/chat', [
            'model' => 'deepseek-r1',
            'messages' => [
                ['role' => 'user', 'content' => $question]
            ]
        ]);

        return $response->json();
    }
}
