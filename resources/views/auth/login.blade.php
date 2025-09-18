<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Educational Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-8">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-800">Welcome Back</h1>
                <p class="text-gray-600">Sign in to your account</p>
            </div>

            @if (session('status'))
                <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('login.submit') }}" method="POST" class="space-y-6">
                @csrf
                
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="{{ old('email') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="you@example.com"
                           required
                           autofocus>
                </div>

                <div>
                    <div class="flex justify-between items-center mb-1">
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <a href="#" class="text-sm text-blue-600 hover:text-blue-500">Forgot password?</a>
                    </div>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="••••••••"
                           required>
                </div>

                <div class="flex items-center">
                    <input type="checkbox" 
                           id="remember" 
                           name="remember" 
                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="remember" class="ml-2 block text-sm text-gray-700">
                        Remember me
                    </label>
                </div>

                <div>
                    <button type="submit" 
                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Sign in
                    </button>
                </div>
            </form>
        </div>
        
        <div class="bg-gray-50 px-8 py-4 border-t border-gray-200">
            <p class="text-center text-sm text-gray-600">
                Don't have an account? 
                <a href="#" class="font-medium text-blue-600 hover:text-blue-500">
                    Contact administrator
                </a>
            </p>
        </div>
    </div>
</body>
</html>
