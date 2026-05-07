<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Base Laravel Tests Edit Profile</title>
</head>

<body>
    <h1>EDIT PROFILE</h1>
    <form action="{{ route('user-register-submit') }}" method="POST">
        @csrf

        @foreach($userData as $userAttribute)
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="$userAttribute->name" required><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="$userAttribute->email" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" value="$userAttribute->password" required><br><br>

        <button type="submit">Update</button>
        @endforeach
    </form>
</body>

</html>