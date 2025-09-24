<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
</head>

<body>
    <h1>Login</h1>

    @if(session('error'))
    <p style="color:red">{{ session('error') }}</p>
    @endif
    @if (session('success'))
    <p style="color:green">{{ session('success') }}</p>
    @endif
    <form method="POST" action="{{ url('login') }}">
        @csrf
        <input type="text" name="name" placeholder="Nama" required><br>
        <!-- <input type="email" name="email" placeholder="Email" required><br> -->
        <input type="password" name="password" placeholder="Password" required><br>
        <button type="submit">Login</button>
    </form>
</body>

</html>
