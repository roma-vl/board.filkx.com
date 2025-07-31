<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Admin\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Http\Requests\User\UserFilterRequest;
use App\Http\Resources\Admin\User\UserResource;
use App\Http\Services\Users\UserService;
use App\Models\Users\Role;
use App\Models\Users\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class UsersController extends Controller
{
    public function __construct(
        private readonly UserService $userService,
    ) {}

    public function index(UserFilterRequest $request): Response
    {
        $validated = $request->validatedWithDefaults();
        $users = UserResource::collection($this->userService->getFilteredPaginatedUsers($validated));

        return Inertia::render('Admin/Users/Index', [
            'users' => $users,
        ]);
    }

    public function create(): JsonResponse
    {
        if (Gate::denies('user.create')) {
            abort(403);
        }

        return response()->json([
            'roles' => Role::all(),
        ]);
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        if (Gate::denies('user.create')) {
            abort(403);
        }

        $user = $this->userService->createUserFromAdmin($request->validated());

        if ($request->has('roles')) {
            $user->roles()->sync($request->input('roles'));
        }

        return back()->with('success', 'User created successfully!');
    }

    public function show(User $user)
    {
        return response()->json($user);
    }

    public function edit(User $user)
    {
        if (Gate::denies('user.edit')) {
            abort(403);
        }

        return response()->json([
            'user' => new UserResource($user),
            'roles' => Role::all(),
            'userRoles' => $user->roles->pluck('id'),
        ]);
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        if (Gate::denies('user.edit')) {
            abort(403);
        }

        $this->userService->updateUser($user, $request->validated());

        if ($request->has('roles')) {
            $user->roles()->sync($request->input('roles'));
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user): RedirectResponse
    {
        if (Gate::denies('user.delete')) {
            abort(403);
        }

        $this->userService->deleteUser($user);

        return redirect()->route('admin.users.index')
            ->with('info', 'Вжух і щось сталося... 🤡 ');
    }

    public function restore(int $id): RedirectResponse
    {
        if (Gate::denies('user.delete')) {
            abort(403);
        }

        $this->userService->restoreUser($id);

        return redirect()->route('admin.users.index')
            ->with('success', 'Вжух і користувач відновлений! 🤡');
    }

    public function search(UserFilterRequest $request): Response
    {
        $validated = $request->validatedWithDefaults();
        $users = UserResource::collection($this->userService->getFilteredPaginatedUsers($validated));

        return Inertia::render('Admin/Users/Index', [
            'users' => $users,
        ]);
    }
}
