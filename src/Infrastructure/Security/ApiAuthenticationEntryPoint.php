<?php

namespace App\Infrastructure\Security;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;

class ApiAuthenticationEntryPoint implements AuthenticationEntryPointInterface
{
    public function start(Request $request, ?AuthenticationException $authException = null): Response
    {
        return new JsonResponse(
            [
                '@context' => '/api/contexts/Error',
                '@id' => '/api/errors/401',
                '@type' => 'Error',
                'title' => 'An error occurred',
                'detail' => 'Authentication required',
                'status' => 401,
                'type' => '/errors/401',
                'description' => 'Full authentication is required to access this resource.',
            ],
            Response::HTTP_UNAUTHORIZED
        );
    }
}
