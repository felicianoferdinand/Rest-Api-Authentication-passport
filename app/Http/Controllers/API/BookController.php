<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;

/**
 * @OA\Info(title="My First API", version="0.1")
 */
class BookController extends Controller
{
    /**
     *  @OA\OpenApi(
     *      @OA\Info(
     *          title="Book API",
     *          version="1.0.0",
     *          description="API for managing books",
     *          @OA\Contact(
     *              email="support@example.com"
     *          ),
     *          @OA\License(
     *              name="MIT",
     *              url="https://opensource.org/licenses/MIT"
     *          )
     *      ),
     *      @OA\SecurityScheme(
     *          securityScheme="passport_token_ready",
     *          type="http",
     *          scheme="bearer",
     *          description="Enter token",
     *          name="Authorization",
     *          in="header"
     *      ),
     *      @OA\SecurityScheme(
     *          securityScheme="passport",
     *          type="oauth2",
     *          description="Laravel passport oauth2 security",
     *          in="header",
     *          flows={
     *              "password": {
     *                  "authorizationUrl": config('app.url') . '/oauth/authorize',
     *                  "tokenUrl": config('app.url') . '/oauth/token',
     *                  "refreshUrl": config('app.url') . '/oauth/refresh',
     *                  "scopes": {}
     *              }
     *          }
     *      )
     *  )
     */

    /**
     *  @OA\Get(
     *      path="/api/book",
     *      tags={"book"},
     *      summary="Display list of items",
     *      operationId="index",
     *      @OA\Response(
     *          response=200,
     *          description="Successful",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(ref="App\Models\Book")
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Invalid request",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="error", type="string")
     *          )
     *      )
     * )
     */
    public function index()
    {
        try {
            $books = Book::all();
            return response()->json($books, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while fetching the books'], 500);
        }
    }


    /**
     *  @OA\Post(
     *      path="/api/book",
     *      tags={"book"},
     *      summary="Create item",
     *      operationId="create",
     *      @OA\RequestBody(
     *          required=true,
     *          description="Create a new book",
     *          @OA\JsonContent(
     *              ref="App\Models\Book",
     *              example={"name": "felicianoBook", "desc": "testFelic"}
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful",
     *          @OA\JsonContent(
     *              ref="App\Models\Book"
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Invalid input",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="error", type="string")
     *          )
     *      ),
     *      security={{"passport_token_ready":{}, "passport":{}}}
     * )
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'desc' => 'nullable|string',
            // Add other validation rules as needed
        ]);

        $book = Book::create($validatedData);

        return response()->json([
            'message' => 'Book created successfully',
            'data' => $book
        ], 201);
    }


    /**
     *  @OA\Get(
     *      path="/api/book/{id}",
     *      tags={"book"},
     *      summary="Display the specified book",
     *      operationId="show",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="ID of the book to retrieve",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              ref="App\Models\Book"
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Book not found",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="error", type="string")
     *          )
     *      )
     * )
     */
    public function show(string $id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json(['error' => 'Book not found'], 404);
        }

        return response()->json($book, 200);
    }


    /**
     *  @OA\Post(
     *      path="/api/book/{id}",
     *      tags={"book"},
     *      summary="Update item",
     *      operationId="update",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="ID of the book to update",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          description="Update book",
     *          @OA\JsonContent(
     *              ref="App\Models\Book",
     *              example={"name": "felicianoBook", "desc": "testFelic"}
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="success", type="boolean"),
     *              @OA\Property(property="data", ref="App\Models\Book")
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Invalid input",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="error", type="string")
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Book not found",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="error", type="string", example="Book not found")
     *          )
     *      ),
     *      security={{"passport_token_ready":{}, "passport":{}}}
     * )
     */
    public function update(Request $request, string $id)
    {
        $book = Book::find($id);
        if (!$book) {
            return response()->json(['error' => 'Book not found'], 404);
        }

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'desc' => 'nullable|string',
        ]);

        $book->update($validatedData);

        return response()->json([
            'success' => true,
            'data' => $book
        ], 200);
    }


    /**
     *  @OA\Delete(
     *      path="/api/book/{id}",
     *      tags={"book"},
     *      summary="Delete item",
     *      operationId="destroy",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="ID of the book to delete",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="message", type="string", example="Book deleted successfully")
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Book not found",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="error", type="string", example="Book not found")
     *          )
     *      ),
     *      security={{"passport_token_ready":{}, "passport":{}}}
     * )
     */
    public function destroy(string $id)
    {
        $book = Book::find($id);
        if ($book) {
            $book->delete();
            return response()->json(['message' => 'Book deleted successfully'], 200);
        } else {
            return response()->json(['error' => 'Book not found'], 404);
        }
    }
}
