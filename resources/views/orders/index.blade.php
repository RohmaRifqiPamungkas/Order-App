@extends('layouts.app')

@section('title', 'Manajemen Pesanan')

@section('content')

{{-- Notifikasi --}}
<div id="alert-box" class="alert d-none" role="alert"></div>

{{-- Header --}}
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="fw-bold mb-0">Daftar Pesanan</h4>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalOrder" onclick="resetForm()">
        <i class="bi bi-plus-lg me-1"></i> Tambah Pesanan
    </button>
</div>

{{-- Tabel --}}
<div class="card shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover table-striped mb-0" id="tabel-order">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Nama Pemesan</th>
                    <th>No. WhatsApp</th>
                    <th>Email</th>
                    <th>Produk</th>
                    <th>Jumlah</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="tbody-order">
                @foreach ($orders as $i => $order)
                <tr id="row-{{ $order->id }}">
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $order->nama_pemesan }}</td>
                    <td>{{ $order->nomor_wa }}</td>
                    <td>{{ $order->email }}</td>
                    <td>{{ $order->nama_produk }}</td>
                    <td>{{ $order->jumlah }}</td>
                    <td>
                        <span class="badge
                            {{ $order->status === 'baru' ? 'bg-secondary' : ($order->status === 'diproses' ? 'bg-warning text-dark' : 'bg-success') }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                    <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <button class="btn btn-sm btn-warning me-1" onclick="editOrder('{{ $order->id }}')">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button class="btn btn-sm btn-danger" onclick="deleteOrder('{{ $order->id }}')">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- Modal Form --}}
<div class="modal fade" id="modalOrder" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-title">Tambah Pesanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="form-order">
                    <input type="hidden" id="order-id">

                    <div class="mb-3">
                        <label class="form-label">Nama Pemesan</label>
                        <input type="text" class="form-control" id="nama_pemesan" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nomor WhatsApp</label>
                        <input type="text" class="form-control" id="nomor_wa" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Produk</label>
                        <input type="text" class="form-control" id="nama_produk" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jumlah</label>
                        <input type="number" class="form-control" id="jumlah" min="1" required>
                    </div>
                    <div class="mb-3" id="status-group" style="display:none">
                        <label class="form-label">Status</label>
                        <select class="form-select" id="status">
                            <option value="baru">Baru</option>
                            <option value="diproses">Diproses</option>
                            <option value="selesai">Selesai</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="btn-save" onclick="saveOrder()">Simpan</button>
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
        box.className = `alert alert-${type} alert-dismissible fade show`;
        box.innerHTML = `${message} <button type="button" class="btn-close" data-bs-dismiss="alert"></button>`;
        setTimeout(() => box.classList.add('d-none'), 4000);
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
        return `<tr id="row-${o.id}">
        <td>${index}</td>
        <td>${o.nama_pemesan}</td>
        <td>${o.nomor_wa}</td>
        <td>${o.email}</td>
        <td>${o.nama_produk}</td>
        <td>${o.jumlah}</td>
        <td><span class="badge ${badgeClass(o.status)}">${o.status.charAt(0).toUpperCase() + o.status.slice(1)}</span></td>
        <td>${date}</td>
        <td>
            <button class="btn btn-sm btn-warning me-1" onclick="editOrder('${o.id}')"><i class="bi bi-pencil"></i></button>
            <button class="btn btn-sm btn-danger" onclick="deleteOrder('${o.id}')"><i class="bi bi-trash"></i></button>
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
            .catch(() => showAlert('Terjadi kesalahan.', 'danger'));
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
        if (!confirm('Yakin hapus pesanan ini?')) return;

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
                    showAlert(res.message);
                }
            });
    }
</script>
@endsection