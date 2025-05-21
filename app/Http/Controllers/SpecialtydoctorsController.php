<?php

namespace App\Http\Controllers;

use App\Models\Specialtydoctors;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Tag(
 *     name="Specialty Doctors",
 *     description="Operaciones relacionadas con especialidades de médicos"
 * )
 *
 * @OA\Schema(
 *     schema="SpecialtyDoctor",
 *     type="object",
 *     required={"specialty", "doctors_id"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="specialty", type="string", example="Cardiología"),
 *     @OA\Property(property="doctors_id", type="integer", example=1),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 *     @OA\Property(
 *         property="doctor",
 *         type="object",
 *         ref="#/components/schemas/Doctor"
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="SpecialtyDoctorRequest",
 *     type="object",
 *     required={"specialty", "doctors_id"},
 *     @OA\Property(property="specialty", type="string", example="Cardiología", maxLength=255),
 *     @OA\Property(property="doctors_id", type="integer", example=1)
 * )
 */
class SpecialtydoctorsController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/specialtydoctors",
     *     summary="Listar todas las especialidades de médicos",
     *     tags={"Specialty Doctors"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de especialidades de médicos",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/SpecialtyDoctor")
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
        $Specialtydoctors = Specialtydoctors::all();
        return response()->json($Specialtydoctors, 200);
    }

    public function create() {}

    /**
     * @OA\Post(
     *     path="/api/specialtydoctors",
     *     summary="Crear una nueva especialidad de médico",
     *     tags={"Specialty Doctors"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/SpecialtyDoctorRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Especialidad creada exitosamente",
     *         @OA\JsonContent(ref="#/components/schemas/SpecialtyDoctor")
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
            'specialty' => 'required|string|max:255',
            'doctors_id' => 'required|integer|exists:doctors,id'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $Specialtydoctors = Specialtydoctors::create($request->all());
        return response()->json($Specialtydoctors, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/specialtydoctors/{id}",
     *     summary="Obtener una especialidad de médico específica",
     *     tags={"Specialty Doctors"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la especialidad del médico",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Especialidad encontrada",
     *         @OA\JsonContent(ref="#/components/schemas/SpecialtyDoctor")
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
     *         description="Especialidad no encontrada",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="specialty doctor not found"),
     *             @OA\Property(property="code", type="integer", example=404)
     *         )
     *     )
     * )
     */
    public function show(string $id)
    {
        $Specialtydoctors = Specialtydoctors::find($id);
        if (!$Specialtydoctors) {
            $data = [
                'status' => 'error',
                'message' => 'specialty doctor not found',
                'code' => 404
            ];
            return response()->json($data, 404);
        }

        return response()->json($Specialtydoctors, 200);
    }

    public function edit(string $id) {}

    /**
     * @OA\Put(
     *     path="/api/specialtydoctors/{id}",
     *     summary="Actualizar una especialidad de médico",
     *     tags={"Specialty Doctors"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la especialidad a actualizar",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/SpecialtyDoctorRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Especialidad actualizada exitosamente",
     *         @OA\JsonContent(ref="#/components/schemas/SpecialtyDoctor")
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
     *         description="Especialidad no encontrada",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="specialty doctor not found"),
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
        $Specialtydoctors = Specialtydoctors::find($id);
        if (!$Specialtydoctors) {
            $data = [
                'status' => 'error',
                'message' => 'specialty doctor not found',
                'code' => 404
            ];
            return response()->json($data, 404);
        }

        $validator = Validator::make($request->all(), [
            'specialty' => 'required|string|max:255',
            'doctors_id' => 'required|integer|exists:doctors,id'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $Specialtydoctors->update($request->all());
        return response()->json($Specialtydoctors, 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/specialtydoctors/{id}",
     *     summary="Eliminar una especialidad de médico",
     *     tags={"Specialty Doctors"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la especialidad a eliminar",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Especialidad eliminada exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="specialty doctor deleted successfully"),
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
     *         description="Especialidad no encontrada",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="specialty doctor not found"),
     *             @OA\Property(property="code", type="integer", example=404)
     *         )
     *     )
     * )
     */
    public function destroy(string $id)
    {
        $Specialtydoctors = Specialtydoctors::find($id);
        if (!$Specialtydoctors) {
            $data = [
                'status' => 'error',
                'message' => 'specialty doctor not found',
                'code' => 404
            ];
            return response()->json($data, 404);
        }

        $Specialtydoctors->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'specialty doctor deleted successfully',
            'code' => 200
        ], 200);
    }
}
