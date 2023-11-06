@extends('auth_custom.layouts2')

@section('content')

<div class="row justify-content-center mt-5"> <div class="col-md-8">

    <div class="card"> <div class="card-header">Resize Photo</div> <div class="card-body">
        <form action="{{ route('resizeImage', $user) }}" method="post" enctype="multipart/form-data"> @csrf <input
            type="hidden" name="photo" value="{{ $user->photo }}">
            <div class="mb-3 row">
            <label for="size" class="col-md-4 col-form-label text-md-end text-start">Choose image size:</label>
            <div class="col-md-6">
                <select name="size" id="size" class="form-control">
                <option value="square" {{ $user->size === 'square' ? 'selected' : '' }}>Square</option>
                <option value="thumbnail" {{ $user->size === 'thumbnail' ? 'selected' : '' }}>Thumbnail</option>
                </select>
                </div>
            </div>
            <div class="mb-3 row">
            <div class="col-md-6 offset-md-4">
            <button type="submit" class="btn btn-warning">
                Resize Image
                </button>
    </div>
    </div>
    </form>
</div>
</div> </div>
</div>

<table class="table"> <thead> <tr>
<th scope="col">Photo</th>
<th scope="col">Square Photo</th>
<th scope="col">Thumbnail Photo</th>
</tr>
</thead>
<tbody>
    <tr>
        <td>
            @if($user->photo)
            <img src="{{ asset('storage/photos/original/' . $user->photo) }}" width="250" height="300">

            @else
            <p>Not Available</p>
            @endif
        </td>
        <td>
            @if($user->photo)
            @if (File::exists(public_path('storage/photos/square/' . $user->photo)))
            <img src="{{ asset('storage/photos/square/' . $user->photo) }}">
            @else
            <p>Not resized</p>
            @endif
            @else
            <p>Not Available</p>
            @endif
        </td>
        <td>
            @if($user->photo)
            @if (File::exists(public_path('storage/photos/thumbnail/' .
            $user->photo)))
            <img src="{{ asset('storage/photos/thumbnail/' . $user->photo) }}">
            @else
            <p>Not resized</p>
            @endif
            @else
            <p>Not Available</p>
            @endif
        </td>
    </tr>
</tbody>
</table>

@endsection