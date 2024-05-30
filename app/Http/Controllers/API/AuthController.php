<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Models\User;
use OpenApi\Annotations as OA;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     *  @OA\Post(
     *      path="/api/user/register",
     *      tags={"user"},
     *      summary="Register new user & get token",
     *      operationId="register",
     *      @OA\Response(
     *          response=400,
     *          description="Invalid input",
     *          @OA\JsonContent()
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful",
     *          @OA\JsonContent()
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          description="Request body description",
     *          @OA\JsonContent(
     *              ref="App\Models\User",
     *              example={
     *                  "name": "Feliciano",
     *                  "email": "fel@gmail.com",
     *                  "password": "fel123_321",
     *                  "password_confirmation": "fel123_321"
     *              }
     *          )
     *      )
     * )
     */
    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                "name" => "required|string|max:255",
                "email" => "required|string|email|max:255|unique:users",
                "password" => "required|string|min:6|confirmed",
            ]);

            if ($validator->fails()) {
                throw new HttpException(400, $validator->messages()->first());
            }

            $request["password"] = Hash::make($request["password"]);
            $request["remember_token"] = Str::random(10);
            $user = User::create($request->toArray());
            $token = $user->createToken("All Yours")->accessToken;

            return response()->json(
                array(
                    "name" => $request->name,
                    "email" => $request->get("email"),
                    "token" => $token
                ),
                200
            );
        } catch (\Exception $exception) {
            throw new HttpException(400, "Invalid data : {$exception->getMessage()}");
        }
    }

    /**
     * @OA\Post(
     *      path="/api/user/login",
     *      tags={"user"},
     *      summary="Log in to existing user & get token",
     *      operationId="login",
     *      @OA\Response(
     *          response=400,
     *          description="Invalid input",
     *          @OA\JsonContent()
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful login",
     *          @OA\JsonContent()
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized",
     *          @OA\JsonContent()
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          description="Request body description",
     *          @OA\JsonContent(
     *              required={"email", "password"},
     *              @OA\Property(property="email", type="string", format="email", example="fel@gmail.com"),
     *              @OA\Property(property="password", type="string", example="fel123_321"),
     *          )
     *      )
     * )
     */
    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                "email" => "required|string|email|max:255",
                "password" => "required|string|min:6",
            ]);

            if ($validator->fails()) {
                throw new HttpException(400, $validator->messages()->first());
            }

            $user = User::where("email", $request->email)->first();

            if ($user) {
                if (Hash::check($request->password, $user->password)) {
                    $token = $user->createToken("All Yours")->accessToken;
                    return response()->json(
                        array(
                            "email" => $request->get("email"),
                            "token" => $token
                        ),
                        200
                    );
                } else {
                    return response()->json(array("message" => "Password mismatch"), 400);
                }
            } else {
                return response()->json(array("message" => "User does not exists"), 400);
            }
        } catch (\Exception $exception) {
            throw new HttpException(400, "Invalid data : {$exception->getMessage()}");
        }
    }

    /**
     * @OA\Post(
     *      path="/api/user/logout",
     *      tags={"user"},
     *      summary="Log out & destroy self token",
     *      operationId="logout",
     *      @OA\Response(
     *          response=400,
     *          description="Invalid input",
     *          @OA\JsonContent()
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful logout",
     *          @OA\JsonContent()
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          description="Request body description",
     *          @OA\JsonContent()
     *      ),
     * )
     */
    public function logout(Request $request)
    {
        try {
            $token = $request->user()->token();
            $token->revoke();
            return response()->json(array("message" => "You Have been successfully logged out!"), 200);
        } catch (\Exception $exception) {
            throw new HttpException(400, "Invalid data : {$exception->getMessage()}");
        }
    }
}
