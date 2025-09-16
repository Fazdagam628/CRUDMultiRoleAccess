<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index</title>
</head>

<body>

    <h1 class="text-xl font-bold">Posts</h1>
    <a href="{{ route('posts.create') }}">Create Post</a>

    @if (Auth::user()->is_admin)
    <a href="{{ route('admin.dashboard') }}">
        <button type="button">Go to Admin Dashboard</button>
    </a>
    @else
    <a href="{{ route('user.dashboard') }}">
        <button type="button">Go to User Dashboard</button>
    </a>
    @endif

    <ul>
        @foreach($posts as $post)
        <li>
            <strong>{{ $post->title }}</strong> by {{ $post->user->name }}
            <a href="{{ route('posts.edit',$post) }}">Edit</a>
            <form action="{{ route('posts.destroy',$post) }}" method="POST" style="display:inline;">
                @csrf @method('DELETE')
                <button type="submit">Delete</button>
            </form>
        </li>
        @endforeach
    </ul>
</body>

</html>