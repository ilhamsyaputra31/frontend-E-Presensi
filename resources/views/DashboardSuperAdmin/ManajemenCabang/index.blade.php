<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Cabang</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: "#4F46E5",
                        secondary: "#6366F1",
                        accent: "#F59E0B",
                        danger: "#E53E3E"
                    }
                }
            }
        };
    </script>
    <script
        src="https://maps.googleapis.com/maps/api/js?key=YOUR_GOOGLE_MAPS_API_KEY&libraries=places&callback=initAutocomplete"
        async defer></script>

</head>

<body class="bg-gray-100">

    <div class="flex min-h-screen">

        <!-- Sidebar -->
        <aside class="w-64 bg-gray-900 text-white h-screen p-5 fixed transition-transform transform duration-300">
            <h2 class="text-3xl font-bold mb-8 text-center">SuperAdmin</h2>
            <nav class="space-y-2">
                <a href="/SuperAdmin" class="flex items-center px-4 py-3 hover:bg-gray-700 rounded transition">
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
        <div class="ml-64 w-full p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-gray-700">Manajemen Cabang</h1>
                <button onclick="openModal()"
                    class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-secondary transition">
                    ➕ Tambah Cabang
                </button>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <table class="w-full border-collapse border border-gray-300">
                    <thead>
                        <tr class="bg-gray-200 text-gray-700">
                            <th class="border p-3 text-left">ID</th>
                            <th class="border p-3 text-left">Nama Cabang</th>
                            <th class="border p-3 text-left">Alamat</th>
                            <th class="border p-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="cabangList">
                        <tr>
                            <td colspan="4" class="text-center p-4 text-gray-500">Loading...</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Modal -->
            <div id="modal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center hidden">
                <div class="bg-white p-6 rounded-lg w-96 shadow-lg">
                    <h2 class="text-xl font-bold mb-4">Tambah Cabang</h2>
                    <label class="block mb-2">Nama Cabang</label>
                    <input type="text" id="namaCabang" class="w-full p-2 border rounded mb-3"
                        placeholder="Masukkan nama cabang">

                    <label class="block mb-2">Alamat</label>
                    <input type="text" id="alamatCabang" class="w-full p-2 border rounded mb-3"
                        placeholder="Masukkan alamat">

                    <input type="hidden" id="latitude">
                    <input type="hidden" id="longitude">

                    <div class="flex justify-end gap-3">
                        <button onclick="closeModal()"
                            class="px-4 py-2 border rounded-lg hover:bg-gray-200">Batal</button>
                        <button onclick="addCabang()"
                            class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-secondary transition">
                            Simpan
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let token = localStorage.getItem('auth_token');

            if (!token) {
                alert("Anda harus login terlebih dahulu!");
                window.location.href = "/";
                return;
            }

            fetchCabangData();
        });

        async function fetchCabangData() {
            let token = localStorage.getItem('auth_token');

            try {
                let response = await fetch('http://127.0.0.1:8000/api/cabangs', {
                    method: 'GET',
                    headers: {
                        'Authorization': 'Bearer ' + token,
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);

                const result = await response.json();
                const cabangList = result.data.data;

                let tableBody = document.getElementById("cabangList");
                tableBody.innerHTML = "";

                cabangList.forEach(cabang => {
                    const row = document.createElement("tr");
                    row.classList.add("hover:bg-gray-100", "transition");

                    row.innerHTML = `
                        <td class="border p-3 text-gray-700">${cabang.id}</td>
                        <td class="border p-3 text-gray-900 font-semibold">${cabang.nama_cabang}</td>
                        <td class="border p-3 text-gray-700">${cabang.alamat}</td>
                        <td class="border p-3">
                            <div class="flex justify-center gap-2">
                                <button class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">
                                    ✏️ Edit
                                </button>
                                <button class="bg-danger text-white px-4 py-2 rounded-lg hover:bg-red-600 transition">
                                    🗑 Hapus
                                </button>
                            </div>
                        </td>
                    `;
                    tableBody.appendChild(row);
                });

            } catch (error) {
                console.error("Error fetching data:", error);
                document.getElementById("cabangList").innerHTML = `
                    <tr>
                        <td colspan="4" class="text-center p-4 text-red-500">Gagal memuat data.</td>
                    </tr>
                `;
            }
        }

        function openModal() {
            document.getElementById("modal").classList.remove("hidden");

            navigator.geolocation.getCurrentPosition(
                (position) => {
                    console.log("Lokasi berhasil didapatkan:", position.coords);
                    document.getElementById("latitude").value = parseFloat(position.coords.latitude);
                    document.getElementById("longitude").value = parseFloat(position.coords.longitude);
                },
                (error) => {
                    console.error("Gagal mendapatkan lokasi:", error);
                    alert("Gagal mendapatkan lokasi. Pastikan GPS aktif dan beri izin lokasi.");
                }
            );
        }

        function closeModal() {
            document.getElementById("modal").classList.add("hidden");

            document.getElementById("namaCabang").value = "";
            document.getElementById("alamatCabang").value = "";
            document.getElementById("latitude").value = "";
            document.getElementById("longitude").value = "";
        }

        async function addCabang() {
            let token = localStorage.getItem('auth_token');
            if (!token) {
                alert("Anda harus login terlebih dahulu!");
                window.location.href = "/";
                return;
            }

            let namaCabang = document.getElementById("namaCabang").value.trim();
            let alamatCabang = document.getElementById("alamatCabang").value.trim();
            let latitude = parseFloat(document.getElementById("latitude").value);
            let longitude = parseFloat(document.getElementById("longitude").value);

            if (!namaCabang || !alamatCabang || isNaN(latitude) || isNaN(longitude)) {
                alert("Mohon isi semua data dan pastikan lokasi valid!");
                return;
            }

            let data = {
                nama_cabang: namaCabang,
                alamat: alamatCabang,
                latitude: latitude,
                longitude: longitude,
                radius: 100
            };

            try {
                let response = await fetch('http://127.0.0.1:8000/api/cabangs', {
                    method: 'POST',
                    headers: {
                        'Authorization': 'Bearer ' + token,
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(data)
                });

                let result = await response.json();
                console.log("Response dari API:", result);

                if (response.ok) {
                    alert("Cabang berhasil ditambahkan!");
                    closeModal();
                    fetchCabangData();
                } else {
                    alert("Gagal menambahkan cabang. Cek log untuk detail.");
                }
            } catch (error) {
                console.error("Error menambahkan cabang:", error);
                alert("Terjadi kesalahan saat mengirim data.");
            }
        }
    </script>

</body>

</html>
