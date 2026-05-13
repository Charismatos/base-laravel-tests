<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Base Laravel Tests Login</title>
</head>

<body>
    @if (session('registration_success'))
    <div class="registration-successful">
        <p class="registration-success-message">You Registered Successfuly</p>
    </div>
    @endif
    @if (session('update_success'))
    <div class="update-successful">
        <p class="update-success-message">Profile Updated Successfuly</p>
    </div>
    @endif

    <h1 class="login-heading">LOGIN</h1>
    <form class="login-form" action="{{ route('user-login-submit') }}" method="POST">
        @csrf
        <label class="login-labels" for="email">Email:</label>
        <input class="small-text-inputs" id="email" name="email" required><br><br>

        <label class="login-labels" for="password">Password:</label>
        <input class="small-text-inputs" type="password" id="password" name="password" required><br><br>

        <button class="login-submit-btn" type="submit">LogIn</button>
    </form>
</body>

</html>