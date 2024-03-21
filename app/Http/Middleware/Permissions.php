<?php
namespace App\Http\Middleware;

use Closure;
use App\Models\Role;
use Symfony\Component\HttpFoundation\Response;
use Spatie\Permission\Exceptions\UnauthorizedException;

class Permissions
{
    public function handle($request, Closure $next, $permission, $guard = null)
    {
        $authGuard = app('auth')->guard($guard);

        if ($authGuard->guest()) {
            throw UnauthorizedException::notLoggedIn();
        }
        $userRole = auth()->user()->roles;
        $rolesId = [];
        foreach ($userRole as $role) {
            $rolesId[] =  $role->id;
        }
        $roles =  Role::with('permissions')->whereIn('id', $rolesId)->get();
        $userPermissions = auth()->user()->getPermissionNames()->toArray();
        if (in_array($permission, $userPermissions)) {
            return $next($request);
        }
        foreach($roles as $role){
            foreach($role->permissions->pluck('name') as $rolePermission){
                if ($permission == $rolePermission) {
                    return $next($request);
                }
                if(!in_array($rolePermission, $userPermissions)){
                    $userPermissions[] = $rolePermission;
                }
            }
        }
        
        return response()->json([
            'status' =>  false,
            'message' => 'User does not have the right permissions.' ,
        ], Response::HTTP_FORBIDDEN);
    }
}