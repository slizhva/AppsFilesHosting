@extends('layouts.admin')

@section('container-class')
    container
@endsection

@section('body-class')
    col-md-11
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

                <strong>---Add file:---</strong>
                <form method="POST" action="{{ route('image.add', $set['id']) }}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="d-flex flex-column">
                        <div class="btn-group col-md-5" role="group" aria-label="Optimize toggle button group">
                            <input type="radio" class="btn-check" name="optimize" value="no" id="btnradio1" autocomplete="off" checked>
                            <label class="btn btn-outline-primary" for="btnradio1">Without optimize</label>

                            <input type="radio" class="btn-check" name="optimize" value="yes" id="btnradio2" autocomplete="off">
                            <label class="btn btn-outline-primary" for="btnradio2">With optimize</label>
                        </div>
                        <div class="btn-group col-md-5 mt-3">
                            <div class="form-floating me-2">
                                <input name="width" type="number" class="form-control" id="width" placeholder="width" value="">
                                <label for="width">Width</label>
                            </div>
                            <div class="form-floating">
                                <input name="height" type="number" class="form-control" id="width" placeholder="width" value="">
                                <label for="width">Height</label>
                            </div>
                        </div>
                        <div class="btn-group col-md-5 mt-3 mb-3">
                            <div class="me-2">
                                <label for="width">Single File</label>
                                <input class="form-control" name="image" type="file">
                            </div>
                            <div>
                                <label for="width">Bulk Upload</label>
                                <input class="form-control" name="bulk_upload" type="file">
                            </div>
                        </div>
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
