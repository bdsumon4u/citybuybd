<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\SalaryAdvance;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalaryAdvanceController extends Controller
{
    public function index(Request $request)
    {
        $userId = $request->get('user_id');
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        $users = User::whereIn('role', [2, 3])->where('status', 1)->get();

        $query = SalaryAdvance::with(['user', 'approver'])
            ->whereMonth('date', $month)
            ->whereYear('date', $year);

        if ($userId) {
            $query->where('user_id', $userId);
        }

        $advances = $query->orderBy('date', 'desc')->get();

        return view('backend.pages.payroll.advances', compact('users', 'advances', 'month', 'year', 'userId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date',
            'note' => 'nullable|string|max:500',
        ]);

        SalaryAdvance::create([
            'user_id' => $request->user_id,
            'amount' => $request->amount,
            'date' => $request->date,
            'note' => $request->note,
            'approved_by' => Auth::id(),
        ]);

        return back()->with('message', 'Salary advance recorded successfully!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date',
            'note' => 'nullable|string|max:500',
        ]);

        $advance = SalaryAdvance::findOrFail($id);
        $advance->amount = $request->amount;
        $advance->date = $request->date;
        $advance->note = $request->note;
        $advance->save();

        return back()->with('message', 'Salary advance updated successfully!');
    }

    public function destroy($id)
    {
        $advance = SalaryAdvance::findOrFail($id);
        $advance->delete();

        return back()->with('message', 'Salary advance deleted successfully!');
    }
}
