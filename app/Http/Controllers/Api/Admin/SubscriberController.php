<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SubscriberController extends Controller
{
    /**
     * Filters: status (active|unsubscribed), q, per_page. Meta includes per-status counts.
     */
    public function index(Request $request): JsonResponse
    {
        $query = NewsletterSubscriber::latest();

        if ($search = $request->input('q')) {
            $query->where('email', 'like', "%{$search}%");
        }

        if (in_array($status = $request->input('status'), ['active', 'unsubscribed'], true)) {
            $query->where('status', $status);
        }

        $subscribers = $query->paginate($this->perPage($request, 20))->withQueryString()->toArray();
        $subscribers['counts'] = [
            'all' => NewsletterSubscriber::count(),
            'active' => NewsletterSubscriber::where('status', 'active')->count(),
            'unsubscribed' => NewsletterSubscriber::where('status', 'unsubscribed')->count(),
        ];

        return response()->json($subscribers);
    }

    public function destroy(NewsletterSubscriber $subscriber): JsonResponse
    {
        $subscriber->delete();

        return response()->json(['message' => 'Subscriber removed successfully.']);
    }
}
