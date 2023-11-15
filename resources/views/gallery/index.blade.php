@extends('auth_custom.layouts2')
@section('content')
<div class="row justify-content-center mt-5">
    <div class="col-md-8">
        <div class="card">
        <form action="{{ route('gallery.create') }}">
        <button type="submit" class="btn btn-warning mb-2">Tambah</button>
        </form>
            <div class="card-header">Dashboard</div>
                <div class="card-body">
                    <div class="row">
                        @if(count($galleries)>0)
                        @foreach ($galleries as $gallery)
                            <div class="col-sm-2">
                                <div>
                                    <a class="example-image-link" href="{{asset('storage/posts_image/'.$gallery->picture )}}" data-lightbox="roadtrip" data-title="{{$gallery->description}}">
                                        <img class="example-image img-fluid mb-2" src="{{asset('storage/posts_image/'.$gallery->picture )}}" alt="image-1" />
                                    </a>
                                </div>
                                <div class="mb-2">
                                    <form action="{{ route('gallery.edit', $gallery->id) }}">
                                    <button type="submit" class="btn btn-primary">Edit</button>
                                    </form>
                                </div>
                                <div>
                                    <form action="{{ route('gallery.destroy', $gallery->id) }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    @else
                    <h3>Tidak ada data.</h3>
                    @endif
                        <div class="d-flex">
                        {{ $galleries->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
