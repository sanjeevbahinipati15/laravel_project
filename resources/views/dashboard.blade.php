<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

</head>
<body>
    <h1>Welcome to the Dashboard</h1>

    <p>You are logged in!</p>

    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit">Logout</button>
    </form>

    @if (session('error'))
        <p>{{ session('error') }}</p>
    @endif

    @if (session('success'))
        <p>{{ session('success') }}</p>

    @endif


    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Aadhar No</th>
                <th>Aadhar Image</th>
                <th>Pan No</th>
                <th>Pan Image</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->aadhar_no }}</td>
                    <td><img src="{{ asset('storage/' . $user->aadhar_image) }}" alt="Aadhar Image" width="100"></td>
                    <td>{{ $user->pan_no }}</td>
                    <td><img src="{{ asset('storage/' . $user->pan_image) }}" alt="Pan Image" width="100"></td>
                    <td>
                        <form action="{{ route('users.edit', $user->id) }}" method="GET">
                            @csrf
                            @method('PUT')
                            <button type="submit">Edit</button>
                        </form>
                        <form style="margin-bottom: 10px;" action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
</body>
</html>
