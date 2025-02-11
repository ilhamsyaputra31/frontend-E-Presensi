<!doctype html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="theme-color" content="#000000">
    <title>Jayamahe-Attendace</title>
    <meta name="description" content="Mobilekit HTML Mobile UI Kit">
    <meta name="keywords" content="bootstrap 4, mobile template, cordova, phonegap, mobile, html" />
    <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon.png') }}" sizes="32x32">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/img/icon/192x192.png') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="manifest" href="__manifest.json">
</head>

<body class="bg-white">

    <!-- loader -->
    <div id="loader">
        <div class="spinner-border text-primary" role="status"></div>
    </div>
    <!-- * loader -->


    <!-- App Capsule -->
    <div id="appCapsule" class="pt-0">

        <div class="login-form mt-1">
            <div class="section">
                <img src="assets/img/sample/photo/vector4.png" alt="image" class="form-image">
            </div>
            <div class="section mt-1">
                <h1>Get started</h1>
                <h4>Fill the form to log in</h4>
            </div>
            <div class="section mt-1 mb-5">
                <form id="loginForm">
                    @csrf
                    <div class="form-group boxed">
                        <div class="input-wrapper">
                            <input type="email" class="form-control" id="email1" placeholder="Email address"
                                required>
                            <i class="clear-input">
                                <ion-icon name="close-circle"></ion-icon>
                            </i>
                        </div>
                    </div>

                    <div class="form-group boxed">
                        <div class="input-wrapper">
                            <input type="password" class="form-control" id="password1" placeholder="Password" required>
                            <i class="clear-input">
                                <ion-icon name="close-circle"></ion-icon>
                            </i>
                        </div>
                    </div>

                    <div class="form-links mt-2">
                        <div>
                            <a href="{{ route('register') }}">Register Now</a>
                        </div>
                        <div><a href="page-forgot-password.html" class="text-muted">Forgot Password?</a></div>
                    </div>


                    <!-- Tampilkan error di sini -->
                    <p id="errorMessage" class="text-danger text-center mt-2"></p>

                    <div class="form-button-group">
                        <button type="submit" class="btn btn-primary btn-block btn-lg">Log in</button>
                    </div>
                </form>
            </div>
        </div>


    </div>
    <!-- * App Capsule -->



    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Jika sudah login, redirect ke dashboard
            if (localStorage.getItem('auth_token')) {
                window.location.href = "/user";
            }
        });

        // Ambil elemen form
        const loginForm = document.getElementById('loginForm');

        if (loginForm) {
            loginForm.addEventListener('submit', async function(event) {
                event.preventDefault(); // Mencegah reload halaman

                let email = document.getElementById('email1').value.trim();
                let password = document.getElementById('password1').value.trim();
                let loader = document.getElementById('loader');
                let errorMessage = document.getElementById('errorMessage');

                // Validasi input
                if (!email || !password) {
                    errorMessage.innerText = "Email dan password tidak boleh kosong!";
                    return;
                }

                // Tampilkan loader
                loader.style.display = "block";
                errorMessage.innerText = ""; // Reset pesan error

                try {
                    let response = await fetch('http://127.0.0.1:8000/api/login', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            email,
                            password
                        })
                    });

                    let result = await response.json();

                    if (response.ok) {
                        localStorage.setItem('auth_token', result.token); // Simpan token JWT
                        window.location.href = "/user"; // Redirect ke dashboard
                    } else {
                        errorMessage.innerText = result.message || 'Login gagal! Periksa email dan password.';
                    }
                } catch (error) {
                    console.error('Error:', error);
                    errorMessage.innerText = 'Terjadi kesalahan. Coba lagi nanti.';
                }

                // Sembunyikan loader setelah proses selesai
                loader.style.display = "none";
            });
        }
    </script>



    <!-- ///////////// Js Files ////////////////////  -->
    <!-- Jquery -->
    <script src="{{ asset('assets/js/lib/jquery-3.4.1.min.js') }}"></script>
    <!-- Bootstrap-->
    <script src="{{ asset('assets/js/lib/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/lib/bootstrap.min.js') }}"></script>
    <!-- Ionicons -->
    <script type="module" src="https://unpkg.com/ionicons@5.0.0/dist/ionicons/ionicons.js"></script>
    <!-- Owl Carousel -->
    <script src="{{ asset('assets/js/plugins/owl-carousel/owl.carousel.min.js') }}"></script>
    <!-- jQuery Circle Progress -->
    <script src="{{ asset('assets/js/plugins/jquery-circle-progress/circle-progress.min.js') }}"></script>
    <!-- Base Js File -->
    <script src="{{ asset('assets/js/base.js') }}"></script>



</body>

</html>
