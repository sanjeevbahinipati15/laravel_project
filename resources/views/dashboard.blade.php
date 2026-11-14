<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Surya International</title>
</head>
<body>
    <h1>Welcome to the Dashboard</h1>

    <p>You are logged in!</p>

    <form id="logoutForm">
        @csrf
        <button type="submit">Logout</button>
    </form>

    @if (session('error'))
        <p style="color: red;">{{ session('error') }}</p>
    @endif

    @if (session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    <div id="profileDetails" style="margin-top: 20px; border: 1px solid #ccc; padding: 15px; display: inline-block;">
        <h2>Profile Details</h2>
        <p id="name"><strong>Name:</strong> Loading...</p>
        <p id="email"><strong>Email:</strong> Loading...</p>
        <p id="mobileno"><strong>Mobile No:</strong> Loading...</p>
        <p id="dob"><strong>DOB:</strong> Loading...</p>
        <p id="aadharNo"><strong>Aadhar No:</strong> Loading...</p>
        <p id="aadharImage"><strong>Aadhar Image:</strong> Loading...</p>
        <p id="panNo"><strong>Pan No:</strong> Loading...</p>
        <p id="panImage"><strong>Profile Image:</strong> Loading...</p>
    </div>

    <!-- Search, Filter & Sort Form -->
    <div style="margin: 20px 0;">
        <form action="{{ route('dashboard') }}" method="GET">
            <div style="display: inline-block; margin-right: 10px;">
                <input type="text" value="{{ request()->input('search') }}" name="search" placeholder="Search name, email...">
            </div>

            <div style="display: inline-block; margin-right: 10px;">
                <select name="filter_document">
                    <option value="">Filter by Document</option>
                    <option value="with_document" {{ request('filter_document') === 'with_document' ? 'selected' : '' }}>With Document</option>
                    <option value="without_document" {{ request('filter_document') === 'without_document' ? 'selected' : '' }}>Without Document</option>
                </select>
            </div>

            <div style="display: inline-block; margin-right: 10px;">
                <select name="sort">
                    <option value="">Sort By</option>
                    <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>Name</option>
                    <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Latest</option>
                    <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Oldest</option>
                </select>
            </div>

            <button type="submit" style="padding: 6px 14px; cursor: pointer;">Apply</button>
        </form>

        @if(request()->has('search') || request()->has('filter_document') || request()->has('sort'))
            <div style="margin-top: 10px;">
                <a href="{{ route('dashboard') }}">Reset Filters</a>
            </div>
        @endif
    </div>

    <!-- Users Table -->
    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>Profile Image</th>
                <th>Name</th>
                <th>Email</th>
                <th>Mobile No</th>
                <th>DOB</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
                <tr>
                    <td>
                        @if($user->pan_image)
                            <img src="{{ asset('storage/' . $user->pan_image) }}" alt="Profile Image" width="80">
                        @else
                            N/A
                        @endif
                    </td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->mobileno ?? 'N/A' }}</td>
                    <td>{{ $user->dob ? $user->dob->format('d-m-Y') : 'N/A' }}</td>
                    <td>
                        <!-- Fixed: Edit is a GET route, no @csrf or @method('PUT') needed -->
                        <a href="{{ route('users.edit', $user->id) }}" style="text-decoration: none; padding: 3px 8px; background: #007bff; color: white; border-radius: 3px; display: inline-block; margin-bottom: 5px;">Edit</a>

                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background: #dc3545; color: white; border: none; padding: 3px 8px; border-radius: 3px; cursor: pointer;">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" align="center">No users found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin: 15px 0;">
        {{ $users->links() }}
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const token = localStorage.getItem('auth_token');
            if (!token) {
                alert('No authentication token found. Please log in again.');
                window.location.href = '{{ route('login.form') }}';
                return;
            }

            fetch("{{ route('api.user') }}", {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${token}`
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Failed to fetch user details');
                }
                return response.json();
            })
            .then(data => {
                document.getElementById('name').innerHTML = `<strong>Name:</strong> ${data.name}`;
                document.getElementById('email').innerHTML = `<strong>Email:</strong> ${data.email}`;
                document.getElementById('aadharNo').innerHTML = `<strong>Aadhar No:</strong> ${data.aadhar_no ?? 'N/A'}`;
                document.getElementById('aadharImage').innerHTML = data.aadhar_image
                    ? `<strong>Aadhar Image:</strong> <img src="{{ asset('storage/') }}/${data.aadhar_image}" alt="Aadhar Image" width="150">`
                    : `<strong>Aadhar Image:</strong> N/A`;
                document.getElementById('panNo').innerHTML = `<strong>Pan No:</strong> ${data.pan_no ?? 'N/A'}`;
                document.getElementById('panImage').innerHTML = data.pan_image
                    ? `<strong>Profile Image:</strong> <img src="{{ asset('storage/') }}/${data.pan_image}" alt="Profile Image" width="150">`
                    : `<strong>Profile Image:</strong> N/A`;
                document.getElementById('mobileno').innerHTML = `<strong>Mobile No:</strong> ${data.mobileno ?? 'N/A'}`;
                document.getElementById('dob').innerHTML = `<strong>DOB:</strong> ${data.dob ?? 'N/A'}`;
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to fetch user details. Please log in again.');
                localStorage.removeItem('auth_token');
                window.location.href = '{{ route('login.form') }}';
            });
        });

        document.getElementById('logoutForm').addEventListener('submit', async function(event) {
            event.preventDefault();

            const token = window.localStorage.getItem('auth_token');
            if (!token) {
                alert('No authentication token found.');
                window.location.href = '{{ route('login.form') }}';
                return;
            }

            try {
                const api = await fetch("{{ route('api.logout') }}", {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${token}`
                    }
                });

                if (!api.ok) {
                    const errorData = await api.json();
                    throw new Error(errorData.error || 'An error occurred during logout');
                } else {
                    localStorage.removeItem('auth_token');
                    window.location.href = '{{ route('login.form') }}';
                }
            } catch (error) {
                console.log('Error:', error);
                console.error('Error:', error);
                alert(error.message);
                localStorage.removeItem('auth_token');
                window.location.href = '{{ route('login.form') }}';
            }
        });
    </script>
</body>
</html>
