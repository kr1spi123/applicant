@extends('layouts.admin')

@section('title', 'Управление специальностями')

@section('content')
<div class="admin-header">
    <h1>Управление специальностями</h1>
    <button class="btn btn-primary" onclick="openModal('addModal')">Добавить специальность</button>
</div>

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
                <label>Навыки (через запятую)</label>
                <textarea name="skills" rows="3"></textarea>
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
                <label>Навыки (через запятую)</label>
                <textarea name="skills" id="edit_skills" rows="3"></textarea>
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
        document.getElementById('edit_description').value = specialty.description;
        document.getElementById('edit_skills').value = specialty.skills || '';
        document.getElementById('edit_budget_places').value = specialty.budget_places ?? 0;
        document.getElementById('edit_total_places').value = specialty.total_places ?? specialty.budget_places ?? 0;
        document.getElementById('edit_cost_full_time').value = specialty.cost_full_time ?? '';
        document.getElementById('edit_cost_part_time').value = specialty.cost_part_time ?? '';
        document.getElementById('edit_cost_distance').value = specialty.cost_distance ?? '';
        
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
