<?php

namespace App\Http\Controllers\Session;

use App\Http\Controllers\Controller;
use App\Models\User\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\Session\UserResource;
use DB;
use Illuminate\Http\Response;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\Container\BindingResolutionException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class SessionController extends Controller
{
    private $user;
    private $createToken;

    public function __construct(User $user)
    {
        $this->user        = $user;
        $this->createToken = 'Mobile App';
    }

    /**
     * @param Request $request
     * @return Response|ResponseFactory
     * @throws BindingResolutionException
     * @throws BadRequestException
     */
    public function register(Request $request)
    {
        $validate = validator($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validate->fails()) {
            return response(['errors' => $validate->getMessageBag()], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        $request->merge([
            'password' => Hash::make($request->password),
        ]);

        try {
            DB::beginTransaction();

            $user = $this->user->create($request->all());

            $user->sendWelcomeNotification();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            return response([
                'error' => 'Sorry! Registration is not successfull.'
            ], JsonResponse::HTTP_BAD_REQUEST);
        }

        return response([
            'token' => $user->createToken($this->createToken)->plainTextToken,
        ], JsonResponse::HTTP_CREATED);
    }

    /**
     * @param Request $request
     * @return Response|ResponseFactory
     * @throws ValidationException
     * @throws BindingResolutionException
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response([
                'error' => 'Sorry! Login not successfull.'
            ], JsonResponse::HTTP_BAD_REQUEST);
        }

        $user->tokens()->where('name', $this->createToken)->delete();

        return response([
            'token' => $user->createToken($this->createToken)->plainTextToken,
        ], JsonResponse::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return UserResource
     */
    public function perfil(Request $request)
    {
        return new UserResource($request->user());
    }

    /**
     * @param Request $request
     * @return Response|ResponseFactory
     * @throws BindingResolutionException
     */
    public function logout(Request $request)
    {
        try {
            $request->user()->tokens()->delete();
        } catch (\Throwable $th) {
            return response([
                'error' => 'Sorry! Logout not successfull.'
            ], JsonResponse::HTTP_BAD_REQUEST);
        }

        return response([
            'logout' => true,
        ], JsonResponse::HTTP_OK);
    }
}
