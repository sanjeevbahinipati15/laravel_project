<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    
</head>
<body>
    <h1>Edit User</h1>

    @if($errors->any())
        <div style="color: red;">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group" style="margin-bottom: 10px;">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required>
        </div>
        <div class="form-group" style="margin-bottom: 10px;">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required>
        </div>
        <div class="form-group" style="margin-bottom: 10px;">
            <label for="aadhar_no">Aadhar no:</label>
            <input type="number" id="aadhar_no" name="aadhar_no" value="{{ old('aadhar_no', $user->aadhar_no) }}" required>
        </div>

        <div class="form-group" style="margin-bottom: 10px;">
            <label for="aadhar_image">Aadhar Image:</label>
            <input type="file" accept="image/*" id="aadhar_image" name="aadhar_image">
            @if($user->aadhar_image)
                <img src="{{ asset('storage/' . $user->aadhar_image) }}" alt="Aadhar Image" width="100">
            @endif
        </div>

        <div class="form-group" style="margin-bottom: 10px;">
            <label for="pan_no">Pan no:</label>
            <input type="text" id="pan_no" name="pan_no" value="{{ old('pan_no', $user->pan_no) }}" required>
        </div>

        <div class="form-group" style="margin-bottom: 10px;">
            <label for="pan_image">Pan Image:</label>
            <input type="file" accept="image/*" id="pan_image" name="pan_image">
            @if($user->pan_image)
                <img src="{{ asset('storage/' . $user->pan_image) }}" alt="Pan Image" width="100">  
            @endif
        </div>
        <button type="submit">Update User</button>
    </form>
</body>
</html>