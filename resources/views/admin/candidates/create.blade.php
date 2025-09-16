<!DOCTYPE html>
<html>

<head>
    <title>Tambah Kandidat</title>
</head>

<body>
    <h1>Tambah Kandidat Baru</h1>
    @if($errors->any())
    <ul style="color:red;">
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
    @endif
    <form action="{{ route('admin.candidates.store') }}" method="POST" enctype="multipart/form-data">

        @csrf

        <label for="no_urut">No Urut:</label>
        <input type="number" name="no_urut" value="{{ old('no_urut') }}" required><br><br>

        <label for="candidate_photo">Foto Kandidat:</label>
        <input type="file" name="candidate_photo" required><br><br>

        <label for="leader_name">Nama Ketua:</label>
        <input type="text" name="leader_name" value="{{ old('leader_name') }}" required><br><br>

        <label for="coleader_name">Nama Wakil:</label>
        <input type="text" name="coleader_name" value="{{ old('coleader_name') }}" required><br><br>

        <label for="vision">Visi:</label>
        <input type="text" name="vision" value="{{ old('vision') }}" required><br><br>

        <label for="mission">Misi:</label>
        <input type="text" name="mission" value="{{ old('mission') }}" required><br><br>


        <button type="submit">Simpan</button>

    </form>

    <a href="{{ route('admin.candidates.index') }}">Kembali ke daftar kandidat</a>
</body>

</html>
