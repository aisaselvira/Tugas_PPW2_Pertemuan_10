<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Jobs\SendMailJob;
use Intervention\Image\ImageManagerStatic as Image;

class LoginRegisterCustomController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except([
            'logout2',
            'dashboard2'
        ]);
    }

    public function register2()
    {
        return view('auth_custom.register2');
    }

    public function store2(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:250',
            'email' => 'required|email|max:250|unique:users',
            'password' => 'required|min:8|confirmed',
            'photo' => 'image|nullable|max:1999'
        ]);


        if ($request->hasFile('photo')) {
            $image = $request->file('photo');
            $filenameWithExt = $request->file('photo')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('photo')->getClientOriginalExtension();
            $filenameToStore = $filename . '_' . time() . '.' . $extension;
            $path = $request->file('photo')->storeAs('photos/original', $filenameToStore);
            // Simpan gambar asli

            // Buat thumbnail dengan lebar dan tinggi yang diinginkan
            $thumbnailPath = public_path('storage/photos/thumbnail/' . $filenameToStore);
            Image::make($image)
                ->fit(100, 100)
                ->save($thumbnailPath);

            // Buat versi persegi dengan lebar dan tinggi yang sama
            $squarePath = public_path('storage/photos/square/' . $filenameToStore);
            Image::make($image)
                ->fit(200, 200)
                ->save($squarePath);

            $path = $filenameToStore;
        } else {
            $path = null;
        }

        User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'photo' => $path,
        ]);



        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'subject' => "Selamat Datang di Web Portfolio Aisa Selvira",
            'body' => "Anda telah mengunjungi situs Web Portfolio Aisa Selvira. Yuk, kepoin situs ini untuk melihat karya-karya Aisa Selvira."
        ];

        $credentials = $request->only('email', 'password');
        Auth::attempt($credentials);
        $request->session()->regenerate();

        dispatch(new SendMailJob($data));

        return redirect()->route('dashboard2')->withSuccess('Berhasil Register!');

    }

    public function login2()
    {
        return view('auth_custom.login2');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('dashboard2')->withSucces('Berhasil Login2!');
        }
        return back()->withErrors([
            'email' => "Email tidak sesuai"
        ])->onlyInput('email');
    }

    public function dashboard2()
    {
        if (Auth::check()) {
            return view('auth_custom.dashboard2');
        }

        return redirect()->route('login2')
            ->withErrors([
                'email' => 'Please login2 to access this page.',
            ])->onlyInput('email');

    }

    public function logout2(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login2')->withSuccess("Berhasil logout");
    }


}

