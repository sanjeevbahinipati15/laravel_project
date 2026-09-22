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

    <form id="logoutForm">
        @csrf
        <button type="submit">Logout</button>
    </form>

    @if (session('error'))
        <p>{{ session('error') }}</p>
    @endif

    @if (session('success'))
        <p>{{ session('success') }}</p>

    @endif

    <div id="profileDetails" style="margin-top: 20px;">
        <h2>Profile Details</h2>
        <p id="name"><strong>Name:</strong> {{ Auth::user()->name }}</p>
        <p id="email"><strong>Email:</strong> {{ Auth::user()->email }}</p>
        <p id="aadharNo"><strong>Aadhar No:</strong> {{ Auth::user()->aadhar_no }}</p>
        <p id="aadharImage"><strong>Aadhar Image:</strong></p>
        {{-- <img src="{{ asset('storage/' . Auth::user()->aadhar_image) }}" alt="Aadhar Image" width="200"> --}}
        <p id="panNo"><strong>Pan No:</strong> {{ Auth::user()->pan_no }}</p>
        <p id="panImage"><strong>Pan Image:</ strong></p>
        {{-- <img src="{{ asset('storage/' . Auth::user()->pan_image) }}" alt="Pan Image" width="200"> --}}
    </div>


{{-- <div style="margin: 10px">


    <form action="{{ route('dashboard') }}" method="GET">
        @csrf
    //add searchbar
    <div>
        <input type="text" value="{{ request()->input('search') }}" name="search" placeholder="Search...">

    </div>

    //document status filter
    <div>
        <select name="filter_document">
            <option value="">Select</option>
            <option value="with_document" {{ request('filter_document') === 'with_document'? 'selected' : '' }}> With Document</option>
            <option value="without_document" {{ request('filter_document') === 'without_document'? 'selected' : '' }}> Without Document</option>
        </select>

    </div>

    //sorting
    <div>
        <select name="sort">
            <option value="">Select</option>
            <option value="id" {{ request('id') === 'id'? 'selected' : '' }}> ID</option>
            <option value="name" {{ request('name') === 'name'? 'selected' : '' }}> Name</option>

        </select>

    </div>

    //submit button
    <div>
        <button type="submit" style="padding: 6px 14px; cursor: pointer;">Apply</button>
    </div>
    </form>

    @if(request()->has('search') || request()->has('filter_document') || request()->has('sort') || request()->has('filter_status'))
        <div style="margin-top: 10px; margin-bottom: 10px;">
            <a href="{{ route('dashboard') }}">Reset Filters</a>
        </div>
    @endif
</div> --}}

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

    </table>

</div>

  <div style="margin: 10px">
        {{ $users->links() }}
  </div>


</body>
</html>
<script>

    document.addEventListener('DOMContentLoaded', function() {
            const token = localStorage.getItem('auth_token');
            if (!token) {
                alert('No authentication token found. Please log in again.');
                window.location.href = '{{ route('login.form') }}'; // Redirect to login page
            }

            fetch("{{ route('api.user') }}", {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${token}` // Include the token in the Authorization header
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Failed to fetch user details');
                }
                return response.json();
            })
            .then(data => {
                // Update the profile details on the dashboard
                document.getElementById('name').innerHTML = `<strong>Name:</strong> ${data.name}`;
                document.getElementById('email').innerHTML = `<strong>Email:</strong> ${data.email}`;
                document.getElementById('aadharNo').innerHTML = `<strong>Aadhar No:</strong> ${data.aadhar_no}`;
                document.getElementById('aadharImage').innerHTML = `<strong>Aadhar Image:</strong> <img src="{{ asset('storage/') }}/${data.aadhar_image}" alt="Aadhar Image" width="200">`;
                document.getElementById('panNo').innerHTML = `<strong>Pan No:</strong> ${data.pan_no}`;
                document.getElementById('panImage').innerHTML = `<strong>Pan Image:</strong> <img src="{{ asset('storage/') }}/${data.pan_image}" alt="Pan Image" width="200">`;
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to fetch user details. Please log in again.');
                window.location.href = '{{ route('login.form') }}'; // Redirect to login page
            });

        });



    document.getElementById('logoutForm').addEventListener('submit', async function(event) {
        event.preventDefault(); // Prevent the default form submission

        const token = window.localStorage.getItem('auth_token'); // Retrieve the token from local storage

        if (!token) {
            alert('No authentication token found. Please log in again.');
            window.location.href = '{{ route('login.form') }}'; // Redirect to login page
            return;
        }
        else{

            try {
                const api = await fetch("{{ route('api.logout') }}", {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${token}` // Include the token in the Authorization header
                    }
                });

                if (!api.ok) {
                    const errorData = await api.json();
                    throw new Error(errorData.error || 'An error occurred');
                } else {
                    const data = await api.json();
                    console.log('Success:', data);
                    localStorage.removeItem('auth_token'); // Remove the token from local storage
                    // Redirect to the login page or any other page
                    window.location.href = '{{ route('login.form') }}';
                }
            } catch (error) {
                console.error('Error:', error);
                alert(error.message); // Show an alert with the error message
                // localStorage.removeItem('auth_token');
                // window.location.href = "{{ route('login.form') }}";
            }
        }
    });

</script>




