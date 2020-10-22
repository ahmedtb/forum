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
                                <label for="channel_id">channel</label>
                                <select class="form-control" id="channel_id"  name="channel_id" class="form-control" required>
                                    <option value="">Select One:</option>
                                    @foreach($channels as $channel)
                                        <option   value="{{$channel->id}}">{{$channel->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="title">title</label>
                                <input class="form-control" id="title" name="title" value="{{old('title')}}" required/>
                            </div>

                            <div   class="form-group">
                                <label for="body">body</label>
                                <textarea class="form-control" id="body" name="body" placeholder="thread post here" required>{{ old('body') }}</textarea>
                            </div>

                            <button type="submit" class="btn btn-default">publish</button>

                            <div class="form-group">
                                @if(count($errors))
                                    <ul class="alert alert-danger">
                                    @foreach($errors->all() as $error)
                                        <li>{{$error}}</li>
                                    @endforeach
                                    </ul>
                                @endif
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
