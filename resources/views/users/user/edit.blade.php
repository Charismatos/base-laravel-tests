<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Base Laravel Tests Edit Profile</title>
</head>

<body>
    <h1 class="update-heading">EDIT PROFILE</h1>
    <form class="update-form" action="{{ route('user-edit-submit', ['user'=>$user->id]) }}" method="POST">
        @csrf
        @method('PATCH')
        <label class="update-labels" for="name">Name:</label>
        <input class="small-text-inputs" type="text" id="name" name="name" value="{{ $user->name }}" required><br><br>

        <label class="update-labels" for="email">Email:</label>
        <input class="small-text-inputs" type="email" id="email" name="email" value="{{ $user->email }}" required><br><br>

        <label class="update-labels" for="password">Password:</label>
        <input class="small-text-inputs" type="password" id="password" name="password" value="{{ $user->password }}" required><br><br>

        <button class="update-submit-btn" type="submit">Update</button>
    </form>
</body>

</html>