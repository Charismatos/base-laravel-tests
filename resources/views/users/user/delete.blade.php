<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Base Laravel Tests Delete Profile</title>
</head>

<body>
    <h1 class="delete-heading">DELETE PROFILE</h1>
    <b>Name:</b> <span>{{ $user->name }}</span>

    <b>Email:</b> <span>{{ $user->email }}</span>

    <div class="delete-confirmation">
        Are You Sure You Want To Delete This Account?
        <a href="{{ route('user-dashboard', ['user'=>$user->id]) }}"><button>No</button></a>
        <a href="{{ route('user-delete-submit', ['user'=>$user->id]) }}"><button>Yes</button></a>
    </div>

    <form class="delete-form" action="{{ route('user-delete-submit', ['user'=>$user->id]) }}" method="POST">
        @csrf
        @method('DELETE')
        <button class="delete-submit-btn" type="submit">Delete Account</button>
    </form>
</body>

</html>