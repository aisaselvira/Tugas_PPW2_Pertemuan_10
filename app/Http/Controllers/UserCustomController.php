<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\RedirectResponse;
use Intervention\Image\Facades\Image;

// use Image;

class UserCustomController extends Controller
{
    /**
     * Display a registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function index2()
    {
        $data_user = User::all();
        if (!Auth::check()) {
            return redirect()->route('login2')->withErrors(['email' => 'Please login to access the dashboard.',])->onlyInput('email');
        }

        $users = User::get();

        return view('user_custom.user2', compact('data_user'));
    }
    public function destroy2(User $user): RedirectResponse
    {
        if ($user->photo) {
            $photoPath = public_path('photos/' . $user->photo);
            if (File::exists($photoPath)) {
                File::delete($photoPath);
            }
            $user->photo = null;
            $user->save();
        }

        return redirect()->route('user2')->with('success', 'User2 photo is deleted successfully.');
    }

    public function edit_resize(User $user)
    {
        return view('user_custom.edit_resize', compact('user'));
    }

    public function edit2(User $user)
    {
        return view('user_custom.edit2', compact('user'));
    }

    public function update2(Request $request, User $user)
{
    $request->validate([
        'name' => 'required|string|max:250',
        'photo' => 'image|nullable|max:1999',
    ]);

    $user->name = $request->input('name');
    if ($request->hasFile('photo')) {
        $photoPath = public_path('storage/photos/original/' . $user->photo);
        if (File::exists($photoPath)) {
            File::delete($photoPath);
        }
        $filenameWithExt = $request->file('photo')->getClientOriginalName();
        $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
        $extension = $request->file('photo')->getClientOriginalExtension();
        $filenameSimpan = $filename . '_' . time() . '.' . $extension;
        
        // Simpan gambar asli
        $path = $request->file('photo')->storeAs('photos/original', $filenameSimpan);

        // Resizing gambar asli
        $resizedImage = Image::make(public_path('storage/photos/original/' . $filenameSimpan));
        $resizedImage->fit(250, 250);
        $resizedImage->save(public_path('storage/photos/original/' . $filenameSimpan));

        $user->photo = $filenameSimpan;
        $user->save();
    }

    return redirect()->route('user2')
        ->with('success', 'User2 photo is updated successfully.');
}




    public function resizeForm(User $user)
    {
        return view('user_custom.resize2', compact('user'));
    }

    public function resizeImage(Request $request, User $user)
    {
        $this->validate($request, [
            'size' => 'required|in:thumbnail,square',
            'photo' => 'required|string',
        ]);
        $size = $request->input('size');

        if (Storage::exists('photos/original/' . $user->photo)) {
            $originalImagePath = public_path('storage/photos/original/' . $user->photo);

            if ($size === 'square') {
                $resizedImage = Image::make($originalImagePath);
                $resizedImage->fit(100, 100);
                $resizedImage->save(public_path('storage/photos/square/' . $user->photo));
            } elseif ($size === 'thumbnail') {
                $resizedImage = Image::make($originalImagePath);
                $resizedImage->fit(160, 90);
                $resizedImage->save(public_path('storage/photos/thumbnail/' . $user->photo));
            }
        }
        return view('user_custom.resize2', compact('user'))->with('success', 'User photo is resized successfully.');
    }

}