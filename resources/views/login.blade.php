<!DOCTYPE html>
<html lang="en" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartLeave - Login</title>
    @vite(['resources/js/app.js', 'resources/css/app.css'])
</head>

<body class="bg-base-200 h-full flex items-center justify-center">
    <div class="card shadow-lg bg-base-100 flex flex-col lg:flex-row w-full max-w-5xl h-auto lg:h-[600px]">

        <div class="lg:w-1/2 bg-gradient-to-br from-blue-500 via-blue-400 to-blue-300 text-white flex flex-col items-center justify-center p-8">
            <div class="avatar mb-6">
                <div class="w-40 rounded-full border-4 border-white">
                    <img src="{{ asset('images/sj-logo.jpg') }}" alt="Logo">
                </div>
            </div>

            <p class="text-lg font-semibold text-center">LOCAL GOVERNMENT UNIT</p>
            <p class="text-lg font-semibold text-center">SAN JULIAN, EASTERN SAMAR</p>

            <div class="divider my-6"></div>

            <img src="{{ asset('images/smart-logo.png') }}" alt="Logo" class="w-1/2">
        </div>


        <div class="lg:w-1/2 p-8 flex flex-col justify-center">
            <h3 class="text-2xl font-bold text-center mb-6">Sign in to your account</h3>

            @if (session('error'))
                <div class="alert alert-error mb-4">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-error mb-4">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST" class="space-y-4">
                @csrf
                <x-input 
                    label="User ID" 
                    name="employee_id" 
                    type="text" 
                    placeholder="Enter your User ID" 
                    required 
                    value="{{ old('employee_id') }}"
                />

                <x-input 
                    label="Password" 
                    name="password" 
                    type="password" 
                    placeholder="Password" 
                    required 
                />

                <div class="form-control">
                    <label class="label cursor-pointer">
                        <input type="checkbox" name="is_standard_employee" class="checkbox checkbox-primary" />
                        <span class="label-text">Login as standard employee (restrict access to employee pages only)</span>
                    </label>
                </div>

                <button type="submit" class="btn btn-primary w-full mt-4">Login</button>
            </form>
        </div>
    </div>
</body>

</html>
