@extends('auth_custom.layouts2')

@section('content')

<table class="table"> <thead> <tr>
    <th scope="col">Name</th> <th scope="col">Email</th> <th scope="col">Photo</th>
    <th scope="col">Action</th> </tr> </thead>
        <tbody> @foreach($data_user as $user) <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td> @if ($user->photo) @if (File::exists(storage_path('app/public/photos/square/' . $user->photo)))
                    <img src="{{ asset('storage/photos/square/' . $user->photo) }}"></td>
                        @elseif (File::exists(storage_path('app/public/photo/thumbnail/' . $user->photo)))
                        <img src="{{ asset('storage/photos/thumbnail/' . $user->photo) }}"></td>
                        @else
                        <img src="{{ asset('storage/photos/original/' . $user->photo) }}" width="250" height="300"></td>
                        @endif
                    @else
                    <p>Not Available</p></td>
                    @endif
                    <td>
                    <form action="{{ route('destroy2', $user) }}" method="post">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm hapus-button" onClick="return confirm('Yakin mau dihapus')">Delete</button>
                    <a href="{{ route('edit_resize', $user) }}" class="btn btn-warning btn-sm edit-button">Edit</a>
                    </form>
                    </td>
            </tr>
            @endforeach
            </tbody>
</table>
@endsection