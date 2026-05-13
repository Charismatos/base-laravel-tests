<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Base Laravel Tests Register</title>
</head>

<body>
    @if(session('delete_success'))
    <div class="delete-successful">
        <p class="delete-success-message">Deleted Successfuly</p>
    </div>
    @endif
    <h1 class="register-heading">REGISTER</h1>
    <form class="register-form user-register-form" action="{{ route('user-register-submit') }}" method="POST">
        @csrf
        <!-- <input type="hidden" name="_token" value="{{ csrf_token() }}"> -->
        <label class="register-labels" for="name">Name:</label>
        <input class="small-text-inputs" type="text" id="name" name="name" required><br><br>

        <label class="register-labels" for="email">Email:</label>
        <input class="small-text-inputs" type="email" id="email" name="email" required><br><br>

        <label class="register-labels" for="password">Password:</label>
        <input class="small-text-inputs" type="password" id="password" name="password" required><br><br>

        <button class="register-submit-btn" type="submit">Register</button>
    </form>
</body>

</html>