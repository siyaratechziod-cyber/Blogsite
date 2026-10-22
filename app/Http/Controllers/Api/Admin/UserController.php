<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Concerns\DeletesPublicUploads;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Validation\Rule;

/**
 * Admin user management. {user} binds by numeric id here (public routes use username).
 */
class UserController extends Controller
{
    use DeletesPublicUploads;

    /**
     * Filters: q, role (admin|author|reader), status (active|inactive), per_page.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = User::withCount('posts')->latest();

        if ($search = $request->input('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%");
            });
        }

        if (in_array($role = $request->input('role'), ['admin', 'author', 'reader'], true)) {
            $query->where('role', $role);
        }

        $status = $request->input('status');
        if ($status === 'active') {
            $query->where('is_active', true);
        } elseif ($status === 'inactive') {
            $query->where('is_active', false);
        }

        return UserResource::collection($query->paginate($this->perPage($request, 10))->withQueryString());
    }

    public function show(User $user): UserResource
    {
        return new UserResource($user->loadCount(['posts', 'followers', 'following']));
    }

    public function update(Request $request, User $user): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'role' => ['required', Rule::in(['admin', 'author', 'reader'])],
            'is_active' => ['required', 'boolean'],
        ]);

        $actor = $request->user();

        if ($user->id === $actor->id && $data['role'] !== $user->role) {
            return $this->refuse('You cannot change your own role. Ask another admin to do it.');
        }

        if ($user->isAdmin() && $data['role'] !== 'admin' && $this->isLastAdmin($user)) {
            return $this->refuse('This is the last remaining admin account, so its role cannot be changed.');
        }

        $isActive = (bool) $data['is_active'];

        if ($user->isActive() && ! $isActive) {
            if ($user->id === $actor->id) {
                return $this->refuse('You cannot deactivate your own account.');
            }

            if ($user->isAdmin() && $this->isLastActiveAdmin($user)) {
                return $this->refuse('This is the last active admin account, so it cannot be deactivated.');
            }
        }

        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $data['role'],
            'is_active' => $isActive,
        ]);

        if (! $isActive) {
            $user->tokens()->delete();
        }

        return response()->json([
            'message' => 'User updated successfully.',
            'data' => new UserResource($user),
        ]);
    }

    public function toggleStatus(Request $request, User $user): JsonResponse
    {
        if ($user->id === $request->user()->id) {
            return $this->refuse('You cannot deactivate your own account.');
        }

        if ($user->isAdmin() && $user->isActive() && $this->isLastActiveAdmin($user)) {
            return $this->refuse('This is the last active admin account, so it cannot be deactivated.');
        }

        $user->update(['is_active' => ! $user->isActive()]);

        if (! $user->isActive()) {
            $user->tokens()->delete();
        }

        return response()->json([
            'message' => $user->isActive() ? 'User account activated.' : 'User account deactivated.',
            'data' => new UserResource($user),
        ]);
    }

    public function destroy(Request $request, User $user): JsonResponse
    {
        if ($user->id === $request->user()->id) {
            return $this->refuse('You cannot delete your own account.');
        }

        if ($user->posts()->exists()) {
            return $this->refuse('This user has published content. Deactivate the account instead of deleting it to avoid losing their posts.');
        }

        if ($user->isAdmin() && $this->isLastAdmin($user)) {
            return $this->refuse('This is the last remaining admin account, so it cannot be deleted.');
        }

        $this->deleteUpload($user->profile_image);
        $this->deleteUpload($user->cover_image);
        $user->tokens()->delete();
        $user->delete();

        return response()->json(['message' => 'User deleted successfully.']);
    }

    private function refuse(string $message): JsonResponse
    {
        return response()->json(['message' => $message], 422);
    }

    private function isLastAdmin(User $user): bool
    {
        return User::where('role', 'admin')->whereKeyNot($user->id)->doesntExist();
    }

    private function isLastActiveAdmin(User $user): bool
    {
        return User::where('role', 'admin')
            ->where('is_active', true)
            ->whereKeyNot($user->id)
            ->doesntExist();
    }
}
