<!DOCTYPE html>
<html>

<head>
    <title>Daftar Kandidat</title>
</head>

<body>
    <h1>Daftar Kandidat</h1>
    @if(session('success'))
    <p style="color:green">{{ session('success') }}</p>
    @endif
    <a href="{{ route('admin.candidates.create') }}">Tambah Kandidat Baru</a>
    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>No Urut</th>
                <th>Foto</th>
                <th>Ketua</th>
                <th>Wakil</th>
                <th>Visi</th>
                <th>Misi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($candidates as $candidate)
            <tr>
                <td>{{ $candidate->no_urut}}</td>
                <td>
                    <img src="{{ asset('storage/' . $candidate->candidate_photo) }}" width="80" alt="Foto Kandidat">
                </td>
                <td>{{ $candidate->leader_name }}</td>
                <td>{{ $candidate->coleader_name }}</td>
                <td>{{ $candidate->vision }}</td>
                <td>{{ $candidate->mission }}</td>
                <td>
                    <a href="{{ route('admin.candidates.edit', $candidate->id) }}">Edit</a>
                    <form action="{{ route('admin.candidates.delete', $candidate->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Yakin hapus kandidat?')">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
