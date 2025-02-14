@extends('Layout.presensi')

@section('header')
    <div class="appHeader bg-primary text-light">
        <div class="left">
            <a href="/user" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="section">
            <h3 class="text-center text-white fw-bold">📅 Pengajuan Cuti</h3>
        </div>

    </div>
@endsection

@section('content')
    <div id="appCapsule" style="margin-top: 80px;">


        <div class="section mt-2">
            <div class="card">
                <div class="card-body">
                    <form id="formCuti">
                        <div class="mb-3">
                            <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                            <input type="date" class="form-control" id="tanggal_mulai" required>
                        </div>

                        <div class="mb-3">
                            <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                            <input type="date" class="form-control" id="tanggal_selesai" required>
                        </div>

                        <div class="mb-3">
                            <label for="jenis_izin" class="form-label">Jenis Izin</label>
                            <select class="form-control" id="jenis_izin" required>
                                <option value="Cuti Tahunan">Cuti Tahunan</option>
                                <option value="Cuti Sakit">Cuti Sakit</option>
                                <option value="Cuti Bersalin">Cuti Bersalin</option>
                                <option value="Cuti Lainnya">Cuti Lainnya</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Ajukan Cuti</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="section mt-3">
            <div id="responseMessage" class="alert d-none"></div>
        </div>
    </div>
@endsection
@push('myscript')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let token = localStorage.getItem('auth_token');

            if (!token) {
                window.location.href = "/";
                return;
            }

            document.getElementById("formCuti").addEventListener("submit", async function(event) {
                event.preventDefault();

                let tanggalMulai = document.getElementById("tanggal_mulai").value;
                let tanggalSelesai = document.getElementById("tanggal_selesai").value;
                let jenisIzin = document.getElementById("jenis_izin").value;
                let responseMessage = document.getElementById("responseMessage");

                try {
                    let response = await fetch("http://127.0.0.1:8000/api/izin-karyawan", {
                        method: "POST",
                        headers: {
                            "Authorization": "Bearer " + token,
                            "Accept": "application/json",
                            "Content-Type": "application/json",
                        },
                        body: JSON.stringify({
                            "tanggal_mulai": tanggalMulai,
                            "tanggal_selsai": tanggalSelesai,
                            "jenis_izin": jenisIzin
                        }),
                    });

                    let result = await response.json();

                    if (response.ok) {
                        responseMessage.classList.remove("d-none", "alert-danger");
                        responseMessage.classList.add("alert-success");
                        responseMessage.innerText = "Pengajuan cuti berhasil!";
                    } else {
                        responseMessage.classList.remove("d-none", "alert-success");
                        responseMessage.classList.add("alert-danger");
                        responseMessage.innerText = result.message || "Gagal mengajukan cuti.";
                    }
                } catch (error) {
                    responseMessage.classList.remove("d-none", "alert-success");
                    responseMessage.classList.add("alert-danger");
                    responseMessage.innerText = "Terjadi kesalahan. Silakan coba lagi.";
                    console.error("Error:", error);
                }
            });
        });
    </script>
@endpush
