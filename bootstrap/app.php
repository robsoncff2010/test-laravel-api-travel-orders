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
        $exceptions->render(function (\Throwable $e, $request) {
            $status  = 500;
            $message = 'Erro interno no servidor';

            // Exceções de domínio
            if ($e instanceof \App\Exceptions\Domain\DomainExceptionInterface) {
                return response()->json([
                    'success' => false,
                    'error_code' => $e->getErrorCode(),
                    'message' => $e->getMessage(),
                ], $e->getStatusCode());
            }

            // Exceções comuns do Laravel
            if ($e instanceof \Illuminate\Validation\ValidationException) {
                $status  = 422;
                $message = 'Erro de validação';

                return response()->json([
                    'success' => false,
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

            // Logging estruturado (sempre)
            logger()->error($e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'type'  => class_basename($e),
            ]);

            // Em dev ou homologação mostra detalhes
            if (config('app.debug')) {
                return response()->json([
                    'success' => false,
                    'error_code' => method_exists($e, 'getErrorCode')
                        ? $e->getErrorCode()
                        : class_basename($e),
                    'message' => $e->getMessage(),
                ], $status);
            }

            // Em produção, resposta genérica
            return response()->json([
                'success' => false,
                'message' => $message,
            ], $status);
        });
    })
    ->create();