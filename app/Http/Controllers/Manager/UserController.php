<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::Where('role', 3)->get();

        return view('manager.pages.user.manage', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('manager.pages.user.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([

            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],

        ]);

        $user = new user;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->start_time = $request->start_time;
        $user->end_time = $request->end_time;
        $user->role = $request->role;
        $user->status = $request->status;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        return to_route('manager.user.manage');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        if (! is_null($user)) {
            return view('manager.pages.user.edit', compact('user'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $user = User::find($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->start_time = $request->start_time;
        $user->end_time = $request->end_time;
        $user->role = $request->role;
        $user->status = $request->status;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        return to_route('manager.user.manage');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        if (! is_null($user)) {
            $user->delete();
        }

        return to_route('manager.user.manage');

    }
}
