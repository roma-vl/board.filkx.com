<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Http\Resources\Admin\User\UserResource;
use App\Http\Repositories\UserRepository;
use App\Http\Services\SearchSortService;
use App\Http\Services\UserService;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use Inertia\Response;

class UsersController extends Controller
{
    const int PER_PAGE = 5;
    const string SORT_BY_DEFAULT = 'id';
    const string SORT_ORDER_DEFAULT = 'asc';

    public function __construct(
        private readonly UserService    $userService,
        private readonly UserRepository $userRepository,
        private readonly SearchSortService $searchSortService
    ) {}

    public function index(Request $request): Response
    {
        $perPage = $this->getSessionValue($request, 'per_page', self::PER_PAGE);
        $sortBy = $this->getSessionValue($request, 'sort_by', self::SORT_BY_DEFAULT);
        $sortOrder = $this->getSessionValue($request, 'sort_order', self::SORT_ORDER_DEFAULT);

        $usersQuery = $this->userRepository->getPaginatedUsers($perPage, $sortBy, $sortOrder);
        $users = UserResource::collection($usersQuery);

        return Inertia::render('Admin/Users/Index', compact('users', 'sortBy', 'sortOrder'));
    }

    public function create(): JsonResponse
    {
        $roles = Role::all(); // Отримуємо всі ролі

        return response()->json([
            'roles' => $roles,
        ]);
    }



    public function store(StoreUserRequest $request): RedirectResponse
    {
        $user = $this->userService->createUser($request->validated());

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
        if (Gate::denies('edit-user')) {
            abort(403);
        }

        $roles = Role::all();
        $userRoles = $user->roles->pluck('id');

        return response()->json([
            'user' => new UserResource($user),
            'roles' => $roles,
            'userRoles' => $userRoles
        ]);
    }


    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        if (Gate::denies('edit-user')) {
            abort(403);
        }

        $data = $request->validated();

        // Оновлення даних користувача
        $this->userService->updateUser($user, $data);

        // Оновлення ролей (якщо передані)
        if ($request->has('roles')) {
            $user->roles()->sync($request->input('roles'));
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }


    public function destroy(User $user): RedirectResponse
    {
        $this->userService->deleteUser($user);
        return redirect()->route('admin.users.index')
            ->with('info', 'Вжух і щось сталося... 🤡 ');
    }

    public function restore(int $id): RedirectResponse
    {
        $user = $this->userService->restoreUser($id);
        return redirect()->route('admin.users.index')
            ->with('success', 'Вжух і користувач відновлений! 🤡');
    }

    public function search(Request $request): Response
    {
        $perPage = $this->getSessionValue($request, 'per_page', self::PER_PAGE);
        $sortBy = $this->getSessionValue($request, 'sort_by', self::SORT_BY_DEFAULT);
        $sortOrder = $this->getSessionValue($request, 'sort_order', self::SORT_ORDER_DEFAULT);

        $search = $request->input('search');
        $usersQuery = $this->userRepository->getPaginatedUsers($perPage, $sortBy, $sortOrder);
        $this->searchSortService->applySearch($usersQuery, $search);

        $users = UserResource::collection($usersQuery);

        return Inertia::render('Admin/Users/Index', compact('users', 'sortBy', 'sortOrder'));
    }

    private function getSessionValue(Request $request, string $key, $default)
    {
        $value = $request->query($key, session($key, $default));
        session([$key => $value]);
        return $value;
    }
}
