@extends('layouts.app')

@section('title', 'Manajemen Pesanan')

@section('content')

{{-- Notifikasi --}}
<div id="alert-box" class="alert d-none shadow-sm rounded-3" role="alert"></div>

{{-- Header --}}
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <div>
        <h3 class="fw-bold mb-0 text-dark">Daftar Pesanan</h3>
        <p class="text-muted mb-0 small">Kelola semua data pesanan pelanggan Anda di sini.</p>
    </div>
    <button class="btn btn-primary shadow-sm rounded-3 px-3 py-2" data-bs-toggle="modal" data-bs-target="#modalOrder" onclick="resetForm()">
        <i class="bi bi-plus-circle me-2"></i>Tambah Pesanan
    </button>
</div>

{{-- Tabel Container --}}
<div class="card border-0 shadow-sm rounded-3">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="tabel-order">
                <thead class="table-light text-secondary">
                    <tr>
                        <th class="ps-4">#</th>
                        <th>Nama Pemesan</th>
                        <th>No. WhatsApp</th>
                        <th>Email</th>
                        <th>Produk</th>
                        <th>Jumlah</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tbody-order" class="border-top-0">
                    @foreach ($orders as $i => $order)
                    <tr id="row-{{ $order->id }}">
                        <td class="ps-4 text-muted">{{ $i + 1 }}</td>
                        <td class="fw-semibold">{{ $order->nama_pemesan }}</td>
                        <td>{{ $order->nomor_wa }}</td>
                        <td>{{ $order->email }}</td>
                        <td>{{ $order->nama_produk }}</td>
                        <td>{{ $order->jumlah }}</td>
                        <td>
                            <span class="badge rounded-pill fw-normal px-3 py-2 
                                {{ $order->status === 'baru' ? 'bg-secondary' : ($order->status === 'diproses' ? 'bg-warning text-dark' : 'bg-success') }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td class="text-muted small">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        <td class="text-end pe-4">
                            <button class="btn btn-sm btn-light text-primary me-1 rounded-3" onclick="editOrder('{{ $order->id }}')" title="Edit">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                            <button class="btn btn-sm btn-light text-danger rounded-3" onclick="deleteOrder('{{ $order->id }}')" title="Hapus">
                                <i class="bi bi-trash3"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal Form --}}
