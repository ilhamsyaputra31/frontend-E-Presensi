@extends('Layout.presensi')

@section('header')
    <div class="appHeader bg-primary text-light">
        <div class="left">
            <a href="/user" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">Presensi Masuk</div>
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
                <canvas id="canvas" style="display: none;"></canvas>
            </div>
        </div>
    </div>
@endsection

@push('myscript')
    <script>
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

        document.addEventListener("DOMContentLoaded", function() {
            setCameraSize();
            window.addEventListener("resize", setCameraSize);

            document.getElementById('capture-btn').addEventListener('click', function() {
                Webcam.snap(function(data_uri) {
                    sendAttendance(data_uri);
                });
            });
        });

        function sendAttendance(imageData) {
            let token = localStorage.getItem('auth_token');
            if (!token) {
                alert("Silakan login terlebih dahulu.");
                window.location.href = "/";
                return;
            }

            navigator.geolocation.getCurrentPosition(async function(position) {
                    let formData = new FormData();
                    formData.append("jenis_absen", "masuk");
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
                        if (response.ok) {
                            alert(result.message);
                        } else {
                            alert("Gagal: " + result.message);
                        }
                    } catch (error) {
                        console.error("Error:", error);
                        alert("Terjadi kesalahan saat mengirim presensi.");
                    }
                },
                function(error) {
                    alert("Tidak dapat mengambil lokasi. Aktifkan GPS Anda.");
                });
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
