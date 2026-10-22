<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminSubscriberController extends Controller
{
    public function index(Request $request): View
    {
        $query = NewsletterSubscriber::latest();

        if ($search = $request->input('q')) {
            $query->where('email', 'like', "%{$search}%");
        }

        if ($status = $request->input('status')) {
            if (in_array($status, ['active', 'unsubscribed'], true)) {
                $query->where('status', $status);
            }
        }

        $subscribers = $query->paginate(20)->withQueryString();

        $counts = [
            'all' => NewsletterSubscriber::count(),
            'active' => NewsletterSubscriber::where('status', 'active')->count(),
            'unsubscribed' => NewsletterSubscriber::where('status', 'unsubscribed')->count(),
        ];

        return view('admin.subscribers.index', compact('subscribers', 'counts'));
    }

    public function destroy(NewsletterSubscriber $subscriber): RedirectResponse
    {
        $subscriber->delete();

        return redirect()
            ->route('admin.subscribers.index')
            ->with('success', 'Subscriber removed successfully.');
    }
}
