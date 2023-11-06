@extends('auth_custom.layouts2')

@section('content')
<div class="row justify-content-center mt-5">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">Edit Photo</div>
            <div class="card-body">
                <form action="" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <p class="mb-3">Do you want to edit by uploading a new photo profile?</p>
                    <a href="{{ route('edit2', $user) }}" class="btn btn-primary btn-sm">Upload New Photo</a>

                    <hr>

                    <p class="mt-3">Do you want to edit with the current photo profile and resize it?</p>
                    <a href="{{ route('resizeForm', $user) }}" class="btn btn-warning btn-sm">Resize Photo</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
