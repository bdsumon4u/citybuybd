<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserBonus;
use Illuminate\Http\Request;

class UserBonusController extends Controller
{
    public function index(Request $request)
    {
        $userId = $request->query('user_id');
        $year = $request->query('year', date('Y'));

        $query = UserBonus::with('user');

        if ($userId) {
            $query->where('user_id', $userId);
        }

        if ($year) {
            $query->where('year', $year);
        }

        $bonuses = $query->orderByDesc('created_at')->paginate(20);
        $users = User::where('role', '!=', 1)->orderBy('name')->get();

        return view('backend.pages.payroll.user-bonus', compact('bonuses', 'users', 'userId', 'year'));
    }

    public function create()
    {
        $users = User::where('role', '!=', 1)->orderBy('name')->get();

        return view('backend.pages.payroll.user-bonus-form', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:100',
            'description' => 'nullable|string|max:255',
            'amount' => 'required|numeric|min:0',
            'year' => 'required|integer|min:2020|max:2099',
            'month' => 'required|integer|min:1|max:12',
            'notes' => 'nullable|string',
        ]);

        UserBonus::create([
            'user_id' => $request->user_id,
            'name' => $request->name,
            'description' => $request->description,
            'amount' => $request->amount,
            'year' => $request->year,
            'month' => str_pad($request->month, 2, '0', STR_PAD_LEFT),
            'notes' => $request->notes,
        ]);

        return back()->with('message', 'Special Bonus created successfully!');
    }

    public function edit(UserBonus $bonus)
    {
        $users = User::where('role', '!=', 1)->orderBy('name')->get();

        return view('backend.pages.payroll.user-bonus-form', compact('bonus', 'users'));
    }

    public function update(Request $request, UserBonus $bonus)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:100',
            'description' => 'nullable|string|max:255',
            'amount' => 'required|numeric|min:0',
            'year' => 'required|integer|min:2020|max:2099',
            'month' => 'required|integer|min:1|max:12',
            'notes' => 'nullable|string',
        ]);

        $bonus->update([
            'user_id' => $request->user_id,
            'name' => $request->name,
            'description' => $request->description,
            'amount' => $request->amount,
            'year' => $request->year,
            'month' => str_pad($request->month, 2, '0', STR_PAD_LEFT),
            'notes' => $request->notes,
        ]);

        return back()->with('message', 'Special Bonus updated successfully!');
    }

    public function destroy(UserBonus $bonus)
    {
        $bonus->delete();

        return back()->with('message', 'Special Bonus deleted successfully!');
    }
}
