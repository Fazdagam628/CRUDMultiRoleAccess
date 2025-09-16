<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
</head>

<body>
    <h1 class="text-xl font-bold">User Dashboard</h1>
    <p>Welcome, <strong>{{ Auth::user()->name }}</strong></p>
    {{-- atau pakai helper --}}
    {{-- <p>Welcome, <strong>{{ auth()->user()->name }}</strong></p> --}}
    <a href="{{ route('posts.index') }}">To Post</a>
    <a href="{{ route('user.vote.index') }}">To Vote</a>
    <form action="{{ route('logout') }}" method="POST" style="display:inline;">
        @csrf
        <button type="submit">Logout</button>
    </form>
    <form action="{{ route('user.dashboard.used') }}" method="post" onsubmit="return confirm('Mark this token as used?');">
        @csrf
        <button type="submit">Mark Token as Used</button>
    </form>

</body>

</html>
