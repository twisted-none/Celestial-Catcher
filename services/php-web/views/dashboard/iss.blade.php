@extends('layouts.app')

@section('content')
<div class="card p-4 mb-4 animate__animated animate__fadeInDown">
    <h2 class="mb-3">📡 Мониторинг МКС (сырые логи)</h2>
    
    <!-- Форма фильтрации и поиска -->
    <form method="GET" action="{{ route('dashboard.iss') }}">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Поиск по скорости или видимости..." value="{{ request('search') }}">
            <button type="submit" class="btn btn-primary">Найти</button>
        </div>
    </form>
</div>

<!-- Таблица данных -->
<div class="card animate__animated animate__fadeInUp">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Время записи</th>
                        <th>Широта</th>
                        <th>Долгота</th>
                        <th>Скорость (км/ч)</th>
                        <th>Видимость</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $row)
                        @php
                            // Декодируем JSON, чтобы с ним было удобно работать
                            $payload = json_decode($row->payload);
                        @endphp
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($row->fetched_at)->format('Y-m-d H:i:s') }}</td>
                            <td>{{ $payload->latitude ?? 'N/A' }}</td>
                            <td>{{ $payload->longitude ?? 'N/A' }}</td>
                            <td>
                                <span class="badge bg-info text-dark">{{ $payload->velocity ?? 'N/A' }}</span>
                            </td>
                            <td>
                                @if(isset($payload->visibility) && $payload->visibility == 'daylight')
                                    <span class="badge bg-warning text-dark">☀️ День</span>
                                @else
                                    <span class="badge bg-secondary">🌑 Тень</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <h4>Данных пока нет 😔</h4>
                                <p>Rust-сервис еще не успел собрать данные. Подождите 10-20 секунд и обновите страницу.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center mt-4">
            {{ $data->links() }}
        </div>
    </div>
</div>
@endsection