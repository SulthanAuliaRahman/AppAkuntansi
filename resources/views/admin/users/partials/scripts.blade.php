{{-- resources/views/admin/users/partials/scripts.blade.php --}}
<script>
/**
 * ============================================================
 *  MODAL HELPERS
 * ============================================================
 */
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (!modal) return;

    modal.classList.add('open');

    requestAnimationFrame(() => requestAnimationFrame(() => {
        const backdrop = modal.querySelector('.modal-backdrop');
        const panel    = modal.querySelector('.modal-panel');
        if (backdrop) backdrop.classList.replace('opacity-0', 'opacity-100');
        if (panel) {
            panel.classList.remove('opacity-0', 'translate-y-4', 'sm:scale-95');
            panel.classList.add('opacity-100', 'translate-y-0', 'sm:scale-100');
        }
    }));

    const backdrop = modal.querySelector('.modal-backdrop');
    if (backdrop) backdrop.onclick = () => closeModal(modalId);
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (!modal) return;

    const backdrop = modal.querySelector('.modal-backdrop');
    const panel    = modal.querySelector('.modal-panel');
    if (backdrop) backdrop.classList.replace('opacity-100', 'opacity-0');
    if (panel) {
        panel.classList.remove('opacity-100', 'translate-y-0', 'sm:scale-100');
        panel.classList.add('opacity-0', 'translate-y-4', 'sm:scale-95');
    }

    setTimeout(() => modal.classList.remove('open'), 300);
}

// Close modal on Escape key
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        document.querySelectorAll('.modal-wrapper.open').forEach(modal => {
            closeModal(modal.id);
        });
    }
});

/**
 * ============================================================
 *  MODAL USER — tambah / edit
 * ============================================================
 */
function openModalEditUser(id, name, email, roleId, updateUrl) {
    // Pre-fill form fields
    document.getElementById('user-name').value  = name;
    document.getElementById('user-email').value = email;
    document.getElementById('user-role').value  = roleId;
    document.getElementById('user-password').value = '';

    // Switch to PUT
    document.getElementById('form-user').action       = updateUrl;
    document.getElementById('form-user-method').value = 'PUT';

    // UI tweaks: password optional, title text
    document.getElementById('user-password').required = false;
    document.getElementById('modal-user-pass-hint').classList.remove('hidden');
    document.getElementById('modal-user-title-text').textContent = 'Edit Pengguna';

    openModal('modal-user');
}

// Reset modal-user ke mode Tambah ketika dibuka tanpa argumen edit
const _origOpenModal = openModal;
document.getElementById('modal-user')?.addEventListener('transitionend', () => {});

// Override tombol "Tambah User" agar reset form ke mode POST
function resetModalUser() {
    document.getElementById('form-user').reset();
    document.getElementById('form-user').action       = "{{ route('admin.users.store') }}";
    document.getElementById('form-user-method').value = 'POST';
    document.getElementById('user-password').required = true;
    document.getElementById('modal-user-pass-hint').classList.add('hidden');
    document.getElementById('modal-user-title-text').textContent = 'Tambah Pengguna Baru';
    openModal('modal-user');
}

/**
 * ============================================================
 *  MODAL ROLE — tambah / edit
 * ============================================================
 */
function openModalEditRole(id, namaRole, deskripsi, isFullAccess, updateUrl) {
    document.getElementById('role-nama').value        = namaRole;
    document.getElementById('role-deskripsi').value   = deskripsi;
    document.getElementById('role-full-access').checked = isFullAccess;

    document.getElementById('form-role').action       = updateUrl;
    document.getElementById('form-role-method').value = 'PUT';

    document.getElementById('modal-role-title-text').textContent = 'Edit Role';
    document.getElementById('modal-role-icon').className =
        'bg-amber-100 text-amber-600 p-2 rounded-lg';

    openModal('modal-role');
}

function resetModalRole() {
    document.getElementById('form-role').reset();
    document.getElementById('form-role').action       = "{{ route('admin.roles.store') }}";
    document.getElementById('form-role-method').value = 'POST';
    document.getElementById('modal-role-title-text').textContent = 'Form Master Role';
    document.getElementById('modal-role-icon').className =
        'bg-violet-100 text-violet-600 p-2 rounded-lg';
    openModal('modal-role');
}

/**
 * ============================================================
 *  DELETE CONFIRM
 *  tab: tab mana yang harus aktif setelah redirect
 * ============================================================
 */
function confirmDelete(name, deleteUrl, tab = 'users') {
    document.getElementById('delete-target-name').textContent   = name;
    document.getElementById('form-delete').action               = deleteUrl;
    document.getElementById('delete-redirect-tab').value        = tab;
    openModal('modal-confirm-delete');
}

/**
 * ============================================================
 *  FILTER TABLE (client-side search)
 * ============================================================
 */
function filterTable(tableId, query) {
    const rows = document.querySelectorAll(`#${tableId} tbody tr`);
    const q    = query.toLowerCase().trim();
    let visible = 0;

    rows.forEach(row => {
        const match = row.textContent.toLowerCase().includes(q);
        row.style.display = match ? '' : 'none';
        if (match) visible++;
    });

    const empty = document.getElementById('users-empty');
    if (empty) empty.classList.toggle('hidden', visible > 0 || q === '');
}

/**
 * ============================================================
 *  PASSWORD TOGGLE
 * ============================================================
 */
function togglePasswordVisibility(btn) {
    const input = btn.closest('.relative').querySelector('input[type]');
    const icon  = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}

/**
 * ============================================================
 *  AKSES AKUN CHECKLIST
 * ============================================================
 */
function updateAksesCount() {
    const count = document.querySelectorAll('.akun-checkbox:checked').length;
    const badge = document.getElementById('akses-count');
    if (badge) badge.textContent = count + ' dipilih';
}

function toggleAllAkun(checked) {
    document.querySelectorAll('.akun-checkbox').forEach(cb => cb.checked = checked);
    updateAksesCount();
}

/**
 * ============================================================
 *  INIT
 * ============================================================
 */
document.addEventListener('DOMContentLoaded', () => {
    updateAksesCount();

    // Patch tombol "Tambah User" dan "Tambah Role" agar reset dulu
    document.querySelectorAll('[data-open-user]').forEach(btn => {
        btn.addEventListener('click', resetModalUser);
    });
    document.querySelectorAll('[data-open-role]').forEach(btn => {
        btn.addEventListener('click', resetModalRole);
    });
});
</script>
