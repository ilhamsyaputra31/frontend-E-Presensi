<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="theme-color" content="#000000">
    <title>Jayamahe-Attendance - Register</title>
    <meta name="description" content="Mobilekit HTML Mobile UI Kit">
    <meta name="keywords" content="bootstrap 4, mobile template, cordova, phonegap, mobile, html">
    <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon.png') }}" sizes="32x32">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>

<body class="bg-white">

    <!-- App Capsule -->
    <div id="appCapsule" class="pt-0">
        <div class="login-form mt-1">
            <div class="section">
                <img src="{{ asset('assets/img/sample/photo/vector4.png') }}" alt="image" class="form-image">
            </div>
            <div class="section mt-1">
                <h1>Register</h1>
                <h4>Fill the form to create an account</h4>
            </div>
            <div class="section mt-1 mb-5">
                <form id="registerForm">
                    <div class="form-group boxed">
                        <div class="input-wrapper">
                            <input type="text" class="form-control" id="name" placeholder="Full Name" required>
                            <i class="clear-input"><ion-icon name="close-circle"></ion-icon></i>
                        </div>
                    </div>

                    <div class="form-group boxed">
                        <div class="input-wrapper">
                            <input type="email" class="form-control" id="email" placeholder="Email address"
                                required>
                            <i class="clear-input"><ion-icon name="close-circle"></ion-icon></i>
                        </div>
                    </div>

                    <div class="form-group boxed">
                        <div class="input-wrapper">
                            <input type="password" class="form-control" id="password" placeholder="Password" required>
                            <i class="clear-input"><ion-icon name="close-circle"></ion-icon></i>
                        </div>
                    </div>

                    <div class="form-group boxed">
                        <div class="input-wrapper">
                            <input type="password" class="form-control" id="confirmPassword"
                                placeholder="Confirm Password" required>
                            <i class="clear-input"><ion-icon name="close-circle"></ion-icon></i>
                        </div>
                    </div>

                    <div class="form-group boxed">
                        <div class="input-wrapper">
                            <select class="form-control" id="cabang_id" required>
                                <option value="">Memuat cabang...</option> <!-- Opsi awal sebelum data dimuat -->
                            </select>
                        </div>
                    </div>

                    <div class="form-group boxed">
                        <div class="input-wrapper">
                            <input type="file" class="form-control" id="faceImage" accept="image/*" required>
                            <i class="clear-input"><ion-icon name="close-circle"></ion-icon></i>
                        </div>
                    </div>

                    <!-- Tampilkan error atau pesan sukses -->
                    <p id="message" class="text-danger text-center mt-2"></p>

                    <div class="form-button-group">
                        <button type="submit" id="registerButton" class="btn btn-primary btn-block btn-lg">
                            Register
                        </button>
                    </div>

                    <div class="form-links mt-2">
                        <div>
                            <a href="/">Already have an account? Login</a>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
    <!-- * App Capsule -->

    <script>
        async function loadCabangOptions() {
            let cabangSelect = document.getElementById('cabang_id');

            try {
                console.log("🔄 Mengambil data cabang dari API...");
                let response = await fetch('http://127.0.0.1:8000/api/cabangs');
                let result = await response.json();
                console.log("📌 Response dari API:", result);

                // Pastikan API mengembalikan array daftar cabang
                if (response.ok && result.data && result.data.data.length > 0) {
                    cabangSelect.innerHTML = '<option value="">Pilih Cabang</option>';

                    result.data.data.forEach(cabang => { // Ambil data dari pagination
                        let option = document.createElement('option');
                        option.value = cabang.id;
                        option.textContent = cabang.nama_cabang;
                        cabangSelect.appendChild(option);
                    });
                } else {
                    console.error("❌ Gagal mengambil data cabang:", result);
                    cabangSelect.innerHTML = '<option value="">Tidak ada cabang tersedia</option>';
                }
            } catch (error) {
                console.error("❌ Terjadi kesalahan saat mengambil data cabang:", error);
                cabangSelect.innerHTML = '<option value="">Gagal Memuat Cabang</option>';
            }
        }

        document.addEventListener("DOMContentLoaded", async function() {
            await loadCabangOptions();
        });



        document.getElementById('registerForm').addEventListener('submit', async function(event) {
            event.preventDefault();

            let name = document.getElementById('name').value.trim();
            let email = document.getElementById('email').value.trim();
            let password = document.getElementById('password').value.trim();
            let confirmPassword = document.getElementById('confirmPassword').value.trim();
            let cabangId = document.getElementById('cabang_id').value;
            let faceImage = document.getElementById('faceImage').files[0];
            let message = document.getElementById('message');
            let registerButton = document.getElementById('registerButton');

            if (!name || !email || !password || !confirmPassword || !cabangId || !faceImage) {
                message.innerText = "Semua field harus diisi!";
                return;
            }

            if (!validateEmail(email)) {
                message.innerText = "Format email tidak valid!";
                return;
            }

            if (password.length < 6) {
                message.innerText = "Password minimal 6 karakter!";
                return;
            }

            if (password !== confirmPassword) {
                message.innerText = "Password dan konfirmasi password tidak cocok!";
                return;
            }

            registerButton.disabled = true;
            message.innerText = "";

            let formData = new FormData();
            formData.append("name", name);
            formData.append("email", email);
            formData.append("password", password);
            formData.append("password_confirmation", confirmPassword);
            formData.append("cabang_id", cabangId);
            formData.append("face_image", faceImage);

            try {
                let response = await fetch('http://127.0.0.1:8000/api/register', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                let result = await response.json();

                if (response.ok) {
                    message.classList.remove('text-danger');
                    message.classList.add('text-success');
                    message.innerText = "Registrasi berhasil! Mengalihkan ke halaman login...";

                    setTimeout(() => {
                        window.location.href = "/";
                    }, 2000);
                } else {
                    message.classList.add('text-danger');
                    message.innerText = result.message || 'Registrasi gagal! Periksa kembali input Anda.';
                }
            } catch (error) {
                console.error('Error:', error);
                message.innerText = 'Terjadi kesalahan. Coba lagi nanti.';
            }

            // Aktifkan kembali tombol register
            registerButton.disabled = false;
        });

        function validateEmail(email) {
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        }
    </script>


    <script src="{{ asset('assets/js/lib/jquery-3.4.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/lib/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/lib/bootstrap.min.js') }}"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.0.0/dist/ionicons/ionicons.js"></script>

</body>

</html>
