@extends('layouts.admin')

@section('title', 'Subscribers')
@section('page-title', 'Subscribers')

@php
    $currentStatus = request('status', '');
    $tabs = [
        '' => ['label' => 'All', 'count' => $counts['all']],
        'active' => ['label' => 'Active', 'count' => $counts['active']],
        'unsubscribed' => ['label' => 'Unsubscribed', 'count' => $counts['unsubscribed']],
    ];
@endphp

@section('content')
    <div class="bh-admin-card">
        <div class="bh-admin-card-header">
            <div>
                <h2 class="bh-admin-card-title">Newsletter Subscribers</h2>
                <p class="text-xs text-muted mb-0">{{ $counts['active'] }} active {{ Str::plural('subscriber', $counts['active']) }} receiving the newsletter.</p>
            </div>
        </div>

        <div class="bh-admin-card-body border-bottom">
            <div class="d-flex flex-wrap gap-2 mb-3">
                @foreach ($tabs as $value => $tab)
                    <a href="{{ $value === '' ? route('admin.subscribers.index', request()->only('q')) : route('admin.subscribers.index', array_merge(request()->only('q'), ['status' => $value])) }}"
                       class="badge rounded-pill text-decoration-none px-3 py-2 {{ $currentStatus === $value ? 'bg-primary' : 'bg-secondary-subtle text-secondary-emphasis' }}"
                       @if ($currentStatus === $value) aria-current="page" @endif>
                        {{ $tab['label'] }}
                        <span class="opacity-75 ms-1">{{ $tab['count'] }}</span>
                    </a>
                @endforeach
            </div>

            <form action="{{ route('admin.subscribers.index') }}" method="GET" class="bh-admin-filters" role="search">
                @if ($currentStatus !== '')
                    <input type="hidden" name="status" value="{{ $currentStatus }}">
                @endif
                <label for="subscriberSearch" class="visually-hidden">Search subscribers</label>
                <input type="text" id="subscriberSearch" name="q" class="bh-form-control py-2 text-sm" placeholder="Search by email..." value="{{ request('q') }}">
                <button type="submit" class="btn-bh-secondary py-2 px-3 text-sm">Search</button>
                @if (request()->hasAny(['q', 'status']))
                    <a href="{{ route('admin.subscribers.index') }}" class="text-xs text-muted hover-underline ms-1">Reset</a>
                @endif
            </form>
        </div>

        @if ($subscribers->isEmpty())
            <div class="bh-admin-empty">
                <i class="bi bi-send"></i>
                <p class="mb-1 fw-semibold">No subscribers found</p>
                <p class="text-sm mb-0">
                    @if (request()->hasAny(['q', 'status']))
                        Try adjusting your filters.
                    @else
                        Emails collected through the newsletter signup will appear here.
                    @endif
                </p>
            </div>
        @else
            <div class="table-responsive">
                <table class="bh-admin-table">
                    <thead>
                        <tr>
                            <th scope="col">Email</th>
                            <th scope="col">Status</th>
                            <th scope="col">Subscribed</th>
                            <th scope="col" class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($subscribers as $subscriber)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="bh-stat-icon is-navy" style="width: 32px; height: 32px; font-size: 0.85rem;"><i class="bi bi-envelope-at"></i></span>
                                        <span class="text-sm fw-semibold">{{ $subscriber->email }}</span>
                                    </div>
                                </td>
                                <td>
                                    @if ($subscriber->status === 'active')
                                        <span class="badge rounded-pill bg-success-subtle text-success-emphasis">Active</span>
                                    @else
                                        <span class="badge rounded-pill bg-secondary-subtle text-secondary-emphasis">Unsubscribed</span>
                                    @endif
                                </td>
                                <td class="text-nowrap text-muted text-xs">{{ $subscriber->created_at->format('M j, Y') }}<br>{{ $subscriber->created_at->format('g:i A') }}</td>
                                <td>
                                    <div class="d-flex justify-content-end gap-1">
                                        <form action="{{ route('admin.subscribers.destroy', $subscriber) }}" method="POST" data-confirm="Remove {{ $subscriber->email }} from the subscriber list? This cannot be undone.">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bh-action-btn is-danger" title="Remove subscriber" aria-label="Remove {{ $subscriber->email }}">
                                                <i class="bi bi-trash3"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($subscribers->hasPages())
                <div class="bh-admin-pagination">
                    {{ $subscribers->links('pagination::bootstrap-5') }}
                </div>
            @endif
        @endif
    </div>
@endsection
