@extends('layouts.admin')

@section('container-class')
    container
@endsection

@section('body-class')
    col-md-8
@endsection

@section('admin-title')
    <div>
        <span><a class="btn btn-link p-0" href="{{ route('images', $set['id']) }}">{{ $set['name'] }}</a>/Upload Result</span>
    </div>
@endsection

@section('admin-body')

    <div class="container">
        <div class="row">
            <div class="col-md-12">

                <textarea id="textToCopy" class="w-100" name="" cols="30" rows="15">{{ $result }}</textarea>

                <input style="display: none" id="copyLinkButton" type="submit" value="Copy to clipboard">
            </div>
        </div>
    </div>
@endsection
