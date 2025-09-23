<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
</head>

<body>
    <h1 class="text-xl font-bold">Admin Dashboard</h1>
    <p>Welcome, <strong>{{ Auth::user()->name }}</strong></p>
    {{-- atau pakai helper --}}
    {{-- <p>Welcome, <strong>{{ auth()->user()->name }}</strong></p> --}}
    <button><a href="{{ route('posts.index') }}" style="text-decoration: none;">To Post</a></button>
    <button><a href="{{ route('admin.candidates.index') }}" style="text-decoration: none;">Candidates</a></button>
    <button><a href="{{ route('admin.votes.results') }}" style="text-decoration: none;">To Results</a></button>
    <form action="{{ route('logout') }}" method="POST" style="display:inline;">
        @csrf
        <button type="submit">Logout</button>
    </form>
    <hr>
    <h2>Daftar User</h2>
    <ul>
        @foreach($users as $user)
        @if ($users->isEmpty())
        <li>No users found.</li>
        @else
        <li>{{ $user->name }} ({{ $user->email }})</li>
        <form action="{{ route('admin.user.reset') }}" method="post" style="display:inline;">
            @csrf
            @method('POST')
            <input type="hidden" name="user_id" value="{{ $user->id }}">
            <button type="submit" onclick="return confirm('Yakin Reset?')">Reset Akun</button>
        </form>
        @endif
        @endforeach
        @if(session('success'))
        <p style="color:green">{{ session('success') }}</p>
        @elseif(session('error'))
        <p style="color:red">{{ session('error') }}</p>

        @endif
    </ul>
    <form action="{{ route('admin.vote.resetAll') }}" method="post" style="margin-top: 10px;">
        @csrf
        <button type="submit" onclick="return confirm('Yakin reset semua vote?')">Reset Semua Vote</button>
    </form>


    <hr>
    <h2>Tambah User Baru</h2>
    <form action="{{ route('users.store') }}" method="POST">
        @csrf
        <input type="text" name="name" placeholder="Nama" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <label>
            <input type="checkbox" name="is_admin"> Admin?
        </label>
        <button type="submit">Tambah User</button>
    </form>
    <form action="{{ route('users.import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <label for="file">Import user</label>
        <input type="file" name="file" placeholder="xlsx,xls,csv" required>
        <button type="submit">Import</button>
    </form>
    <br>
    <h2>Buat Token untuk User</h2>
    <form action="{{ route('users.token') }}" method="POST">
        @csrf
        <label for="user_id">Pilih User</label>
        <select name="user_id" id="user_id" style="width: 300px" required>
            <option value="">-- Pilih User --</option>
            @foreach($users as $user)
            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
            @endforeach
        </select>
        <button type="submit">Buat Token</button>
    </form>

    @if(session('success'))
    <p style="color:green">{{ session('success') }}</p>
    @endif

    @if(session('new_token'))
    <div style="margin-top:10px; padding:10px; border:1px solid #ccc; display:inline-block;">
        <strong>Token:</strong>
        <span id="tokenText">{{ session('new_token') }}</span>
        <button type="button" onclick="copyToken()">Copy</button>
    </div>
    @endif

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Select2 CSS & JS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#user_id').select2({
                placeholder: "Cari nama atau email user",
                allowClear: true
            });
        });

        function copyToken() {
            let token = document.getElementById("tokenText").innerText;
            navigator.clipboard.writeText(token).then(function() {
                alert("Token berhasil disalin: " + token);
            }, function() {
                alert("Gagal menyalin token.");
            });
        }
    </script>

</body>

</html>