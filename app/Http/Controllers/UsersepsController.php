<?php

namespace App\Http\Controllers;

use App\Models\Userseps;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Tag(
 *     name="UsersEPS",
 *     description="Operaciones relacionadas con usuarios EPS"
 * )
 *
 * @OA\Schema(
 *     schema="Userseps",
 *     type="object",
 *     required={"name", "email", "identificationType", "identificationNumber", "phone", "address", "healthcenters_id"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Juan Pérez"),
 *     @OA\Property(property="email", type="string", format="email", example="juan@example.com"),
 *     @OA\Property(property="identificationType", type="string", example="CC"),
 *     @OA\Property(property="identificationNumber", type="string", example="123456789"),
 *     @OA\Property(property="phone", type="string", example="3001234567"),
 *     @OA\Property(property="address", type="string", example="Calle 123 #45-67"),
 *     @OA\Property(property="healthcenters_id", type="integer", example=1),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 *     @OA\Property(
 *         property="healthcenter",
 *         type="object",
 *         ref="#/components/schemas/Healthcenter"
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="Healthcenter",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Centro de Salud Principal"),
 *     @OA\Property(property="address", type="string", example="Calle 100 #23-45")
 * )
 *
 * @OA\Schema(
 *     schema="UsersepsRequest",
 *     type="object",
 *     required={"name", "email", "identificationType", "identificationNumber", "phone", "address", "healthcenters_id"},
 *     @OA\Property(property="name", type="string", example="Juan Pérez", maxLength=255),
 *     @OA\Property(property="email", type="string", format="email", example="juan@example.com", maxLength=255),
 *     @OA\Property(property="identificationType", type="string", example="CC", maxLength=255),
 *     @OA\Property(property="identificationNumber", type="string", example="123456789", maxLength=255),
 *     @OA\Property(property="phone", type="string", example="3001234567", maxLength=255),
 *     @OA\Property(property="address", type="string", example="Calle 123 #45-67", maxLength=255),
 *     @OA\Property(property="healthcenters_id", type="integer", example=1)
 * )
 */
class UsersepsController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/userseps",
     *     summary="Listar todos los usuarios EPS",
     *     tags={"UsersEPS"},
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de usuarios EPS",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Userseps")
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
        $userseps = Userseps::with('healthcenter')->get();
        return response()->json($userseps, 200);
    }

    public function create() {}

    /**
     * @OA\Post(
     *     path="/api/userseps",
     *     summary="Crear un nuevo usuario EPS",
     *     tags={"UsersEPS"},
     *     security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UsersepsRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Usuario creado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="User created successfully"),
     *             @OA\Property(property="code", type="integer", example=201)
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="array", @OA\Items(type="string")),
     *             @OA\Property(property="code", type="integer", example=422)
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'identificationType' => 'required|string|max:255',
            'identificationNumber' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'healthcenters_id' => 'required|integer|exists:healthcenters,id',
        ]);

        if ($validator->fails()) {
            $data = [
                'status' => 'error',
                'message' => $validator->errors()->all(),
                'code' => 422
            ];
            return response()->json($data, 422);
        }

        Userseps::create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
            'code' => 201
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/userseps/{id}",
     *     summary="Obtener un usuario EPS específico",
     *     tags={"UsersEPS"},
     * security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del usuario EPS",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuario encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="User found"),
     *             @OA\Property(property="user", ref="#/components/schemas/Userseps"),
     *             @OA\Property(property="code", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Usuario no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="User not found"),
     *             @OA\Property(property="code", type="integer", example=404)
     *         )
     *     )
     * )
     */
    public function show(string $id)
    {
        $user = Userseps::find($id);
        if (!$user) {
            $data = [
                'status' => 'error',
                'message' => 'User not found',
                'code' => 404
            ];
            return response()->json($data, 404);
        }

        $data = [
            'status' => 'success',
            'message' => 'User found',
            'user' => $user,
            'code' => 200
        ];
        return response()->json($data, 200);
    }

    public function edit(string $id) {}

    /**
     * @OA\Put(
     *     path="/api/userseps/{id}",
     *     summary="Actualizar un usuario EPS",
     *     tags={"UsersEPS"},
     * security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del usuario EPS a actualizar",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UsersepsRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuario actualizado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="User updated successfully"),
     *             @OA\Property(property="code", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Usuario no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="User not found"),
     *             @OA\Property(property="code", type="integer", example=404)
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="array", @OA\Items(type="string")),
     *             @OA\Property(property="code", type="integer", example=422)
     *         )
     *     )
     * )
     */
    public function update(Request $request, string $id)
    {
        $user = Userseps::find($id);
        if (!$user) {
            $data = [
                'status' => 'error',
                'message' => 'User not found',
                'code' => 404
            ];
            return response()->json($data, 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'string|max:255',
            'email' => 'string|email|max:255|unique:users,email,' . $id,
            'identificationType' => 'string|max:255',
            'identificationNumber' => 'string|max:255',
            'status' => 'boolean',
            'role' => 'string|max:255',
            'phone' => 'string|max:255',
            'address' => 'string|max:255',
            'healthcenters_id' => 'integer|exists:healthcenters,id',
        ]);

        if ($validator->fails()) {
            $data = [
                'status' => 'error',
                'message' => $validator->errors()->all(),
                'code' => 422
            ];
            return response()->json($data, 422);
        }

        $user->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'User updated successfully',
            'code' => 200
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/userseps/{id}",
     *     summary="Eliminar un usuario EPS",
     *     tags={"UsersEPS"},
     * security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del usuario EPS a eliminar",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuario eliminado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="User deleted successfully"),
     *             @OA\Property(property="code", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Usuario no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="User not found"),
     *             @OA\Property(property="code", type="integer", example=404)
     *         )
     *     )
     * )
     */
    public function destroy(string $id)
    {
        $user = Userseps::find($id);
        if (!$user) {
            $data = [
                'status' => 'error',
                'message' => 'User not found',
                'code' => 404
            ];
            return response()->json($data, 404);
        }

        $user->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'User deleted successfully',
            'code' => 200
        ]);
    }
}
