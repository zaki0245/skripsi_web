<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Rekomendasi Saham</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .sidebar {
            height: 100%;
            width: 250px;
            position: fixed;
            top: 0;
            left: -250px;
            background-color: #698270;
            padding-top: 50px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            transition: left 0.3s;
        }
        .sidebar h2 {
            color: #fff;
            text-align: center;
            margin-bottom: 20px;
        }
        .nav-link {
            color: #fff;
            font-weight: bold;
            transition: all 0.3s ease;
        }
        .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 5px;
        }
        .content {
            margin-left: 0;
            padding: 20px;
            margin-top: 100px;
            padding-top: 20px;
            transition: margin-left 0.3s;
        }
        .header {
            text-align: center;
            padding: 20px 0;
            background-color: #698270;
            color: #fff;
            width: 100%;
            z-index: 500;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.2);
            position: fixed;
            top: 0;
        }
        #openSidebar {
            position: fixed;
            top: 20px;
            left: 20px;
            background-color: #698270;
            color: #fff;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
            z-index: 1500;
        }
    </style>
</head>
<body>
    <button id="openSidebar">&#9776;</button>

    <div class="sidebar" id="sidebar">
        <h2>Fitur</h2>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('data.index') }}">Data Aktual</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('perhitungan') }}">Manajemen Bobot</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('evaluasi') }}">Evaluasi</a>
            </li>
        </ul>
    </div>

    <div class="header">
        <h1>Sistem Rekomendasi Saham</h1>
    </div>
    <div class="content">
        <h1>Nilai Bobot</h1>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Kriteria</th>
                        <th>Bobot</th>
                        <th>Aksi</th> 
                    </tr>
                </thead>
                <tbody>
                    @foreach ($kriterias as $kriteria)
                    <tr>
                        <td>{{ $kriteria->indikator }}</td>
                        <td id="bobot{{ $kriteria->id }}">{{ $kriteria->bobot }}</td>
                        <td>
                            <button class="btn btn-primary" onclick="editBobot({{ $kriteria->id }})">Edit</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script>
        const openSidebarBtn = document.getElementById('openSidebar');
        const sidebar = document.getElementById('sidebar');
        const content = document.querySelector('.content');

        openSidebarBtn.addEventListener('click', () => {
            if (sidebar.style.left === '0px') {
                sidebar.style.left = '-250px';
                content.style.marginLeft = '0';
            } else {
                sidebar.style.left = '0';
                content.style.marginLeft = '250px';
            }
        });

        function editBobot(id) {
        const bobotElement = document.getElementById('bobot' + id);
        const currentBobot = bobotElement.innerText;
        const newBobot = prompt('Masukkan nilai bobot baru:', currentBobot);
        
        if (newBobot !== null && newBobot.trim() !== '') {
            fetch(`{{ url('update-bobot') }}/${id}`, { 
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ bobot: newBobot })
            })
            .then(response => {
                if (response.ok) {
                    bobotElement.innerText = newBobot;
                    alert('Nilai bobot berhasil diperbarui.');
                } else {
                    throw new Error('Gagal memperbarui nilai bobot.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan. Mohon coba lagi.');
            });
        }
    }
    </script>
</body>
</html>
