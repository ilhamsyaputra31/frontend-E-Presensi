@extends('Layout.presensi')
@section('content')
    <div id="appCapsule">
        <div class="section" id="user-section">
            <div id="user-detail">
                <div class="avatar">
                    <img src="assets/img/sample/avatar/avatar1.jpg" alt="avatar" class="imaged w64 rounded">
                </div>
                <div id="user-info">
                    <h2 id="user-name">Adam Abdi Al A'la</h2>
                    <span id="user-role">Head of IT</span>
                </div>
            </div>
        </div>

        <div class="section" id="menu-section">
            <div class="card">
                <div class="card-body text-center">
                    <div class="list-menu">
                        <div class="item-menu text-center">
                            <div class="menu-icon">
                                <a href="" class="green" style="font-size: 40px;">
                                    <ion-icon name="person-sharp"></ion-icon>
                                </a>
                            </div>
                            <div class="menu-name">
                                <span class="text-center">Profil</span>
                            </div>
                        </div>
                        <div class="item-menu text-center">
                            <div class="menu-icon">
                                <a href="" class="danger" style="font-size: 40px;">
                                    <ion-icon name="calendar-number"></ion-icon>
                                </a>
                            </div>
                            <div class="menu-name">
                                <span class="text-center">Cuti</span>
                            </div>
                        </div>
                        <div class="item-menu text-center">
                            <div class="menu-icon">
                                <a href="" class="warning" style="font-size: 40px;">
                                    <ion-icon name="document-text"></ion-icon>
                                </a>
                            </div>
                            <div class="menu-name">
                                <span class="text-center">Histori</span>
                            </div>
                        </div>
                        <div class="item-menu text-center">
                            <div class="menu-icon">
                                <a href="" class="orange" style="font-size: 40px;">
                                    <ion-icon name="location"></ion-icon>
                                </a>
                            </div>
                            <div class="menu-name">
                                Lokasi
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="section mt-2" id="presence-section">
            <div class="todaypresence">
                <div class="row">
                    <div class="col-6">
                        <button id="btn-masuk" class="w-100 border-0 p-0 bg-transparent">
                            <div class="card gradasigreen border-0 shadow-lg" style="background: rgb(4, 139, 10);">
                                <div class="card-body text-center py-3">
                                    <div class="presencecontent">
                                        <div class="iconpresence mb-2">
                                            <ion-icon name="camera" style="font-size: 40px; color: white;"></ion-icon>
                                        </div>
                                        <div class="presencedetail">
                                            <h4 class="presencetitle mb-1 text-white">Masuk</h4>
                                            <span class="text-white fw-bold">07:00</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </button>
                    </div>
                    <div class="col-6">
                        <button id="btn-keluar" class="w-100 border-0 p-0 bg-transparent">
                            <div class="card gradasired border-0 shadow-lg">
                                <div class="card-body text-center py-3">
                                    <div class="presencecontent">
                                        <div class="iconpresence mb-2">
                                            <ion-icon name="camera" style="font-size: 40px; color: white;"></ion-icon>
                                        </div>
                                        <div class="presencedetail">
                                            <h4 class="presencetitle mb-1 text-white">Pulang</h4>
                                            <span class="text-white fw-bold">12:00</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Status Absensi Diletakkan di Bawah Tombol -->
            <div class="section mt-3" id="status-absensi">
                <h4 class="text-center text-primary fw-bold">📋 Status Absensi Hari Ini</h4>
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-body text-center py-4">
                        <div id="status-container" class="mb-3">
                            <span id="status" class="badge bg-secondary px-3 py-2">Memuat status...</span>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="card border-0 shadow-sm py-3">
                                    <div class="card-body text-center">
                                        <ion-icon name="log-in-outline" style="font-size: 40px; color: green;"></ion-icon>
                                        <h5 class="mt-2 text-success">Masuk</h5>
                                        <p id="absensi_masuk" class="fw-bold text-success d-none">
                                            <ion-icon name="time-outline"></ion-icon> <span></span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="card border-0 shadow-sm py-3">
                                    <div class="card-body text-center">
                                        <ion-icon name="log-out-outline" style="font-size: 40px; color: red;"></ion-icon>
                                        <h5 class="mt-2 text-danger">Pulang</h5>
                                        <p id="absensi_keluar" class="fw-bold text-danger d-none">
                                            <ion-icon name="time-outline"></ion-icon> <span></span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", async function() {
            let token = localStorage.getItem('auth_token');

            if (!token) {
                window.location.href = "/";
                return;
            }

            async function checkSession() {
                try {
                    let response = await fetch('http://127.0.0.1:8000/api/user', {
                        method: 'GET',
                        headers: {
                            'Authorization': 'Bearer ' + token,
                            'Accept': 'application/json'
                        }
                    });

                    if (response.status === 401) {
                        console.warn("Token expired or invalid. Redirecting to login...");
                        localStorage.removeItem('auth_token');
                        window.location.href = "/";
                        return;
                    }

                    let result = await response.json();
                    if (response.ok) {
                        document.getElementById('user-name').innerText = result.user.name;
                        document.getElementById('user-role').innerText = result.user.role;
                    } else {
                        console.error('Gagal mengambil data user:', result);
                    }
                } catch (error) {
                    console.error("Error saat mengecek session:", error);
                }
            }

            async function loadDailyStatus() {
                try {
                    let token = localStorage.getItem('auth_token');
                    let response = await fetch('http://127.0.0.1:8000/api/karyawan-Absen/daily-status', {
                        method: 'GET',
                        headers: {
                            'Authorization': 'Bearer ' + token,
                            'Accept': 'application/json'
                        }
                    });

                    let result = await response.json();
                    console.log("Response dari API:", result); // Debug response

                    let statusContainer = document.getElementById('status-container');
                    let statusText = document.getElementById('status');
                    let masukTime = document.getElementById('absensi_masuk');
                    let keluarTime = document.getElementById('absensi_keluar');

                    if (result.status === "success") {
                        let data = result.data;

                        // Set status utama dengan warna berbeda
                        statusText.innerText = data.status;
                        statusText.classList.remove("bg-secondary", "bg-success", "bg-warning",
                            "bg-danger");

                        if (data.status === "Hadir") {
                            statusText.classList.add("bg-success");
                        } else if (data.status === "Terlambat") {
                            statusText.classList.add("bg-warning");
                        } else {
                            statusText.classList.add("bg-danger");
                        }

                        // Menampilkan waktu masuk jika ada
                        if (data.absensi_masuk) {
                            masukTime.classList.remove('d-none');
                            masukTime.querySelector('span').innerText = formatTime(data.absensi_masuk
                                .waktu_absen);
                        } else {
                            masukTime.classList.add('d-none'); // Sembunyikan jika tidak ada data
                        }

                        // Menampilkan waktu keluar jika ada
                        if (data.absensi_keluar) {
                            keluarTime.classList.remove('d-none');
                            keluarTime.querySelector('span').innerText = formatTime(data.absensi_keluar
                                .waktu_absen);
                        } else {
                            keluarTime.classList.add('d-none'); // Sembunyikan jika tidak ada data
                        }

                    } else {
                        statusText.innerText = "Gagal mengambil data";
                        statusText.classList.add("bg-danger");
                    }

                } catch (error) {
                    console.error("Error mengambil status absensi:", error);
                    document.getElementById('status').innerText = "Gagal mengambil data";
                }
            }

            // Fungsi untuk mengubah format waktu dari "2025-02-12 16:03:20" menjadi "16:03"
            function formatTime(datetime) {
                let date = new Date(datetime);
                let hours = date.getHours().toString().padStart(2, '0');
                let minutes = date.getMinutes().toString().padStart(2, '0');
                return `${hours}:${minutes}`;
            }

            // Panggil fungsi untuk memuat status absensi
            loadDailyStatus();




            checkSession();

            document.getElementById('btn-masuk').addEventListener('click', function() {
                window.location.href = "/presensi-masuk";
            });
            document.getElementById('btn-keluar').addEventListener('click', function() {
                window.location.href = "/presensi-keluar";
            });
        });
    </script>
@endsection
