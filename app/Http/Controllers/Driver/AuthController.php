<?php
namespace App\Http\Controllers\Driver;
use App\Helper\Helper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Auth\DriverUpdateProfile;
use App\Http\Repositories\Driver\AuthRepository;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct(private AuthRepository $authRepo) {
        $this->middleware('driver-auth', ['except' => ['login','SendOtp']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request){
        $credentials = Validator::make($request->all(), [
            'phone' => 'required|numeric|exists:drivers,phone,is_active,1|min:11',
            'otp' => 'required|numeric|min:6',
        ]);
        if ($credentials->fails()) {
            return Helper::responseError('There are an errors in your phone or otp', []);
        }
        $response = $this->authRepo->authenticate($request->all());
        return response()->json($response[0], $response['code']);
    }

    public function SendOtp(Request $request){
        $credentials = Validator::make($request->all(), [
            'phone' => 'required|numeric|exists:drivers,phone,is_active,1|min:11'
        ]);
        if ($credentials->fails()) {
            return Helper::responseError('There are an errors in your phone', []);
        }
        $response = $this->authRepo->SendOtp($request->all());
        return response()->json($response[0], $response['code']);
    }

    /**
     * Log the driver out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(){
        auth('driver')->logout();
        return Helper::responseSuccess('Driver successfully signed out', []);
    }
    
    /**
     * Get the authenticated Driver.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile(DriverUpdateProfile $request) {
        $driver = $request->validated();
        if($request->hasFile('image')){
            $driver['image'] = $request->file('image')->store('driver-images');
        }
        $response = $this->authRepo->update(auth('driver')->user()->id, $driver);
        return Helper::responseSuccess('Update profile successfully', $response);
    }

    public function details() {
        return $this->authRepo->getAuthDetails();
    }
}