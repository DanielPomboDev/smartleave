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

        <div class="flex-1 bg-gradient-to-br from-blue-500 via-blue-400 to-blue-300 text-white flex flex-col items-center justify-center p-8">
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


        <div class="flex-1 p-8 flex flex-col justify-center">
            <h3 class="text-2xl font-bold text-center mb-6">Sign in to your account</h3>

            <form action="" class="space-y-4">
                <div>
                    <label for="employee_id" class="block text-sm font-medium mb-1">Employee ID</label>
                    <input type="text" placeholder="Employee ID" class="input input-bordered w-full" id="employee_id" required>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium mb-1">Password</label>
                    <input type="password" placeholder="Password" class="input input-bordered w-full" id="password" required>
                </div>

                <button class="btn btn-primary w-full mt-4">Login</button>
            </form>

            {{-- <p class="text-sm text-center mt-4">
                Forgot your password? <a href="#" class="text-blue-500 hover:underline">Reset it here</a>.
            </p> --}}
        </div>
    </div>
</body>

</html>
