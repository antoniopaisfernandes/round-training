<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\QueryBuilder;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $users = UserResource::collection(
            QueryBuilder::for(User::class)
                ->allowedFilters(['id', 'name', 'email'])
                ->allowedIncludes(['roles'])
                ->defaultSort('name')
                ->allowedSorts(['id', 'name'])
                ->paginate(! request()->has('limit') ? 10 : (request()->get('limit') < 0 ? 9999 : request()->get('limit')))
                ->appends(request()->query())
        );

        return request()->expectsJson()
            ? $users
            : view('user.index', [
                'users' => $users,
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = DB::transaction(function () use ($request, $validated) {
            $user = User::create($validated);
            $user->assignRole($request->get('roles') ?: []);
            $user->givePermissionTo($request->get('permissions') ?: []);

            return $user;
        });

        return $this->show($user);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(User $user)
    {
        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UserRequest  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(UserRequest $request, User $user)
    {
        $user = DB::transaction(function () use ($request, $user) {
            $user->update($request->validated());
            if ($request->has('roles')) {
                $user->syncRoles(...$request->get('roles'));
            }
            if ($request->has('permissions')) {
                $user->syncPermissions(...$request->get('permissions'));
            }

            return $user;
        });

        return $this->show($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\User $user
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(User $user)
    {
        if (auth()->user()->is($user)) {
            abort(403, 'Cannot delete itself');
        }

        $user->delete();

        return response()->json();
    }
}
