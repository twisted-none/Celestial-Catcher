<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class IssController extends Controller
{
    /**
     * Отображает дашборд с логами данных МКС.*
     **/
    public function index(Request $request): View
    {
        $query = DB::table('iss_fetch_log');

        // Поиск по содержимому JSON
        if ($request->filled('search')) {
            $term = $request->input('search');

        $query->where(function ($q) use ($term) {
            $q->whereRaw("CAST(payload->>'velocity' AS TEXT) LIKE ?", ["%{$term}%"])
            ->orWhere('payload->>visibility', 'like', "%{$term}%");
});
        }

        $allowedSorts = [
            'fetched_at' => 'fetched_at',
            'velocity'   => 'payload->>velocity', // Позволяем сортировать по JSON полю
            'visibility' => 'payload->>visibility'
        ];
        
        $sortCol = $request->input('sort_by', 'fetched_at');
        $sortDir = $request->input('sort_dir', 'desc');

        if (array_key_exists($sortCol, $allowedSorts)) {
            $query->orderBy($allowedSorts[$sortCol], $sortDir);
        } else {
            $query->orderBy('fetched_at', 'desc');
        }

        $data = $query->paginate(15)->withQueryString();

        return view('dashboard.iss', compact('data'));
    }
}