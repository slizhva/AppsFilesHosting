@extends('layouts.admin')

@section('container-class')
    container
@endsection

@section('body-class')
    col-md-10
@endsection

@section('admin-title')
    <div>
        <span><a class="btn btn-link p-0" href="{{ route('sets') }}">Dashboard</a>/Set/</span><strong>{{ $set['name'] }}</strong>
    </div>
@endsection

@section('admin-body')

    <div class="container">
        <div class="row">
            <div class="col-md-12">

                <strong>---Add image:---</strong>
                <form method="POST" action="{{ route('image.add', $set['id']) }}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="d-flex flex-column">
                        <input name="image" type="file" class="col-md-7 mt-2 mb-3" required>
                        <input type="submit" value="Upload" class="col-md-2">
                    </div>
                </form>

                <hr class="mt-4 mb-4">

                <table id="imagesTable" class="table table-hover table-sm">
                    <thead class="table-primary">
                        <tr>
                            <th class="table-secondary align-middle text-center">Image</th>
                            <th class="table-secondary align-middle text-center">Url</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($images as $image)
                            <tr>
                                <td><img src="{{ $image }}" alt=""></td>
                                <td>{{ $image }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row mt-5">
            <hr>
            <div class="col-md-5">
                @include('components.dangerous_action_form')
            </div>
        </div>
    </div>
@endsection
