<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="API de Citas EPS",
 *     description="Documentación de la API para el sistema de gestión de citas médicas",
 *     @OA\Contact(
 *         name="Soporte",
 *         email="esteban.ricardo.dev@gmail.com"
 *     )
 * )
 *
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="Servidor principal"
 * )
 */

abstract class Controller
{
    /**
     * @OA\SecurityScheme(
     *     type="http",
     *     name="Authorization",
     *     in="header",
     *     scheme="bearer",
     *     bearerFormat="JWT",
     *     securityScheme="sanctum"
     * )
     */
}
