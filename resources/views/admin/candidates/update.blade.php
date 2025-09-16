<!DOCTYPE html>
<html>

<head>
    <title>Edit Kandidat</title>
</head>

<body>
    <h1>Edit Kandidat</h1>
    @if($errors->any())
    <ul style="color:red;">
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
    @endif
    <form action="{{ route('admin.candidates.update', $candidate->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <label>Foto Kandidat:</label>
        <input type="file" name="candidate_photo">
        <br>
        @if($candidate->candidate_photo)
        <img src="{{ asset('storage/' . $candidate->candidate_photo) }}" width="100" alt="Foto Kandidat">
        @endif
        <br><br>

        <label for="leader_name">Nama Ketua:</label>
        <input type="text" name="leader_name" value="{{ $candidate->leader_name }}" required><br><br>

        <label for="coleader_name">Nama Wakil:</label>
        <input type="text" name="coleader_name" value="{{ $candidate->coleader_name }}" required><br><br>

        <label for="vision">Visi:</label>
        <input type="text" name="vision" value="{{ $candidate->vision }}" required><br><br>

        <label for="mission">Misi:</label>
        <input type="text" name="mission" value="{{ $candidate->mission }}" required><br><br>

        <label for="no_urut">No Urut:</label>
        <input type="number" name="no_urut" value="{{ $candidate->no_urut }}" required><br><br>

        <button type="submit">Update</button>
    </form>

    <a href="{{ route('admin.candidates.index') }}">Kembali ke daftar kandidat</a>
</body>

</html>
