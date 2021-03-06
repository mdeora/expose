<?php

namespace App\Client\Http\Controllers;

use App\Http\Controllers\Controller;
use GuzzleHttp\Psr7\Response;
use function GuzzleHttp\Psr7\str;
use Illuminate\Http\Request;
use Ratchet\ConnectionInterface;

class CreateTunnelController extends Controller
{
    protected $keepConnectionOpen = true;

    public function handle(Request $request, ConnectionInterface $httpConnection)
    {
        app('expose.client')
            ->connectToServer($request->get('url'), $request->get('subdomain', ''), config('expose.auth_token'))
            ->then(function ($data) use ($httpConnection) {
                $httpConnection->send(respond_json($data));
                $httpConnection->close();
            }, function () use ($httpConnection) {
                $httpConnection->send(str(new Response(500)));
                $httpConnection->close();
            });
    }
}
