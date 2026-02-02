let currentView = localStorage.getItem('gymView') || 'list';
let currentData = [];

const listContainer = document.getElementById('listView');
const cardContainer = document.getElementById('cardView');
const tableBody = document.getElementById('memberTableBody');
const btnList = document.getElementById('btnList');
const btnCard = document.getElementById('btnCard');

function renderList() {
    let html = '';
    currentData.forEach(m => {
        let badge = (m.status === 'Active') ? 'badge-active' : 'badge-expired';
        html += `<tr>
            <td><img src="uploads/${m.photo}" style="width:50px; height:50px; border-radius:12px; object-fit:cover;"></td>
            <td><div style="font-weight:800; color:var(--accent-pink);">${m.full_name}</div><div style="font-size:12px;">${m.phone}</div></td>
            <td>${m.start_display}</td><td>${m.end_display}</td>
            <td><span class="badge ${badge}">${m.status}</span></td>
            <td><a href="edit.php?id=${m.id}" class="btn" style="background:var(--light-pink); color:var(--accent-pink); padding:5px 10px;">Edit</a>
            <a href="delete.php?id=${m.id}" class="btn" style="background:#fff0f0; color:red; padding:5px 10px;" onclick="return confirm('Del?')">Del</a></td>
        </tr>`;

    });
    tableBody.innerHTML = html || '<tr><td colspan="6" style="text-align:center">No one here yet 💖</td></tr>';
}

function renderCards() {
    let html = '';
    currentData.forEach(m => {
        let badge = (m.status === 'Active') ? 'badge-active' : 'badge-expired';
        html += `<div class="member-card">
            <span class="badge ${badge}">${m.status}</span>
            <img src="uploads/${m.photo}">
            <div style="font-weight:800; font-size:18px; color:var(--accent-pink);">${m.full_name}</div>
            <p style="font-size:13px; color:#888;">📞 ${m.phone}</p>
            <div style="background:var(--bg-soft); padding:10px; border-radius:15px; font-size:12px; margin-top:10px;">
                Expiry: <b>${m.end_display}</b>
            </div>

            <div style="display:flex; gap:10px; margin-top:15px;">
                <a href="edit.php?id=${m.id}" class="btn btn-edit">Edit ✨</a>
                <a href="delete.php?id=${m.id}" class="btn btn-delete" onclick="return confirm('Delete this member?')">Remove 🗑️</a>
            </div>

        </div>`;
    });
    cardContainer.innerHTML = html || '<p style="text-align:center; width:100%;">No one here yet 💖</p>';
}

function render() {
    if (currentView === 'list') {
        renderList();
        listContainer.classList.remove('hidden');
        cardContainer.classList.add('hidden');
        btnList.classList.add('active');
        btnCard.classList.remove('active');
    } else {
        renderCards();
        cardContainer.classList.remove('hidden');
        listContainer.classList.add('hidden');
        btnCard.classList.add('active');
        btnList.classList.remove('active');
    }
}

function loadData() {
    let q = document.getElementById('ajaxSearch').value;
    let s = document.getElementById('statusFilter').value;
    fetch(`search.php?q=${q}&status=${s}`).then(res => res.json()).then(data => { currentData = data; render(); });
}

document.getElementById('ajaxSearch').addEventListener('input', () => loadData());
document.getElementById('statusFilter').addEventListener('change', () => loadData());
btnList.addEventListener('click', () => { currentView = 'list'; localStorage.setItem('gymView', 'list'); render(); });
btnCard.addEventListener('click', () => { currentView = 'card'; localStorage.setItem('gymView', 'card'); render(); });

document.addEventListener('DOMContentLoaded', () => loadData());
