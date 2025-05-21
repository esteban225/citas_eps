<?php

namespace App\Http\Controllers;

use App\Models\Quotes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Tag(
 *     name="Quotes",
 *     description="API endpoints for managing quotes"
 * )
 */
class QuotesController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/quotes",
     *     tags={"Quotes"},
     * security={{"sanctum":{}}},
     *     summary="Get all quotes",
     *     @OA\Response(
     *         response=200,
     *         description="Successful response"
     *     )
     * )
     */
    public function index()
    {
        $quotes = Quotes::all();
        return response()->json($quotes, 200);
    }

    /**
     * @OA\Post(
     *     path="/api/quotes",
     *     tags={"Quotes"},
     * security={{"sanctum":{}}},
     *     summary="Create a new quote",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"type", "date", "status", "doctors_id", "userseps_id"},
     *             @OA\Property(property="type", type="string"),
     *             @OA\Property(property="date", type="string", format="date"),
     *             @OA\Property(property="status", type="string"),
     *             @OA\Property(property="reason", type="string", nullable=true),
     *             @OA\Property(property="observations", type="string", nullable=true),
     *             @OA\Property(property="doctors_id", type="integer"),
     *             @OA\Property(property="userseps_id", type="integer")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Quote created successfully"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|string|max:255',
            'date' => 'required|date',
            'status' => 'required|string|max:255',
            'reason' => 'nullable|string|max:255',
            'observations' => 'nullable|string|max:255',
            'doctors_id' => 'required|integer|exists:doctors,id',
            'userseps_id' => 'required|integer|exists:userseps,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $quote = Quotes::create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Quote created successfully',
            'quote' => $quote,
            'code' => 201
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/quotes/{id}",
     *     tags={"Quotes"},
     * security={{"sanctum":{}}},
     *     summary="Get a quote by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Quote found"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Quote not found"
     *     )
     * )
     */
    public function show(string $id)
    {
        $quote = Quotes::find($id);
        if (!$quote) {
            return response()->json([
                'message' => 'Quote not found',
                'code' => 404
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Quote found',
            'quote' => $quote,
            'code' => 200
        ], 200);
    }

    /**
     * @OA\Put(
     *     path="/api/quotes/{id}",
     *     tags={"Quotes"},
     * security={{"sanctum":{}}},
     *     summary="Update a quote",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="type", type="string"),
     *             @OA\Property(property="date", type="string", format="date"),
     *             @OA\Property(property="status", type="string"),
     *             @OA\Property(property="reason", type="string", nullable=true),
     *             @OA\Property(property="observations", type="string", nullable=true),
     *             @OA\Property(property="doctors_id", type="integer"),
     *             @OA\Property(property="userseps_id", type="integer")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Quote updated successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Quote not found"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function update(Request $request, string $id)
    {
        $quote = Quotes::find($id);
        if (!$quote) {
            return response()->json([
                'status' => 'error',
                'message' => 'Quote not found',
                'code' => 404
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'type' => 'string|max:255',
            'date' => 'date',
            'status' => 'string|max:255',
            'reason' => 'nullable|string|max:255',
            'observations' => 'nullable|string|max:255',
            'doctors_id' => 'integer|exists:doctors,id',
            'userseps_id' => 'integer|exists:userseps,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $quote->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Quote updated successfully',
            'quote' => $quote,
            'code' => 200
        ], 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/quotes/{id}",
     *     tags={"Quotes"},
     * security={{"sanctum":{}}},
     *     summary="Delete a quote",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Quote deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Quote not found"
     *     )
     * )
     */
    public function destroy(string $id)
    {
        $quote = Quotes::find($id);
        if (!$quote) {
            return response()->json([
                'status' => 'error',
                'message' => 'Quote not found',
                'code' => 404
            ], 404);
        }

        $quote->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Quote deleted successfully',
            'code' => 200
        ], 200);
    }

    // Métodos vacíos sin anotaciones
    public function create() {}
    public function edit(string $id) {}
}
