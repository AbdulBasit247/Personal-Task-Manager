// app.js - simple AJAX for tasks
async function api(action, method='GET', data=null) {
  let url = 'api.php?action=' + action;
  let opts = {method};
  if (data) {
    opts.body = JSON.stringify(data);
    opts.headers = {'Content-Type':'application/json'};
  }
  let res = await fetch(url, opts);
  return res.json();
}

const listEl = document.getElementById('taskList');
const newTask = document.getElementById('newTask');
const addBtn = document.getElementById('addBtn');
const priority = document.getElementById('priority');
const due = document.getElementById('due_date');
const filterStatus = document.getElementById('filterStatus');
const sortBy = document.getElementById('sortBy');

async function refresh() {
  const res = await api('list&status='+filterStatus.value+'&sort='+sortBy.value);
  listEl.innerHTML = '';
  res.forEach(t => {
    const li = document.createElement('li');
    li.className = 'task-item' + (t.completed==1 ? ' completed' : '');
    li.innerHTML = `
      <input type="checkbox" ${t.completed==1 ? 'checked' : ''} data-id="${t.id}" class="chk">
      <div class="text">
        <div contenteditable="true" data-id="${t.id}" class="editable">${escapeHtml(t.task)}</div>
        <small>Due: ${t.due_date || '—'} • Priority: ${t.priority}</small>
      </div>
      <div>
        <button class="btn del" data-id="${t.id}">Delete</button>
      </div>
    `;
    listEl.appendChild(li);
  });
}

function escapeHtml(s){ if (!s) return ''; return s.replaceAll('&','&amp;').replaceAll('<','&lt;').replaceAll('>','&gt;'); }

addBtn.addEventListener('click', createTask);
newTask.addEventListener('keydown', (e)=>{ if (e.key==='Enter') createTask(); });

async function createTask(){
  const t = newTask.value.trim();
  if (!t) return;
  await api('create','POST',{task:t,priority:priority.value,due_date:due.value});
  newTask.value=''; due.value=''; refresh();
}

listEl.addEventListener('click', async (e)=>{
  if (e.target.classList.contains('del')) {
    const id = e.target.dataset.id;
    if (confirm('Delete?')) {
      await api('delete','POST',{id});
      refresh();
    }
  }
  if (e.target.classList.contains('chk')) {
    const id = e.target.dataset.id;
    const checked = e.target.checked ? 1 : 0;
    await api('update','POST',{id,field:'completed',value:checked});
    refresh();
  }
});

listEl.addEventListener('input', debounce(async (e)=>{
  if (e.target.classList.contains('editable')) {
    const id = e.target.dataset.id;
    const txt = e.target.innerText.trim();
    await api('update','POST',{id,field:'task',value:txt});
  }
}, 800));

filterStatus.addEventListener('change', refresh);
sortBy.addEventListener('change', refresh);

function debounce(fn, ms){ let t; return (...a)=>{ clearTimeout(t); t=setTimeout(()=>fn(...a), ms); }; }

refresh();
