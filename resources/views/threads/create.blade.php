@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Forum Threads</div>

                    <div class="card-body">
                        <form method="post" action="/threads" >
                            @csrf
                            <div class="form-group">
                                <label for="title">title</label>
                                <input class="form-control" id="title" name="title"/>
                            </div>

                            <div   class="form-group">
                                <label for="body">body</label>
                                <textarea class="form-control" id="body" name="body" placeholder="thread post here"></textarea>
                            </div>

                            <button type="submit" class="btn btn-default">publish</button>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
