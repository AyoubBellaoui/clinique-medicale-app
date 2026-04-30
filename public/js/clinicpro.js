/* ClinicPro — Main JS */

// ── Live search ──
const searchInput = document.getElementById('globalSearch');
const searchResults = document.getElementById('searchResults');

if (searchInput) {
    let timer;
    searchInput.addEventListener('input', function () {
        clearTimeout(timer);
        const q = this.value.trim();
        if (q.length < 2) { searchResults.classList.remove('open'); return; }
        timer = setTimeout(() => {
            fetch(`/search?q=${encodeURIComponent(q)}`)
                .then(r => r.json())
                .then(data => renderSearch(data));
        }, 280);
    });

    document.addEventListener('click', function (e) {
        if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
            searchResults.classList.remove('open');
        }
    });
}

function renderSearch(data) {
    const { patients, staff } = data;
    if (!patients.length && !staff.length) {
        searchResults.innerHTML = '<div style="padding:16px;text-align:center;font-size:13px;color:#a0c8bf;">Aucun résultat</div>';
        searchResults.classList.add('open');
        return;
    }
    let html = '';
    if (patients.length) {
        html += `<div class="search-results-section">
            <div class="search-results-label">Patients</div>`;
        patients.forEach(p => {
            html += `<a class="search-result-item" href="${p.url}">
                <div class="sr-icon"><svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg></div>
                <div><div class="sr-name">${p.nom}</div><div class="sr-sub">CIN: ${p.cin || '—'} · ${p.telephone || ''}</div></div>
            </a>`;
        });
        html += '</div>';
    }
    if (staff.length) {
        html += `<div class="search-results-section" style="border-top:1px solid rgba(52,168,140,.08)">
            <div class="search-results-label">Staff Médical</div>`;
        staff.forEach(s => {
            html += `<a class="search-result-item" href="${s.url}">
                <div class="sr-icon"><svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/></svg></div>
                <div><div class="sr-name">${s.nom}</div><div class="sr-sub">${s.specialite || ''} · ${s.role || ''}</div></div>
            </a>`;
        });
        html += '</div>';
    }
    searchResults.innerHTML = html;
    searchResults.classList.add('open');
}

// ── Toast notifications ──
function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = 'toast';
    const color = type === 'success' ? '#22c55e' : '#dc2626';
    toast.innerHTML = `
        <svg width="18" height="18" fill="none" stroke="${color}" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <span>${message}</span>`;
    document.body.appendChild(toast);
    setTimeout(() => { toast.style.opacity = '0'; toast.style.transform = 'translateY(10px)'; toast.style.transition = '.3s'; }, 3200);
    setTimeout(() => toast.remove(), 3600);
}

// ── Confirm delete ──
document.addEventListener('click', function (e) {
    const btn = e.target.closest('[data-confirm]');
    if (!btn) return;
    e.preventDefault();
    const msg = btn.dataset.confirm || 'Confirmer la suppression ?';
    if (confirm(msg)) {
        const form = btn.closest('form') || document.querySelector(btn.dataset.form);
        if (form) form.submit();
    }
});

// ── Active sidebar nav ──
(function () {
    const path = window.location.pathname;
    document.querySelectorAll('.nav-item').forEach(a => {
        const href = a.getAttribute('href');
        if (href && href !== '/' && path.startsWith(href)) {
            a.classList.add('active');
        }
    });
})();

// ── Mobile sidebar toggle ──
const sidebarToggle = document.getElementById('sidebarToggle');
const sidebar = document.querySelector('.sidebar');
if (sidebarToggle && sidebar) {
    sidebarToggle.addEventListener('click', () => sidebar.classList.toggle('open'));
}

// ── File upload label update ──
document.querySelectorAll('.file-upload-box input[type="file"]').forEach(input => {
    input.addEventListener('change', function () {
        const label = this.closest('.file-upload-box').querySelector('p');
        if (label && this.files.length) {
            label.textContent = this.files[0].name;
        }
    });
});

// ── Auto-dismiss alerts ──
setTimeout(() => {
    document.querySelectorAll('.alert').forEach(el => {
        el.style.transition = 'opacity .4s';
        el.style.opacity = '0';
        setTimeout(() => el.remove(), 400);
    });
}, 4500);
