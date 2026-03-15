<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Exception $e, $request) {
            $status  = 500;
            $message = 'Erro interno no servidor';

            //detalhes para análise interna e posteriormente pode ser enviado para um serviço de monitoramento
            logger()->error($e->getMessage(), ['trace' => $e->getTraceAsString()]);

            if ($e instanceof \Illuminate\Validation\ValidationException) {
                $status  = 422;
                $message = 'Erro de validação';

                return response()->json([
                    'message' => $message,
                    'errors'  => $e->errors(),
                ], $status);
            }

            if ($e instanceof \Illuminate\Auth\AuthenticationException) {
                $status  = 401;
                $message = 'Não autenticado';
            }

            if ($e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
                $status  = 404;
                $message = 'Recurso não encontrado';
            }

            // Se estamos em homologação/dev (APP_DEBUG=true), mostra detalhes
            if (config('app.debug')) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], $status);
            }

            // Em produção, resposta genérica e segura
            return response()->json([
                'success' => false,
                'message' => $message,
            ], $status);
        });
    })->create();
