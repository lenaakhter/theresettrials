<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/waving.PNG') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <title>Admin · Subscribers</title>
</head>
<body class="app-body">
    <main class="admin-posts">
        <div class="admin-posts__header">
            <div>
                <h1 class="admin-posts__title">Subscribers</h1>
                <p class="admin-posts__subtitle">People who joined your email list.</p>
            </div>
            <div class="admin-actions">
                <a href="{{ route('admin.posts.create') }}" class="admin-posts__logout admin-posts__logout--link">Write post</a>
                <a href="{{ route('admin.admins.create') }}" class="admin-posts__logout admin-posts__logout--link">Add admin</a>
                <a href="{{ route('admin.subscribers.export') }}" class="admin-posts__logout admin-posts__logout--link">Export CSV</a>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="admin-posts__logout">Log out</button>
                </form>
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
</body>
</html>
