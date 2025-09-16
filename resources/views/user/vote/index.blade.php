<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voting Kandidat</title>
</head>

<body>
    <h1>Voting Kandidat</h1>
    @if (session('success'))
    <p style="color:green">{{ session('success') }}</p>
    @endif
    @if(session('error'))
    <p style="color:red">{{ session('error') }}</p>
    <form action="{{ route('logout') }}" method="POST" style="display:inline;">
        @csrf
        <button type="submit">Logout</button>
    </form>
    @endif

    @if($userVote)
    <p>Anda sudah memilih kandidat: <strong>{{ $userVote->candidate->leader_name }} &amp; {{ $userVote->candidate->coleader_name }}</strong></p>
    <form action="{{ route('logout') }}" method="POST" style="display:inline;">
        @csrf
        <button type="submit">Logout</button>
    </form>
    @else
    <form action="{{ route('user.vote.store') }}" method="post">
        @csrf
        @foreach ($candidate as $c)
        <hr>
        <h5>{{ $c->no_urut }}</h5>
        <img src="{{ asset('storage/'. $c->candidate_photo) }}" alt="Foto Kandidat">
        <b>{{ $c->leader_name }} &amp; {{ $c->coleader_name }}</b>
        <p>{{ $c->vision }}</p>
        <p>{{ $c->mission }}</p>
        <button type="submit" name="candidate_id" value="{{ $c->id }}" onclick="return confirm('Yakin memilih kandidat ini?')">Pilih</button>
        @endforeach
    </form>
    @endif

</body>

</html>