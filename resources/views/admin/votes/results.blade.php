<!DOCTYPE html>
<html>

<head>
    <title>Hasil Voting</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <h1>Hasil Voting</h1>
    <h1>Statistik Voting Kandidat</h1>
    <a href="{{ route('admin.dashboard') }}">To Dashboard</a>
    <canvas id="voteChart" width="400" height="200"></canvas>
    <script>
        fetch("{{ route('admin.votes.getData') }}")
            .then(response => response.json())
            .then(result => {
                const ctx = document.getElementById('voteChart').getContext('2d');
                new Chart(ctx, {
                    type: 'bar', // bisa diganti 'pie', 'line', dll
                    data: {
                        labels: result.labels,
                        datasets: [{
                            label: 'Jumlah Suara',
                            data: result.data,
                            backgroundColor: [
                                '#36A2EB',
                                '#FF6384',
                                '#FFCE56',
                                '#4BC0C0',
                                '#9966FF',
                                '#FF9F40'
                            ]
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                display: false
                            },
                            title: {
                                display: true,
                                text: 'Hasil Perhitungan Suara Kandidat'
                            }
                        }
                    }
                });
            });
    </script>
</body>

</html>