@extends('Layout.presensi')

@section('header')
    <div class="appHeader bg-primary text-light">
        <div class="left">
            <a href="/user" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="section">
            <h3 class="text-center text-white fw-bold">📋 Riwayat Absensi</h3>
        </div>
    </div>
@endsection

@section('content')
    <div id="appCapsule" style="margin-top: 70px">

        <!-- Filter Tahun dan Bulan -->
        <div class="section mt-3">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-5">
                            <label for="filterTahun" class="form-label fw-bold">Tahun</label>
                            <select class="form-control" id="filterTahun"></select>
                        </div>
                        <div class="col-5">
                            <label for="filterBulan" class="form-label fw-bold">Bulan</label>
                            <select class="form-control" id="filterBulan">
                                <option value="">Semua</option>
                                <option value="01">Januari</option>
                                <option value="02">Februari</option>
                                <option value="03">Maret</option>
                                <option value="04">April</option>
                                <option value="05">Mei</option>
                                <option value="06">Juni</option>
                                <option value="07">Juli</option>
                                <option value="08">Agustus</option>
                                <option value="09">September</option>
                                <option value="10">Oktober</option>
                                <option value="11">November</option>
                                <option value="12">Desember</option>
                            </select>
                        </div>
                        <div class="col-2 d-flex align-items-end">
                            <button id="btnTampilkan" class="btn btn-primary w-100">
                                <ion-icon name="search-outline"></ion-icon>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- List Riwayat Absensi -->
        <div class="section mt-2">
            <div class="card">
                <div class="card-body">
                    <ul class="list-group" id="riwayatAbsensi">
                        <li class="list-group-item text-center">Silakan pilih tahun & bulan, lalu tekan tombol.</li>
                    </ul>
                </div>
            </div>
        </div>

    </div>

    <script>
        document.addEventListener("DOMContentLoaded", async function() {
            let token = localStorage.getItem("auth_token");

            if (!token) {
                window.location.href = "/";
                return;
            }

            const filterTahun = document.getElementById("filterTahun");
            const filterBulan = document.getElementById("filterBulan");
            const btnTampilkan = document.getElementById("btnTampilkan");
            const riwayatAbsensi = document.getElementById("riwayatAbsensi");

            // Mengisi dropdown Tahun (5 tahun terakhir hingga sekarang)
            let tahunSekarang = new Date().getFullYear();
            for (let i = tahunSekarang; i >= tahunSekarang - 5; i--) {
                let option = document.createElement("option");
                option.value = i;
                option.textContent = i;
                filterTahun.appendChild(option);
            }

            // Event Listener untuk Tombol Tampilkan
            btnTampilkan.addEventListener("click", loadRiwayatAbsensi);

            async function loadRiwayatAbsensi() {
                try {
                    let tahun = filterTahun.value;
                    let bulan = filterBulan.value;
                    let url = `http://127.0.0.1:8000/api/riwayat-absensi?tahun=${tahun}`;
                    if (bulan) url += `&bulan=${bulan}`;

                    riwayatAbsensi.innerHTML =
                        "<li class='list-group-item text-center'>Memuat data...</li>";

                    let response = await fetch(url, {
                        method: "GET",
                        headers: {
                            Authorization: "Bearer " + token,
                            Accept: "application/json",
                        },
                    });

                    let result = await response.json();
                    console.log(result); // Debugging untuk melihat data API

                    riwayatAbsensi.innerHTML = ""; // Bersihkan daftar sebelum menampilkan data baru

                    // Filter data agar hanya menampilkan tahun & bulan yang sesuai
                    let filteredData = result.data.data.filter((item) => {
                        let {
                            tahunAbsen,
                            bulanAbsen
                        } = extractYearMonth(item.waktu_absen);
                        return tahunAbsen == tahun && (!bulan || bulanAbsen == bulan);
                    });

                    if (filteredData.length > 0) {
                        filteredData.forEach((item) => {
                            let li = document.createElement("li");
                            li.classList.add("list-group-item", "d-flex", "justify-content-between",
                                "align-items-center");

                            // Pisahkan Tanggal & Waktu dari waktu_absen
                            let {
                                tanggal,
                                jam
                            } = formatDateTime(item.waktu_absen);

                            // Tentukan ikon dan warna berdasarkan jenis absen
                            let iconJenis = formatIconJenisAbsen(item.jenis_absen);
                            let warnaIkon = (item.jenis_absen === "masuk" || item.jenis_absen ===
                                    "keluar") ?
                                "green" : "red"; // Masuk & Keluar hijau, lainnya merah

                            li.innerHTML = `
                        <div>
                            <strong>📅 ${tanggal}</strong> <br>
                            🕒 <span class="fw-bold">${jam}</span> <br>
                            📌 <span class="fw-bold">${formatJenisAbsen(item.jenis_absen)}</span> - 
                            <span class="fw-bold">${item.status}</span>
                        </div>
                        <ion-icon name="${iconJenis}" 
                            style="font-size: 24px; color: ${warnaIkon}">
                        </ion-icon>
                    `;
                            riwayatAbsensi.appendChild(li);
                        });
                    } else {
                        riwayatAbsensi.innerHTML =
                            "<li class='list-group-item text-center text-muted'>Tidak ada data</li>";
                    }
                } catch (error) {
                    console.error("Error mengambil data absensi:", error);
                    riwayatAbsensi.innerHTML =
                        "<li class='list-group-item text-center text-danger'>Gagal mengambil data</li>";
                }
            }

            // Fungsi untuk memformat waktu_absen menjadi { tanggal: "2025-02-13", jam: "00:11:30" }
            function formatDateTime(datetime) {
                let date = new Date(datetime);
                let tanggal = date.toISOString().split("T")[0]; // Format YYYY-MM-DD
                let jam = date.toTimeString().split(" ")[0]; // Format HH:MM:SS
                return {
                    tanggal,
                    jam
                };
            }

            // Fungsi untuk mendapatkan tahun dan bulan dari waktu_absen
            function extractYearMonth(datetime) {
                let date = new Date(datetime);
                let tahunAbsen = date.getFullYear();
                let bulanAbsen = (date.getMonth() + 1).toString().padStart(2,
                    "0"); // Tambahkan "0" jika satu digit
                return {
                    tahunAbsen,
                    bulanAbsen
                };
            }

            // Fungsi untuk memformat jenis absen agar lebih mudah dibaca
            function formatJenisAbsen(jenis) {
                const mapping = {
                    "masuk": "Masuk",
                    "keluar": "Pulang",
                    "tidak_masuk": "Tidak Masuk",
                    "izin": "Izin",
                    "sakit": "Sakit"
                };
                return mapping[jenis] || jenis; // Jika tidak ditemukan, pakai aslinya
            }

            // Fungsi untuk menentukan ikon berdasarkan jenis absen
            function formatIconJenisAbsen(jenis) {
                const iconMapping = {
                    "masuk": "log-in-outline", // Ikon masuk
                    "keluar": "log-out-outline", // Ikon pulang
                    "tidak_masuk": "close-circle-outline", // Ikon tidak masuk
                    "izin": "document-text-outline", // Ikon izin
                    "sakit": "medkit-outline" // Ikon sakit
                };
                return iconMapping[jenis] ||
                    "help-circle-outline"; // Jika tidak ditemukan, gunakan ikon default
            }
        });
    </script>
@endsection
