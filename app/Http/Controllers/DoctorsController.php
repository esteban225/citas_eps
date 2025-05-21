<?php

namespace App\Http\Controllers;

use App\Models\Doctors;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Tag(
 *     name="Doctors",
 *     description="Operaciones relacionadas con médicos"
 * )
 *
 * @OA\Schema(
 *     schema="Doctor",
 *     type="object",
 *     required={"name", "email", "identificationType", "identificationNumber", "phone", "address", "specialty", "license_number"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Dr. Juan Pérez"),
 *     @OA\Property(property="email", type="string", format="email", example="juan.perez@example.com"),
 *     @OA\Property(property="identificationType", type="string", example="CC"),
 *     @OA\Property(property="identificationNumber", type="string", example="123456789"),
 *     @OA\Property(property="phone", type="string", example="3001234567"),
 *     @OA\Property(property="address", type="string", example="Calle 123 #45-67"),
 *     @OA\Property(property="specialty", type="string", example="Cardiología"),
 *     @OA\Property(property="license_number", type="string", example="MED12345"),
 *     @OA\Property(property="status", type="boolean", example=true),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 *
 * @OA\Schema(
 *     schema="DoctorRequest",
 *     type="object",
 *     required={"name", "email", "identificationType", "identificationNumber", "phone", "address", "specialty", "license_number"},
 *     @OA\Property(property="name", type="string", example="Dr. Juan Pérez", maxLength=255),
 *     @OA\Property(property="email", type="string", format="email", example="juan.perez@example.com", maxLength=255),
 *     @OA\Property(property="identificationType", type="string", example="CC", maxLength=255),
 *     @OA\Property(property="identificationNumber", type="string", example="123456789", maxLength=255),
 *     @OA\Property(property="phone", type="string", example="3001234567", maxLength=255),
 *     @OA\Property(property="address", type="string", example="Calle 123 #45-67", maxLength=255),
 *     @OA\Property(property="specialty", type="string", example="Cardiología", maxLength=255),
 *     @OA\Property(property="license_number", type="string", example="MED12345", maxLength=255),
 *     @OA\Property(property="status", type="boolean", example=true)
 * )
 */
class DoctorsController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/doctors",
     *     summary="Listar todos los médicos",
     *     tags={"Doctors"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de médicos",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Doctor")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
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
        $doctors = Doctors::all();
        return response()->json($doctors, 200);
    }

    public function create() {}

    /**
     * @OA\Post(
     *     path="/api/doctors",
     *     summary="Crear un nuevo médico",
     *     tags={"Doctors"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/DoctorRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Médico creado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Doctor created successfully"),
     *             @OA\Property(property="doctor", ref="#/components/schemas/Doctor"),
     *             @OA\Property(property="code", type="integer", example=201)
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
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
            'email' => 'required|string|email|max:255|unique:doctors',
            'identificationType' => 'required|string|max:255',
            'identificationNumber' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'specialty' => 'required|string|max:255',
            'license_number' => 'required|string|max:255|unique:doctors',
            'status' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $doctor = Doctors::create($request->all());
        return response()->json([
            'status' => 'success',
            'message' => 'Doctor created successfully',
            'doctor' => $doctor,
            'code' => 201
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/doctors/{id}",
     *     summary="Obtener un médico específico",
     *     tags={"Doctors"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del médico",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Médico encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Doctor found"),
     *             @OA\Property(property="doctor", ref="#/components/schemas/Doctor"),
     *             @OA\Property(property="code", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Médico no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Doctor not found"),
     *             @OA\Property(property="code", type="integer", example=404)
     *         )
     *     )
     * )
     */
    public function show(string $id)
    {
        $doctor = Doctors::find($id);
        if (!$doctor) {
            return response()->json([
                'status' => 'error',
                'message' => 'Doctor not found',
                'code' => 404
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Doctor found',
            'doctor' => $doctor,
            'code' => 200
        ]);
    }

    public function edit(string $id) {}

    /**
     * @OA\Put(
     *     path="/api/doctors/{id}",
     *     summary="Actualizar un médico",
     *     tags={"Doctors"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del médico a actualizar",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/DoctorRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Médico actualizado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Doctor updated successfully"),
     *             @OA\Property(property="doctor", ref="#/components/schemas/Doctor"),
     *             @OA\Property(property="code", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Médico no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Doctor not found"),
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
        $doctor = Doctors::find($id);
        if (!$doctor) {
            return response()->json([
                'status' => 'error',
                'message' => 'Doctor not found',
                'code' => 404
            ]);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'string|max:255',
            'email' => 'string|email|max:255|unique:doctors,email,' . $id,
            'identificationType' => 'string|max:255',
            'identificationNumber' => 'integer',
            'phone' => 'string|max:255',
            'address' => 'string|max:255',
            'specialty' => 'string|max:255',
            'license_number' => 'string|max:255|unique:doctors,license_number,' . $id,
            'status' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $doctor->update($request->all());
        return response()->json([
            'status' => 'success',
            'message' => 'Doctor updated successfully',
            'doctor' => $doctor,
            'code' => 200
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/doctors/{id}",
     *     summary="Eliminar un médico",
     *     tags={"Doctors"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del médico a eliminar",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Médico eliminado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Doctor deleted successfully"),
     *             @OA\Property(property="code", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Médico no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Doctor not found"),
     *             @OA\Property(property="code", type="integer", example=404)
     *         )
     *     )
     * )
     */
    public function destroy(string $id)
    {
        $doctor = Doctors::find($id);
        if (!$doctor) {
            return response()->json([
                'status' => 'error',
                'message' => 'Doctor not found',
                'code' => 404
            ]);
        }

        $doctor->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Doctor deleted successfully',
            'code' => 200
        ]);
    }
}
