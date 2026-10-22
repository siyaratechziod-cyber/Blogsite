<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * Filters: status (read|unread), q, per_page. Meta includes unread_count.
     */
    public function index(Request $request): JsonResponse
    {
        $query = ContactMessage::latest();

        $status = $request->input('status');
        if ($status === 'unread') {
            $query->where('is_read', false);
        } elseif ($status === 'read') {
            $query->where('is_read', true);
        }

        if ($search = $request->input('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%");
            });
        }

        $messages = $query->paginate($this->perPage($request, 10))->withQueryString()->toArray();
        $messages['unread_count'] = ContactMessage::where('is_read', false)->count();

        return response()->json($messages);
    }

    /**
     * Opening a message marks it as read, same as the web panel.
     */
    public function show(ContactMessage $message): JsonResponse
    {
        if (! $message->is_read) {
            $message->update(['is_read' => true]);
        }

        return response()->json(['data' => $message]);
    }

    public function toggleRead(ContactMessage $message): JsonResponse
    {
        $message->update(['is_read' => ! $message->is_read]);

        return response()->json([
            'message' => $message->is_read ? 'Message marked as read.' : 'Message marked as unread.',
            'data' => $message,
        ]);
    }

    public function destroy(ContactMessage $message): JsonResponse
    {
        $message->delete();

        return response()->json(['message' => 'Message deleted successfully.']);
    }
}
