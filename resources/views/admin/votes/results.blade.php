<!DOCTYPE html>
<html>

<head>
    <title>Hasil Voting</title>
</head>

<body>
    <h1>Hasil Voting</h1>
    <table border="1" cellpadding="8">
        <tr>
            <th>No Urut</th>
            <th>Ketua & Wakil</th>
            <th>Jumlah Suara</th>
        </tr>
        @foreach($results as $c)
        <tr>
            <td>{{ $c->no_urut }}</td>
            <td>{{ $c->leader_name }} &amp; {{ $c->coleader_name }}</td>
            <td>{{ $c->votes_count }}</td>
        </tr>
        @endforeach
    </table>
</body>

</html>