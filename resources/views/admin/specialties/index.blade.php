@extends('layouts.admin')

@section('title', 'Управление специальностями')

@section('content')
<div class="admin-header">
    <h1>Управление специальностями</h1>
    <button class="btn btn-primary" onclick="openModal('addModal')">Добавить специальность</button>
</div>

@if(session('success'))
    <div class="alert alert-success" style="margin-bottom: 20px; padding: 10px; background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; border-radius: 4px;">
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger" style="margin-bottom: 20px; padding: 10px; background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; border-radius: 4px;">
        <ul style="margin: 0; padding-left: 20px;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<table>
    <thead>
        <tr>
            <th>Название</th>
            <th>Срок обучения</th>
            <th>Квалификация</th>
            <th>Бюджетные места</th>
            <th>Всего мест</th>
            <th>Действия</th>
        </tr>
    </thead>
    <tbody>
        @foreach($specialties as $specialty)
            <tr>
                <td>{{ $specialty->name }}</td>
                <td>{{ $specialty->duration }}</td>
                <td>{{ $specialty->qualification ?? 'Не указано' }}</td>
                <td>{{ $specialty->budget_places }}</td>
                <td>{{ $specialty->total_places ?? $specialty->budget_places }}</td>
                <td>
                    <button class="btn btn-primary btn-sm" onclick="openEditModal({{ $specialty }})">Ред.</button>
                    <form action="{{ route('admin.specialties.destroy', $specialty) }}" method="POST" style="display: inline;" onsubmit="return confirm('Вы уверены?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Удалить</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<!-- Add Modal -->
<div id="addModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('addModal')">&times;</span>
        <h2>Добавить специальность</h2>
        <form action="{{ route('admin.specialties.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label>Название</label>
                <input type="text" name="name" required>
            </div>
            <div class="form-group">
                <label>Срок обучения</label>
                <input type="text" name="duration" required>
            </div>
            <div class="form-group">
                <label>Квалификация</label>
                <input type="text" name="qualification" required>
            </div>
            <div class="form-group">
                <label>Описание</label>
                <textarea name="description" rows="4" required></textarea>
            </div>
            <div class="form-group">
                <label>Бюджетные места</label>
                <input type="number" name="budget_places" min="0" step="1" required>
            </div>
            <div class="form-group">
                <label>Всего мест</label>
                <input type="number" name="total_places" min="0" step="1">
            </div>
            <div class="form-group">
                <label>Стоимость (очная форма), ₽ в год</label>
                <input type="number" name="cost_full_time" min="0" step="0.01">
            </div>
            <div class="form-group">
                <label>Стоимость (заочная форма), ₽ в год</label>
                <input type="number" name="cost_part_time" min="0" step="0.01">
            </div>
            <div class="form-group">
                <label>Стоимость (дистанционная форма), ₽ в год</label>
                <input type="number" name="cost_distance" min="0" step="0.01">
            </div>
            <div class="form-group">
                <label>Формы обучения (через запятую: очная, заочная, дистанционная)</label>
                <input type="text" name="study_forms" placeholder="например: очная, заочная">
            </div>
            <div class="form-group">
                <label>Где работать (через запятую)</label>
                <input type="text" name="where_to_work" placeholder="например: ИТ-компании, Банки">
            </div>
            <div class="form-group">
                <label>Кем работать (через запятую)</label>
                <input type="text" name="job_roles" placeholder="например: Программист, Аналитик">
            </div>
            <div class="form-group">
                <label>Формы обучения (через запятую)</label>
                <input type="text" name="study_forms" id="edit_study_forms">
            </div>
            <div class="form-group">
                <label>Где работать (через запятую)</label>
                <input type="text" name="where_to_work" id="edit_where_to_work">
            </div>
            <div class="form-group">
                <label>Кем работать (через запятую)</label>
                <input type="text" name="job_roles" id="edit_job_roles">
            </div>
            <div class="form-group">
                <label>Фото</label>
                <input type="file" name="photo">
            </div>
            <button type="submit" class="btn btn-primary">Сохранить</button>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('editModal')">&times;</span>
        <h2>Редактировать специальность</h2>
        <form id="editForm" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label>Название</label>
                <input type="text" name="name" id="edit_name" required>
            </div>
            <div class="form-group">
                <label>Срок обучения</label>
                <input type="text" name="duration" id="edit_duration" required>
            </div>
            <div class="form-group">
                <label>Квалификация</label>
                <input type="text" name="qualification" id="edit_qualification" required>
            </div>
            <div class="form-group">
                <label>Описание</label>
                <textarea name="description" id="edit_description" rows="4" required></textarea>
            </div>
            <div class="form-group">
                <label>Бюджетные места</label>
                <input type="number" name="budget_places" id="edit_budget_places" min="0" step="1" required>
            </div>
            <div class="form-group">
                <label>Всего мест</label>
                <input type="number" name="total_places" id="edit_total_places" min="0" step="1">
            </div>
            <div class="form-group">
                <label>Стоимость (очная форма), ₽ в год</label>
                <input type="number" name="cost_full_time" id="edit_cost_full_time" min="0" step="0.01">
            </div>
            <div class="form-group">
                <label>Стоимость (заочная форма), ₽ в год</label>
                <input type="number" name="cost_part_time" id="edit_cost_part_time" min="0" step="0.01">
            </div>
            <div class="form-group">
                <label>Стоимость (дистанционная форма), ₽ в год</label>
                <input type="number" name="cost_distance" id="edit_cost_distance" min="0" step="0.01">
            </div>
            <div class="form-group">
                <label>Фото</label>
                <input type="file" name="photo">
            </div>
            <button type="submit" class="btn btn-primary">Обновить</button>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openModal(id) {
        document.getElementById(id).style.display = "block";
    }

    function closeModal(id) {
        document.getElementById(id).style.display = "none";
    }

    function openEditModal(specialty) {
        document.getElementById('edit_name').value = specialty.name;
        document.getElementById('edit_duration').value = specialty.duration;
        document.getElementById('edit_qualification').value = specialty.qualification || '';
        document.getElementById('edit_study_forms').value = specialty.study_forms || '';
        document.getElementById('edit_description').value = specialty.description;
        document.getElementById('edit_budget_places').value = specialty.budget_places ?? 0;
        document.getElementById('edit_total_places').value = specialty.total_places ?? specialty.budget_places ?? 0;
        document.getElementById('edit_cost_full_time').value = specialty.cost_full_time ?? '';
        document.getElementById('edit_cost_part_time').value = specialty.cost_part_time ?? '';
        document.getElementById('edit_cost_distance').value = specialty.cost_distance ?? '';
        
        // Handle array fields for edit modal
        document.getElementById('edit_where_to_work').value = Array.isArray(specialty.where_to_work) 
            ? specialty.where_to_work.join(', ') 
            : (specialty.where_to_work || '');
        document.getElementById('edit_job_roles').value = Array.isArray(specialty.job_roles) 
            ? specialty.job_roles.join(', ') 
            : (specialty.job_roles || '');
        
        const form = document.getElementById('editForm');
        form.action = `/admin/specialties/${specialty.id}`;
        
        openModal('editModal');
    }

    window.onclick = function(event) {
        if (event.target.classList.contains('modal')) {
            event.target.style.display = "none";
        }
    }
</script>
@endpush
@endsection
