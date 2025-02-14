@extends('Layout.presensi')

@section('header')
    <div class="appHeader bg-primary text-light">
        <div class="left">
            <a href="/user" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">Presensi Keluar</div>
        <div class="right"></div>
    </div>
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center" style="margin-top: 50px;">
            <div class="col-12 text-center">
                <div class="webcam-container">
                    <div class="webcam-capture"></div>
                </div>
                <button id="capture-btn" class="btn btn-primary mt-3">Ambil Gambar & Kirim Presensi</button>
            </div>
        </div>
    </div>
@endsection

@push('myscript')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            setCameraSize();
            window.addEventListener("resize", setCameraSize);
            checkAttendanceStatus();

            document.getElementById('capture-btn').addEventListener('click', function() {
                Webcam.snap(function(data_uri) {
                    sendAttendance(data_uri);
                });
            });
        });

        function setCameraSize() {
            let screenWidth = window.innerWidth;
            let screenHeight = window.innerHeight;

            let width = screenWidth > 640 ? 640 : screenWidth * 0.9;
            let height = screenHeight * 0.5;

            Webcam.set({
                width: width,
                height: height,
                image_format: 'jpeg',
                jpeg_quality: 90
            });

            Webcam.attach('.webcam-capture');
        }


        async function sendAttendance(imageData) {
            let token = localStorage.getItem('auth_token');
            if (!token) {
                Swal.fire({
                    icon: 'error',
                    title: 'Akses Ditolak!',
                    text: 'Silakan login terlebih dahulu.',
                    confirmButtonColor: '#3085d6',
                }).then(() => {
                    window.location.href = "/";
                });
                return;
            }

            if (!navigator.geolocation) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Fitur Tidak Tersedia!',
                    text: 'Perangkat ini tidak mendukung geolokasi.',
                });
                return;
            }

            navigator.geolocation.getCurrentPosition(async function(position) {
                    console.log("Lokasi User:", position.coords.latitude, position.coords.longitude);

                    let formData = new FormData();
                    formData.append("jenis_absen", "keluar");
                    formData.append("latitude", position.coords.latitude);
                    formData.append("longitude", position.coords.longitude);
                    formData.append("image", dataURItoBlob(imageData));

                    try {
                        let response = await fetch('http://127.0.0.1:8000/api/karyawan-Absen', {
                            method: "POST",
                            headers: {
                                "Authorization": "Bearer " + token
                            },
                            body: formData
                        });

                        let result = await response.json();
                        console.log("Response dari Server:", result);

                        if (response.ok) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Presensi Keluar Berhasil!',
                                text: result.message,
                                showConfirmButton: false,
                                timer: 2000
                            }).then(() => {
                                window.location.href = "/user"; // Redirect ke halaman user
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: result.message || "Terjadi kesalahan saat presensi.",
                            });
                        }
                    } catch (error) {
                        console.error("Error:", error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops!',
                            text: "Terjadi kesalahan saat mengirim presensi.",
                        });
                    }
                },
                function(error) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Lokasi Tidak Dapat Diakses!',
                        text: 'Pastikan GPS diaktifkan dan coba lagi.',
                    });
                });
        }

        async function checkAttendanceStatus() {
            let token = localStorage.getItem('auth_token');
            if (!token) {
                window.location.href = "/";
                return;
            }

            try {
                let response = await fetch('http://127.0.0.1:8000/api/karyawan/daily-status', {
                    method: "GET",
                    headers: {
                        "Authorization": "Bearer " + token,
                        "Accept": "application/json"
                    }
                });

                let result = await response.json();
                console.log("Status Absen:", result);

                let captureBtn = document.getElementById('capture-btn');

                // Jika belum absen masuk, tidak boleh absen keluar
                if (result.data.status === "Tidak Hadir") {
                    Swal.fire({
                        icon: 'error',
                        title: 'Presensi Tidak Dapat Dilakukan!',
                        text: 'Anda belum melakukan presensi masuk hari ini.',
                    });
                    captureBtn.disabled = true;
                    captureBtn.innerText = "Absen keluar tidak tersedia";
                    captureBtn.classList.remove("btn-primary");
                    captureBtn.classList.add("btn-secondary");
                }

                // Jika sudah absen keluar, disable tombol
                if (result.data.absensi_keluar) {
                    captureBtn.disabled = true;
                    captureBtn.innerText = "Anda sudah absen keluar";
                    captureBtn.classList.remove("btn-primary");
                    captureBtn.classList.add("btn-secondary");

                    Swal.fire({
                        icon: 'info',
                        title: 'Anda sudah absen keluar!',
                        text: 'Tidak perlu melakukan presensi ulang.',
                        confirmButtonColor: '#3085d6',
                    });
                }
            } catch (error) {
                console.error("Error mengecek status absen:", error);
            }
        }

        function dataURItoBlob(dataURI) {
            let byteString = atob(dataURI.split(',')[1]);
            let mimeString = dataURI.split(',')[0].split(':')[1].split(';')[0];
            let ab = new ArrayBuffer(byteString.length);
            let ia = new Uint8Array(ab);
            for (let i = 0; i < byteString.length; i++) {
                ia[i] = byteString.charCodeAt(i);
            }
            return new Blob([ab], {
                type: mimeString
            });
        }
    </script>
@endpush
