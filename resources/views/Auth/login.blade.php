<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

    <h1 style="color: green; margin-bottom: 10px;"> Surya International</h1>

    <h3 style="color: blue;">Login Form</h3>


    @if($errors->any())
        <div style="color: red;">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <p>{{ session('success') }}</p>
    @endif

    <form action="{{ route('login') }}" method="POST">
        @csrf

        <div class="form-group" style="margin-bottom: 10px;">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required>
        </div>
        <div class="form-group" style="margin-bottom: 10px;">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>

        <div class="form-group" style="margin-bottom: 15px;">
            <label for="remember">Remember Me:</label>
            <input type="checkbox" id="remember" name="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
        </div>

        <div class="form-group" style="margin-bottom: 15px;">
            <label for="forgot_password">Forgot Password?</label>
            <button type="button" onclick="window.location.href='{{ route('password.request') }}'">Reset Password</button>
        </div>

        <button type="submit">Login</button>
        <button type="button" onclick="window.location.href='{{ route('register.form') }}'">Register</button>
    </form>

</body>
</html>
