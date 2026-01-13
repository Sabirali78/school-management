<header class="bg-blue-600 text-white shadow-md">
    @php $user = $user ?? auth()->user(); @endphp
    <div class="container mx-auto flex justify-between items-center py-4 px-6">

        <!-- Logo / App Name -->
        <div class="text-2xl font-bold">
            {{ config('app.name', 'School Management') }}
        </div>

        <!-- Menu -->
        <nav class="space-x-4">
            @if($user)
                @if($user->hasRole('Admin'))
                    <a href="{{ route('dashboard') }}" class="hover:underline">Dashboard</a>
                    <a href="{{ route('students.index') }}" class="hover:underline">Students</a>
                    <a href="{{ route('teachers.index') }}" class="hover:underline">Teachers</a>
                    <a href="{{ route('classes.index') }}" class="hover:underline">Classes</a>
                    <a href="{{ route('fee.index') }}" class="hover:underline">Fees</a>
                    <a href="{{ route('reports.index') }}" class="hover:underline">Reports</a>
                @elseif($user->hasRole('Teacher'))
                    <a href="{{ route('dashboard') }}" class="hover:underline">Dashboard</a>
                    <a href="{{ route('my-classes.index') }}" class="hover:underline">My Classes</a>
                    <a href="{{ route('assignments.index') }}" class="hover:underline">Assignments</a>
                @elseif($user->hasRole('Student'))
                    <a href="{{ route('dashboard') }}" class="hover:underline">Dashboard</a>
                    <a href="{{ route('my-classes.index') }}" class="hover:underline">Classes</a>
                    <a href="{{ route('my-results.index') }}" class="hover:underline">Results</a>
                @elseif($user->hasRole('Parent'))
                    <a href="{{ route('dashboard') }}" class="hover:underline">Dashboard</a>
                    <a href="{{ route('child-results.index') }}" class="hover:underline">Child Results</a>
                @endif

                <button wire:click="logout" class="hover:underline">Logout</button>
            @else
                <a href="{{ route('login') }}" class="hover:underline">Login</a>
            @endif
        </nav>
    </div>
</header>
