<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\User;
use App\Http\Controllers\TagsController;
use App\Http\Controllers\NotesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class BoardController extends Controller {
    private const STATUSES = ['pending', 'in-progress', 'to-review', 'completed'];

    public function index(Request $request) {
        $workplace = Auth::user()->workplace;
        $workplaceId = $workplace?->id;

        $user = Auth::user();
        $userPermissions = $user->getAllPermissions()->pluck('name');
        $canViewOthers = $workplaceId && ($workplace?->user_id === $user->id || $userPermissions->contains("view-other $workplaceId"));

        $boards = Board::with([
            'users:id,name,email',
            'tasks' => function ($query) use ($canViewOthers, $user) {
                if (! $canViewOthers) {
                    $query->where('user_id', $user->id);
                }
                $query->with('user');
            },
        ])
        ->where('workplace_id', $workplaceId)
        ->get()
        ->map(function ($board) {
            $attachments = collect($board->attachments ?? [])->map(fn ($path) => [
                'name' => basename($path),
                'url' => Storage::url($path),
            ])->values();

            $tasks = $board->tasks->map(function ($task) {
                return [
                    'id' => $task->id,
                    'title' => $task->title,
                    'start' => $task->start,
                    'end' => $task->end,
                    'user' => $task->user ? [
                        'id' => $task->user->id,
                        'name' => $task->user->name,
                    ] : null,
                ];
            })->values();

            return [
                'id' => $board->id,
                'title' => $board->title,
                'description' => $board->description,
                'status' => $board->status,
                'due_date' => $board->due_date,
                'attachments' => $attachments,
                'users' => $board->users->map(fn ($user) => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ])->values(),
                'tasks' => $tasks,
                'total_logged' => $board->tasks->sum(fn ($task) => ($task->end - $task->start)),
            ];
        })
        ->values();

        $workplaces = Auth::user()->workplaces;
        $sharedWorkplaces = Auth::user()->sharedWorkplaces();

        $workplaceUsers = User::whereHas('permissions', function ($query) use ($workplaceId) {
            $query->where('name', 'LIKE', '% ' . $workplaceId);
        })->orWhere('id', $workplace?->user_id)->get(['id', 'name', 'email'])->unique('id')->values();

        $tags = TagsController::list();
        $notes = NotesController::list();

        return Inertia::render('Boards/Index', [
            'boards' => $boards,
            'users' => $workplaceUsers,
            'statuses' => self::STATUSES,
            'workplaces' => $workplaces->merge($sharedWorkplaces),
            'status' => session('status'),
            'notes' => $notes,
            'tags' => $tags,
        ]);
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'array'],
            'status' => ['nullable', 'string', 'in:' . implode(',', self::STATUSES)],
            'due_date' => ['nullable', 'date'],
            'attachments.*' => ['file', 'max:5120'],
            'assigned_users' => ['nullable', 'array'],
            'assigned_users.*' => ['exists:users,id'],
        ]);

        $attachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $attachments[] = $file->store('boards', 'public');
            }
        }

        $board = Board::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? [],
            'status' => $validated['status'] ?? 'pending',
            'due_date' => $validated['due_date'] ?? null,
            'attachments' => $attachments,
        ]);

        $board->users()->sync($validated['assigned_users'] ?? []);

        return Redirect::back()->with('status', [
            'code' => 201,
            'status' => 'Board created.',
        ]);
    }

    public function update(Request $request, Board $board) {
        $this->authorizeBoard($board);

        $validated = $request->validate([
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['nullable', 'array'],
            'status' => ['nullable', 'string', 'in:' . implode(',', self::STATUSES)],
            'due_date' => ['nullable', 'date'],
            'attachments.*' => ['file', 'max:5120'],
            'assigned_users' => ['nullable', 'array'],
            'assigned_users.*' => ['exists:users,id'],
        ]);

        $attachments = $board->attachments ?? [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $attachments[] = $file->store('boards', 'public');
            }
        }

        $board->update([
            'title' => $validated['title'] ?? $board->title,
            'description' => $validated['description'] ?? $board->description,
            'status' => $validated['status'] ?? $board->status,
            'due_date' => $validated['due_date'] ?? $board->due_date,
            'attachments' => $attachments,
        ]);

        if (array_key_exists('assigned_users', $validated)) {
            $board->users()->sync($validated['assigned_users'] ?? []);
        }

        return Redirect::back()->with('status', [
            'code' => 200,
            'status' => 'Board updated.',
        ]);
    }

    public function destroy(Board $board) {
        $this->authorizeBoard($board);
        Storage::disk('public')->delete($board->attachments ?? []);
        $board->delete();

        return Redirect::back()->with('status', [
            'code' => 200,
            'status' => 'Board deleted.',
        ]);
    }

    private function authorizeBoard(Board $board): void {
        $workplaceId = (int) session('workplace');
        if ($board->workplace_id !== $workplaceId) {
            abort(403, 'You are not authorized to modify this board.');
        }
    }
}
