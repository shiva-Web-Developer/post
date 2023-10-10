<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Card Design</title>
    <!-- Add Bootstrap 4 CSS CDN -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6 m-auto">
                <div class="card">
                @if(session('success'))
                    <button style="color:green;">{{session('success')}}</button>
                    @endif
                    @if(session('error'))
                    <button style="color:red;">{{session('error')}}</button>
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">Name: {{$data['user']->name}}</h5>
                        <p class="card-text">Email: {{$data['user']->email}}</p>
                        <a href="{{ route('user.logout') }}" onclick="return confirm('Are you sure you want to logout?')">Logout</a><br>
                        <a href="{{ route('create.post') }}">create a post</a>
                    </div>
                </div>
            </div>
        </div><br>
        <div class="row">
            @foreach($post as $res)
            <div class="col-lg-3">
                <div class="card">
                    <img src="{{ url('images/' . $res->image) }}" class="card-img-top" alt="User's Profile Picture">
                    <div class="card-body">
                        <h5 class="card-title">{{$res->title}}</h5>
                        <p class="card-text">{{$res->description}}</p>
                        <span>{{$res->created_at}}</span>
                    </div>
                    <div class="card-footer">
                        <div class="card-footer">
                            <a href="{{ route('edit.post', ['id' => $res->id]) }}" class="btn btn-primary">Edit</a>
                            <a onclick="return confirm('Are you sure you want to Delete?')" href="{{ route('post.delete', ['id' => $res->id]) }}" class="btn btn-danger">Delete</a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach


        </div>
    </div>

</body>

</html>