<div class="modal fade" id="modalOrder" tabindex="-1" aria-labelledby="modal-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-bottom-0 pb-0 pt-4 px-4">
                <h5 class="modal-title fw-bold" id="modal-title">Tambah Pesanan</h5>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4 pt-3 pb-4">
                <form id="form-order">
                    <input type="hidden" id="order-id">

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-medium text-secondary small">Nama Pemesan</label>
                            <input type="text" class="form-control rounded-3 shadow-none" id="nama_pemesan" placeholder="Masukkan nama..." required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium text-secondary small">Nomor WhatsApp</label>
                            <input type="text" class="form-control rounded-3 shadow-none" id="nomor_wa" placeholder="Contoh: 08123456789" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium text-secondary small">Email</label>
                            <input type="email" class="form-control rounded-3 shadow-none" id="email" placeholder="email@contoh.com" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium text-secondary small">Nama Produk</label>
                            <input type="text" class="form-control rounded-3 shadow-none" id="nama_produk" placeholder="Nama produk..." required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium text-secondary small">Jumlah</label>
                            <input type="number" class="form-control rounded-3 shadow-none" id="jumlah" min="1" value="1" required>
                        </div>
                        <div class="col-md-6" id="status-group" style="display:none">
                            <label class="form-label fw-medium text-secondary small">Status</label>
                            <select class="form-select rounded-3 shadow-none" id="status">
                                <option value="baru">Baru</option>
                                <option value="diproses">Diproses</option>
                                <option value="selesai">Selesai</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-top-0 px-4 pb-4 pt-0">
                <button type="button" class="btn btn-light rounded-3 px-4" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary rounded-3 px-4 shadow-sm" id="btn-save" onclick="saveOrder()">Simpan Pesanan</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    const BASE = '/api/orders';
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    function showAlert(message, type = 'success') {
        const box = document.getElementById('alert-box');
        box.className = `alert alert-${type} alert-dismissible fade show shadow-sm rounded-3`;
        box.innerHTML = `<strong>${type === 'success' ? 'Berhasil!' : 'Perhatian!'}</strong> ${message} <button type="button" class="btn-close" data-bs-dismiss="alert"></button>`;
        setTimeout(() => {
            box.classList.remove('show');
            setTimeout(() => box.classList.add('d-none'), 150);
        }, 4000);
    }

    function resetForm() {
        document.getElementById('order-id').value = '';
        document.getElementById('form-order').reset();
        document.getElementById('modal-title').textContent = 'Tambah Pesanan';
        document.getElementById('status-group').style.display = 'none';
    }

    function badgeClass(status) {
        return status === 'baru' ? 'bg-secondary' :
            status === 'diproses' ? 'bg-warning text-dark' :
            'bg-success';
    }

    function renderRow(o, index) {
        const date = new Date(o.created_at).toLocaleString('id-ID', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });

        return `
        <tr id="row-${o.id}">
            <td class="ps-4 text-muted">${index}</td>
            <td class="fw-semibold">${o.nama_pemesan}</td>
            <td>${o.nomor_wa}</td>
            <td>${o.email}</td>
            <td>${o.nama_produk}</td>
            <td>${o.jumlah}</td>
            <td><span class="badge rounded-pill fw-normal px-3 py-2 ${badgeClass(o.status)}">${o.status.charAt(0).toUpperCase() + o.status.slice(1)}</span></td>
            <td class="text-muted small">${date}</td>
            <td class="text-end pe-4">
                <button class="btn btn-sm btn-light text-primary me-1 rounded-3" onclick="editOrder('${o.id}')" title="Edit">
                    <i class="bi bi-pencil-square"></i>
                </button>
                <button class="btn btn-sm btn-light text-danger rounded-3" onclick="deleteOrder('${o.id}')" title="Hapus">
                    <i class="bi bi-trash3"></i>
                </button>
            </td>
        </tr>`;
    }

    function reloadTable() {
        fetch(BASE, {
                headers: {
                    'Accept': 'application/json'
                }
            })
            .then(r => r.json())
            .then(res => {
                const tbody = document.getElementById('tbody-order');
                tbody.innerHTML = res.data.map((o, i) => renderRow(o, i + 1)).join('');
            });
    }

    function saveOrder() {
        const id = document.getElementById('order-id').value;
        const payload = {
            nama_pemesan: document.getElementById('nama_pemesan').value,
            nomor_wa: document.getElementById('nomor_wa').value,
            email: document.getElementById('email').value,
            nama_produk: document.getElementById('nama_produk').value,
            jumlah: document.getElementById('jumlah').value,
            status: document.getElementById('status').value,
        };

        const method = id ? 'PUT' : 'POST';
        const url = id ? `${BASE}/${id}` : BASE;
        const btnSave = document.getElementById('btn-save');

        // Loading state
        const originalText = btnSave.innerHTML;
        btnSave.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Menyimpan...';
        btnSave.disabled = true;

        fetch(url, {
                method,
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify(payload),
            })
            .then(r => r.json())
            .then(res => {
                if (res.success) {
                    bootstrap.Modal.getInstance(document.getElementById('modalOrder')).hide();
                    showAlert(res.message);
                    reloadTable();
                } else {
                    showAlert(JSON.stringify(res.errors), 'danger');
                }
            })
            .catch(() => showAlert('Terjadi kesalahan sistem.', 'danger'))
            .finally(() => {
                btnSave.innerHTML = originalText;
                btnSave.disabled = false;
            });
    }

    function editOrder(id) {
        fetch(`${BASE}/${id}`, {
                headers: {
                    'Accept': 'application/json'
                }
            })
            .then(r => r.json())
            .then(res => {
                const o = res.data;
                document.getElementById('order-id').value = o.id;
                document.getElementById('nama_pemesan').value = o.nama_pemesan;
                document.getElementById('nomor_wa').value = o.nomor_wa;
                document.getElementById('email').value = o.email;
                document.getElementById('nama_produk').value = o.nama_produk;
                document.getElementById('jumlah').value = o.jumlah;
                document.getElementById('status').value = o.status;
                document.getElementById('status-group').style.display = 'block';
                document.getElementById('modal-title').textContent = 'Edit Pesanan';
                new bootstrap.Modal(document.getElementById('modalOrder')).show();
            });
    }

    function deleteOrder(id) {
        Swal.fire({
            title: 'Hapus Pesanan?',
            text: 'Data yang dihapus tidak dapat dikembalikan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true,
        }).then((result) => {
            if (!result.isConfirmed) return;

            fetch(`${BASE}/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                })
                .then(r => r.json())
                .then(res => {
                    if (res.success) {
                        document.getElementById(`row-${id}`).remove();
                        Swal.fire({
                            title: 'Terhapus!',
                            text: res.message,
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false,
                        });
                    }
                });
        });
    }
</script>
@endsection