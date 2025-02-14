<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard SuperAdmin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: "#4F46E5",
                        secondary: "#6366F1",
                        accent: "#F59E0B"
                    }
                }
            }
        };
    </script>
</head>

<body class="bg-gray-100">

    <!-- Wrapper -->
    <div class="flex min-h-screen">

        <!-- Sidebar -->
        <aside class="w-64 bg-gray-900 text-white h-screen p-5 fixed transition-transform transform duration-300">
            <h2 class="text-3xl font-bold mb-8 text-center">SuperAdmin</h2>
            <nav class="space-y-2">
                <a href="#" class="flex items-center px-4 py-3 hover:bg-gray-700 rounded transition">
                    <span class="mr-3">🏠</span> Dashboard
                </a>
                <a href="/SuperAdmin/ManajemenCabang"
                    class="flex items-center px-4 py-3 hover:bg-gray-700 rounded transition">
                    <span class="mr-3">🏢</span> Manajemen Cabang
                </a>
                <a href="#" class="flex items-center px-4 py-3 hover:bg-gray-700 rounded transition">
                    <span class="mr-3">👨‍💼</span> Manajemen Karyawan
                </a>
                <a href="#" class="flex items-center px-4 py-3 hover:bg-gray-700 rounded transition">
                    <span class="mr-3">🔑</span> Manajemen Admin Cabang
                </a>
                <a href="#" class="flex items-center px-4 py-3 bg-red-600 hover:bg-red-700 rounded transition">
                    <span class="mr-3">🚪</span> Logout
                </a>
            </nav>
        </aside>


        <!-- Main Content -->
        <div class="ml-64 w-full transition-all duration-300">
            <header class="bg-white shadow-md p-4 flex justify-between items-center">
                <h1 class="text-2xl font-bold text-gray-700">Dashboard</h1>
                <div class="flex items-center space-x-4">
                    <input type="text" placeholder="Search..."
                        class="px-3 py-2 border rounded-lg focus:ring focus:ring-primary">
                    <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center">
                        <span class="text-gray-600">👤</span>
                    </div>
                </div>
            </header>

            <main class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                    <div class="bg-primary text-white p-6 rounded-lg shadow-md hover:scale-105 transition">
                        <h3 class="text-lg font-semibold">Total Karyawan</h3>
                        <p id="totalKaryawan" class="text-3xl font-bold">Loading...</p>
                        <div class="text-right text-5xl opacity-50">👥</div>
                    </div>

                    <div class="bg-green-500 text-white p-6 rounded-lg shadow-md hover:scale-105 transition">
                        <h3 class="text-lg font-semibold">Total Cabang</h3>
                        <p id="totalcabang" class="text-3xl font-bold">Loading...</p>
                        <div class="text-right text-5xl opacity-50">🏢</div>
                    </div>

                    <div class="bg-accent text-white p-6 rounded-lg shadow-md hover:scale-105 transition">
                        <h3 class="text-lg font-semibold">Pending Tickets</h3>
                        <p class="text-3xl font-bold">12</p>
                        <div class="text-right text-5xl opacity-50">🎫</div>
                    </div>

                </div>
            </main>
        </div>

    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let token = localStorage.getItem('auth_token');

            if (!token) {
                alert("Anda harus login terlebih dahulu!");
                window.location.href = "/";
            } else {
                fetchKaryawanData();
                fetchCabangData();
            }
        });

        let token = localStorage.getItem('auth_token');
        async function fetchKaryawanData() {
            try {
                if (!token) throw new Error("Token tidak ditemukan. Pastikan sudah login.");

                let response = await fetch('http://127.0.0.1:8000/api/karyawans', {
                    method: 'GET',
                    headers: {
                        'Authorization': 'Bearer ' + token,
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);

                const result = await response.json();
                document.getElementById("totalKaryawan").textContent = result.data.total || "0";

            } catch (error) {
                console.error("Error fetching data:", error);
                document.getElementById("totalKaryawan").textContent = "Error";
            }
        }

        document.addEventListener("DOMContentLoaded", fetchCabangData);
        async function fetchCabangData() {
            try {
                if (!token) throw new Error("Token tidak ditemukan. Pastikan sudah login.");

                let response = await fetch('http://127.0.0.1:8000/api/cabangs', {
                    method: 'GET',
                    headers: {
                        'Authorization': 'Bearer ' + token,
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);

                const result = await response.json();
                document.getElementById("totalcabang").textContent = result.data.total || "0";

            } catch (error) {
                console.error("Error fetching data:", error);
                document.getElementById("totalcabang").textContent = "Error";
            }
        }

        document.addEventListener("DOMContentLoaded", fetchKaryawanData);
    </script>


</body>

</html>
