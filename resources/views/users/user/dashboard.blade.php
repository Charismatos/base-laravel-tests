<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Base Laravel Tests Login</title>
</head>

@if (session('login_success'))
<div class="login-successful">
    <p class="login-success-message">You LoggedIn Successfuly</p>
</div>
@endif
<h1 class="dashboard-heading"> DASHBOARD </h1>
<h3 class="welcome-user">Welcome, <b class="heading-user-name">{{ $userData->name }}!</b></h3>
<section class="user-details">
    <h3 class="section-headings">User Details</h3>
    <div class="model-details">
        <p>
        <h4 class="model-attributes">Name:</h4>
        <h6 class="model-attribute-values">{{ $userData->name }}</h6>
        </p>
        <p>
        <h4 class="model-attributes">Email:</h4>
        <h6 class="model-attribute-values">{{ $userData->email }}</h6>
        </p>
    </div>
</section>

<div class="action-links">
    <a class="action-link-with-params" href="{{ route('user-edit-form', ['user' => $userData->id]) }}"><button class="edit-button">Edit Profile</button></a>
    <a class="action-link-with-params" href="{{ route('user-delete-form', ['user' => $userData]) }}"><button class="delete-button">Delete Account</button></a>
    <form id="logout-form" action="{{ route('user-logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
    <a class="action-link" href="{{ route('user-logout') }}"
        onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><button class="logout-button">Logout</button></a>
</div>

</body>

</html>