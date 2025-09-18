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
        const ctx = document.getElementById('voteChart').getContext('2d');
        let chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [], // kosong dulu
                datasets: [{
                    label: 'Jumlah Suara',
                    data: [],
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
                        display: true
                    },
                    title: {
                        display: true,
                        text: 'Hasil Perhitungan Suara Kandidat'
                    }
                }
            }
        });

        function loadData() {
            fetch("{{ route('admin.votes.getData') }}?t=" + new Date().getTime())
                .then(response => response.json())
                .then(result => {
                    chart.data.labels = result.labels;
                    chart.data.datasets[0].data = result.data;
                    chart.update();
                });
        }

        loadData(); // ambil data pertama
        setInterval(loadData, 3000); // update tiap 3 detik
    </script>

</body>

</html>
