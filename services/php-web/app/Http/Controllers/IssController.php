<?php

namespace App\Http\Controllers;

// --- ИСПРАВЛЕНО: Используем обратный слэш \ для пространств имен ---
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View; // Хорошая практика - указывать тип возвращаемого значения

class IssController extends Controller
{
    /**
     * Отображает дашборд с логами данных МКС.
     */
    public function index(Request $request): View
    {
        $query = DB::table('iss_fetch_log');

        // Поиск по содержимому JSON
        if ($request->filled('search')) {
            $term = $request->input('search');

            // Группируем условия поиска, чтобы они не конфликтовали с другими 'where' в будущем
            // ПРАВИЛЬНО (новая версия)
        $query->where(function ($q) use ($term) {
            // Явно приводим числовое поле из JSON к тексту перед сравнением
            // Это самый надежный способ для поиска в PostgreSQL
            $q->whereRaw("CAST(payload->>'velocity' AS TEXT) LIKE ?", ["%{$term}%"])
            ->orWhere('payload->>visibility', 'like', "%{$term}%");
});
        }

        // --- Улучшение: Более безопасная и расширяемая сортировка ---
        
        // Словарь разрешенных для сортировки колонок
        // Ключ - то, что приходит из URL, значение - реальное поле в БД
        $allowedSorts = [
            'fetched_at' => 'fetched_at',
            'velocity'   => 'payload->>velocity', // Позволяем сортировать по JSON полю
            'visibility' => 'payload->>visibility'
        ];
        
        $sortCol = $request->input('sort_by', 'fetched_at');
        $sortDir = $request->input('sort_dir', 'desc');

        // Проверяем, есть ли колонка в нашем списке разрешенных
        if (array_key_exists($sortCol, $allowedSorts)) {
            $query->orderBy($allowedSorts[$sortCol], $sortDir);
        } else {
            // Сортировка по умолчанию, если пришел некорректный параметр
            $query->orderBy('fetched_at', 'desc');
        }

        // Пагинация с сохранением всех GET-параметров (поиск, сортировка)
        $data = $query->paginate(15)->withQueryString();

        return view('dashboard.iss', compact('data'));
    }
}