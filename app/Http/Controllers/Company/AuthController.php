<?php
namespace App\Http\Controllers\Company;
use App\Helper\Helper;
use App\Http\Requests\Auth\Login;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UpdateProfile;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Repositories\Company\AuthRepository;
use App\Http\Requests\Otp\SendOtpUser;
class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct(private AuthRepository $authRepo) {
        $this->middleware('company-auth', ['except' => ['login','SendOtp']]);
    }
 
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Login $request){
        $credentials = $request->validated();
        $response = $this->authRepo->authenticate($credentials);
        return response()->json($response[0], $response['code']);
    }

    
    
    public function SendOtp(SendOtpUser $request){
   
        $credentials = $request->validated();
        $response = $this->authRepo->SendOtp($credentials);
        return response()->json($response[0], $response['code']);
    }
    /**
     * Get the authenticated User details.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function details() {
        $response = $this->authRepo->getUserAuth();
        return response()->json($response, Response::HTTP_OK);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile(UpdateProfile $request) {

        $user = $request->validated();
        if($request->hasFile('image')){
            $user['image'] = $request->file('image')->store('avatars');
        }
        $response = $this->authRepo->update(auth('user')->user()->id, $user);
        return Helper::responseSuccess('Update profile successfully', $response);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(){
        auth('user')->logout();
        return Helper::responseSuccess('User successfully signed out', []);
    }
}