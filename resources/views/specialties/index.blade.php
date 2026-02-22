@extends('layouts.main')

@section('title', 'Специальности - Колледж')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/apps.css') . '?v=' . (file_exists(public_path('css/apps.css')) ? filemtime(public_path('css/apps.css')) : time()) }}">
@endpush

@section('content')
    <div class="container">
        <h1 class="page-title">Наши специальности</h1>
        <p class="page-description">Выберите интересующую вас специальность для получения подробной информации</p>

        <div class="specialties-grid">
            @forelse($specialties as $specialty)
                @php
                    $educationLevel = 'Среднее профессиональное образование';
                    $studyForms = [];
                    $tuitionByForm = [];

                    if (!is_null($specialty->cost_full_time)) {
                        $studyForms[] = 'очная';
                        $tuitionByForm['очная'] = $specialty->cost_full_time;
                    }

                    if (!is_null($specialty->cost_part_time)) {
                        $studyForms[] = 'заочная';
                        $tuitionByForm['заочная'] = $specialty->cost_part_time;
                    }

                    if (!is_null($specialty->cost_distance)) {
                        $studyForms[] = 'дистанционная';
                        $tuitionByForm['дистанционная'] = $specialty->cost_distance;
                    }

                    if (empty($studyForms)) {
                        $studyForms[] = 'очная';
                    }

                    $disciplines = (string) $specialty->skills;
                    $career = null;
                    $desc = (string) $specialty->description;
                    $short = strlen($desc) > 120 ? mb_substr($desc, 0, 120) . '...' : $desc;
                    $paidPlaces = max(0, (int) ($specialty->total_places ?? 0) - (int) $specialty->budget_places);
                @endphp
                <div class="specialty-card"
                     data-code="{{ $specialty->code }}"
                     data-fee="{{ count($tuitionByForm) ? reset($tuitionByForm) : '' }}"
                     data-passing=""
                     data-forms="{{ implode(',', $studyForms) }}"
                     data-name="{{ $specialty->name }}">
                    <div class="specialty-photo">
                        @php
                            $photo = $specialty->photo;
                            if ($photo) {
                                $startsWithSpecialties = strpos($photo, 'specialties/') === 0;
                                $startsWithAssets = strpos($photo, 'assets/') === 0;
                                $path = ($startsWithSpecialties || $startsWithAssets)
                                    ? 'assets/img/' . $photo
                                    : 'assets/img/specialties/' . $photo;
                            }
                        @endphp
                        @if(!empty($photo))
                            <img src="{{ asset($path) }}" alt="{{ $specialty->name }}">
                        @else
                            <img src="{{ asset('assets/img/no-photo.jpg') }}" alt="Нет фото">
                        @endif
                    </div>

                    <div class="specialty-content">
                        <div class="specialty-header-line">
                            <h2 class="specialty-title">{{ $specialty->name }}</h2>
                            @if($specialty->code)
                                <span class="specialty-code-pill">{{ $specialty->code }}</span>
                            @endif
                            <span class="specialty-education-pill">{{ $educationLevel }}</span>
                        </div>
                        <div class="specialty-study-row">
                            @foreach($studyForms as $form)
                                @php
                                    $formType = $form === 'очная' ? 'fulltime' : ($form === 'заочная' ? 'parttime' : 'mixed');
                                @endphp
                                <span class="study-badge study-badge-{{ $formType }}">{{ $form }}</span>
                            @endforeach
                        </div>
                        <div class="specialty-meta">
                            <span class="duration">Срок обучения: {{ $specialty->duration }}</span>
                            <span class="qualification">Квалификация: {{ $specialty->qualification ?? 'Не указано' }}</span>
                            <span class="places">Бюджетных мест: {{ $specialty->budget_places }}</span>
                            <span class="places">Платных мест: {{ $paidPlaces ?: 'Уточняется' }}</span>
                            <span class="tuition">
                                Стоимость:
                                @if(count($tuitionByForm))
                                    @foreach($tuitionByForm as $formTitle => $fee)
                                        <span>{{ $formTitle }} — {{ number_format($fee, 0, ',', ' ') }} ₽ / год</span>@if(!$loop->last), @endif
                                    @endforeach
                                @else
                                    Уточняется
                                @endif
                            </span>
                        </div>
                        <div class="specialty-skills">
                            <h3>Навыки:</h3>
                            <ul>
                                @foreach(explode(',', (string) $specialty->skills) as $skill)
                                    @if(trim($skill) !== '')
                                        <li>{{ trim($skill) }}</li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                        <div class="specialty-actions">
                            <a href="{{ route('applications.create', ['specialty' => $specialty->id]) }}" class="btn-apply">
                                Подать документы
                            </a>
                            <a href="{{ route('specialties.show', $specialty) }}" class="btn-more">
                                Открыть страницу специальности
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="no-data">Нет доступных специальностей</div>
            @endforelse
        </div>
    </div>

    <script>
    </script>
@endsection
