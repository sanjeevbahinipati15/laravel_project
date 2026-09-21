<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

    <h1>Registration Form</h1>


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

    <form action="{{ route('register') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group" style="margin-bottom: 10px;">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required>
        </div>
        <div class="form-group" style="margin-bottom: 10px;">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required>
        </div>
        <div class="form-group" style="margin-bottom: 10px;">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div class="form-group" style="margin-bottom: 10px;">
            <label for="password_confirmation">Confirm Password:</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required>
        </div>
        <div class="form-group" style="margin-bottom: 10px;">
            <label for="aadhar_no">Aadhar no:</label>
            <input type="number" id="aadhar_no" name="aadhar_no" value="{{ old('aadhar_no') }}" required>
        </div>

        <div class="form-group" style="margin-bottom: 10px;">
            <label for="aadhar_image">Aadhar Image:</label>
            <input type="file" accept="image/*" id="aadhar_image" name="aadhar_image">
        </div>

        <div class="form-group" style="margin-bottom: 10px;">
            <label for="pan_no">Pan no:</label>
            <input type="text" id="pan_no" name="pan_no" value="{{ old('pan_no') }}" required>
        </div>

        <div class="form-group" style="margin-bottom: 10px;">
            <label for="pan_image">Pan Image: </label>
            <input type="file" accept="image/*" id="pan_image" name="pan_image">
        </div>

        <button type="submit">Submit</button>
    </form>

</body>
</html>
