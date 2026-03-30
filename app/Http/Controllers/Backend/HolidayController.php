<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Holiday;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HolidayController extends Controller
{
    public function index(Request $request)
    {
        $month = (int) $request->get('month', now()->month);
        $year = (int) $request->get('year', now()->year);

        $startOfMonth = Carbon::create($year, $month, 1)->startOfDay();
        $endOfMonth = Carbon::create($year, $month, 1)->endOfMonth()->endOfDay();

        $holidays = Holiday::query()
            ->whereDate('from_date', '<=', $endOfMonth->toDateString())
            ->whereDate('to_date', '>=', $startOfMonth->toDateString())
            ->orderBy('from_date')
            ->get();

        return view('backend.pages.payroll.holidays', compact('holidays', 'month', 'year'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
            'note' => 'nullable|string|max:500',
            'status' => 'nullable|boolean',
        ]);

        Holiday::create([
            'name' => $request->name,
            'from_date' => $request->from_date,
            'to_date' => $request->to_date,
            'note' => $request->note,
            'status' => $request->boolean('status', true),
        ]);

        return back()->with('message', 'Holiday created successfully.');
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
            'note' => 'nullable|string|max:500',
            'status' => 'nullable|boolean',
        ]);

        $holiday = Holiday::findOrFail($id);
        $holiday->update([
            'name' => $request->name,
            'from_date' => $request->from_date,
            'to_date' => $request->to_date,
            'note' => $request->note,
            'status' => $request->boolean('status'),
        ]);

        return back()->with('message', 'Holiday updated successfully.');
    }

    public function destroy(int $id)
    {
        $holiday = Holiday::findOrFail($id);
        $holiday->delete();

        return back()->with('message', 'Holiday deleted successfully.');
    }
}
