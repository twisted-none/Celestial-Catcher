@extends('layouts.app')

@section('content')
<div class="container animate__animated animate__fadeIn">
    <h1 class="mb-4">ISS Data Log</h1>
    
    <!-- Фильтры и поиск -->
    <div class="card p-3 mb-4">
        <form method="GET" class="row g-3 align-items-center">
            <div class="col-md-5">
                <input type="text" name="search" class="form-control" placeholder="Поиск по скорости или видимости..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="sort_by" class="form-select">
                    <option value="fetched_at" @selected(request('sort_by') == 'fetched_at')>Сортировать по дате</option>
                    <option value="velocity" @selected(request('sort_by') == 'velocity')>Сортировать по скорости</option>
                    <option value="visibility" @selected(request('sort_by') == 'visibility')>Сортировать по видимости</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="sort_dir" class="form-select">
                    <option value="desc" @selected(request('sort_dir') == 'desc')>По убыванию</option>
                    <option value="asc" @selected(request('sort_dir') == 'asc')>По возрастанию</option>
                </select>
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-primary w-100">Найти</button>
            </div>
        </form>
    </div>

    <!-- Таблица с данными -->
    <div class="card">
        <div class="card-body">
            <table class="table table-striped table-hover mb-0">
                <thead>
                    <tr>
                        <th>Дата получения (UTC)</th>
                        <th>Скорость (km/h)</th>
                        <th>Видимость</th>
                        <th style="width: 40%;">Полные данные (Payload)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($data as $log)
                        <tr>
                            <td>{{ $log->fetched_at }}</td>
                            @php
                                $payload = json_decode($log->payload);
                            @endphp
                            <td>{{ $payload->velocity ?? 'N/A' }}</td>
                            <td>{{ $payload->visibility ?? 'N/A' }}</td>
                            <td>
                                {{-- json_encode выводит строку, pre сохраняет форматирование --}}
                                <pre style="font-size: 0.8em; margin: 0;">{{ json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4">Данные не найдены. Попробуйте изменить фильтры.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Пагинация -->
    <div class="d-flex justify-content-center mt-4">
        {{ $data->links() }}
    </div>
</div>
@endsection