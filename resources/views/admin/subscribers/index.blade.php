@extends('layouts.admin')

@section('content')
    <main class="admin-posts">
        <div class="admin-posts__header">
            <div>
                <h1 class="admin-posts__title">Subscribers</h1>
                <p class="admin-posts__subtitle">People who joined your email list.</p>
                <p class="admin-posts__badge">Total subscribers: {{ number_format($subscriberCount) }}</p>
            </div>
            <div class="admin-actions">
                <a href="{{ route('admin.subscribers.export') }}" class="admin-posts__logout admin-posts__logout--link">Export CSV</a>
                <a href="{{ route('admin.subscribers.export-excel') }}" class="admin-posts__logout admin-posts__logout--link">Convert to Excel</a>
            </div>
        </div>

        <section class="admin-editor">
            @if ($subscribers->isEmpty())
                <p class="admin-recent__empty">No subscribers yet.</p>
            @else
                <div class="admin-table-wrap">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Email</th>
                                <th>Joined</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($subscribers as $subscriber)
                                <tr>
                                    <td>{{ $subscriber->email }}</td>
                                    <td>{{ optional($subscriber->created_at)->format('M d, Y H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="admin-pagination">
                    {{ $subscribers->links() }}
                </div>
            @endif
        </section>
    </main>
@endsection
