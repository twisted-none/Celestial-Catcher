@extends('layouts.app')

@section('content')
<div class="container animate__animated animate__fadeIn">
    <h1>ISS Data</h1>
    
    <!-- Фильтры -->
    <form method="GET" class="mb-4 d-flex gap-2">
        <input type="text" name="search" class="form-control" placeholder="Поиск..." value="{{ request('search') }}">
        <select name="sort_by" class="form-select">
            <option value="timestamp">Дата</option>
            <option value="velocity">Скорость</option>
        </select>
        <select name="sort_dir" class="form-select">
            <option value="asc">По возрастанию</option>
            <option value="desc">По убыванию</option>
        </select>
        <button type="submit" class="btn btn-primary">Фильтр</button>
    </form>

    <!-- Таблица -->
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>Дата (UTC)</th>
                <th>Широта</th>
                <th>Долгота</th>
                <th>Скорость</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $row)
            <tr class="animate__animated animate__fadeInUp" style="animation-delay: {{ $loop->index * 0.1 }}s">
                <td>{{ $row->timestamp }}</td>
                <td>{{NumFormat($row->latitude)}}</td>
                <td>{{NumFormat($row->longitude)}}</td>
                <td>{{NumFormat($row->velocity)}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    {{ $data->links() }}
</div>
@endsection