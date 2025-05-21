<?php

namespace App\Http\Controllers;

use App\Models\Healthcenters;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Tag(
 *     name="Health Centers",
 *     description="Operaciones relacionadas con centros de salud"
 * )
 *
 * @OA\Schema(
 *     schema="HealthCenter",
 *     type="object",
 *     required={"name", "address", "phone", "email", "type", "status"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Centro Médico Principal"),
 *     @OA\Property(property="address", type="string", example="Calle 123 #45-67"),
 *     @OA\Property(property="phone", type="string", example="6012345678"),
 *     @OA\Property(property="email", type="string", format="email", example="centro@example.com"),
 *     @OA\Property(property="type", type="string", example="Hospital"),
 *     @OA\Property(property="status", type="boolean", example=true),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 *
 * @OA\Schema(
 *     schema="HealthCenterRequest",
 *     type="object",
 *     required={"name", "address", "phone", "email", "type", "status"},
 *     @OA\Property(property="name", type="string", example="Centro Médico Principal", maxLength=255),
 *     @OA\Property(property="address", type="string", example="Calle 123 #45-67", maxLength=255),
 *     @OA\Property(property="phone", type="string", example="6012345678", maxLength=255),
 *     @OA\Property(property="email", type="string", format="email", example="centro@example.com", maxLength=255),
 *     @OA\Property(property="type", type="string", example="Hospital", maxLength=255),
 *     @OA\Property(property="status", type="boolean", example=true)
 * )
 */
class HealthcenterController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/healthcenters",
     *     summary="Listar todos los centros de salud",
     *     tags={"Health Centers"},
     * security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de centros de salud",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/HealthCenter")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor"
     *     )
     * )
     */
    public function index()
    {
        $healthcenters = Healthcenters::all();
        return response()->json($healthcenters, 200);
    }

    public function create() {}

    /**
     * @OA\Post(
     *     path="/api/healthcenters",
     *     summary="Crear un nuevo centro de salud",
     *     tags={"Health Centers"},
     * security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/HealthCenterRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Centro de salud creado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Health center created successfully"),
     *             @OA\Property(property="healthcenter", ref="#/components/schemas/HealthCenter"),
     *             @OA\Property(property="code", type="integer", example=201)
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 additionalProperties={
     *                     @OA\Property(type="array", @OA\Items(type="string"))
     *                 }
     *             )
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:healthcenters',
            'type' => 'required|string|max:255',
            'status' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $healthcenter = Healthcenters::create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Health center created successfully',
            'healthcenter' => $healthcenter,
            'code' => 201
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/healthcenters/{id}",
     *     summary="Obtener un centro de salud específico",
     *     tags={"Health Centers"},
     * security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del centro de salud",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Centro de salud encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Health center found"),
     *             @OA\Property(property="healthcenter", ref="#/components/schemas/HealthCenter"),
     *             @OA\Property(property="code", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Centro de salud no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Health center not found"),
     *             @OA\Property(property="code", type="integer", example=404)
     *         )
     *     )
     * )
     */
    public function show(string $id)
    {
        $healthcenter = Healthcenters::find($id);
        if (!$healthcenter) {
            return response()->json([
                'status' => 'error',
                'message' => 'Health center not found',
                'code' => 404
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Health center found',
            'healthcenter' => $healthcenter,
            'code' => 200
        ], 200);
    }

    public function edit(string $id) {}

    /**
     * @OA\Put(
     *     path="/api/healthcenters/{id}",
     *     summary="Actualizar un centro de salud",
     *     tags={"Health Centers"},
     * security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del centro de salud a actualizar",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/HealthCenterRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Centro de salud actualizado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Health center updated successfully"),
     *             @OA\Property(property="healthcenter", ref="#/components/schemas/HealthCenter"),
     *             @OA\Property(property="code", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Centro de salud no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Health center not found"),
     *             @OA\Property(property="code", type="integer", example=404)
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 additionalProperties={
     *                     @OA\Property(type="array", @OA\Items(type="string"))
     *                 }
     *             )
     *         )
     *     )
     * )
     */
    public function update(Request $request, string $id)
    {
        $healthcenter = Healthcenters::find($id);
        if (!$healthcenter) {
            return response()->json([
                'status' => 'error',
                'message' => 'Health center not found',
                'code' => 404
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'string|max:255',
            'address' => 'string|max:255',
            'phone' => 'string|max:255',
            'email' => 'string|email|max:255|unique:healthcenters,email,' . $id,
            'type' => 'string|max:255',
            'status' => 'required|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $healthcenter->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Health center updated successfully',
            'healthcenter' => $healthcenter,
            'code' => 200
        ], 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/healthcenters/{id}",
     *     summary="Eliminar un centro de salud",
     *     tags={"Health Centers"},
     * security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del centro de salud a eliminar",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Centro de salud eliminado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Health center deleted successfully"),
     *             @OA\Property(property="code", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Centro de salud no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Health center not found"),
     *             @OA\Property(property="code", type="integer", example=404)
     *         )
     *     )
     * )
     */
    public function destroy(string $id)
    {
        $healthcenter = Healthcenters::find($id);
        if (!$healthcenter) {
            return response()->json([
                'status' => 'error',
                'message' => 'Health center not found',
                'code' => 404
            ], 404);
        }

        $healthcenter->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Health center deleted successfully',
            'code' => 200
        ], 200);
    }
}
