<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Rules\Password;
use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\EmailChangeNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\Rules;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($username)
    {

        $res = User::with('hasBeat')
            ->where('username', '=', $username)
            ->get();

        // $res = User::where('username', '=', $username)
        //     ->get()
        //     ->toArray();

        //
        // $res = File::with('isBeat', 'isBeat.fromUser', 'isBeat.getCover', 'isBeat.likes2')
        //     ->has('isBeat.fromUser')
        //     ->join('beats', function ($join) {
        //         $join->on('beats.id', '=', 'files.beat_id');
        //     })
        //     ->join('users', function ($join) {
        //         $join->on('user_id', '=', 'users.id');
        //     })
        //     ->where('users.username', '=', $username)
        //     ->get()
        //     ->toArray();
        // dd($res);
        if(isset($res[0])){
            return Inertia::render('Profile', [
                'logedIn' => Auth::id(),
                'userData' => $res[0],
            ]);
        }else{
            return Inertia::render('Error', ['msg' => 'This user doesn\'t exist']);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        // dd($request);

        //
        $user = User::where('id', $request->id);
        //'password' => ['required', 'confirmed', Rules\Password::defaults()],


        if ($request->use == 'admin') {
            // dd($request);
            $user->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'username' => $request->username,
                'location' => $request->location,
                'description' => $request->desc,
            ]);

            return response()->json([
                'success' => 'User saved',
            ]);
        }
        $request->validate(['username' => 'required|string|max:255|unique:users,username, ' . $request->id]);
        $request->validate(['first_name' => 'required|string|max:255']);
        $request->validate(['last_name' => 'required|string|max:255']);
        $request->validate(['email' => 'required|string|email|max:255|unique:users,email, ' . $request->id]);
        // $request->validate(['password' => ['required', 'confirmed', Rules\Password::defaults()]]);
        $user->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'username' => $request->username,
            // 'password' => Hash::make($request->password),
            'location' => $request->location,
            'description' => $request->desc,
        ]);

        return "/profile/{$request->username}";
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function deleteUser($id, Request $request)
    {
        User::where('id', $id)
            ->delete();
    }
}
