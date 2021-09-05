<html>
    <head>
        <title>{{ config('app.name') }} - 2FA</title>
        <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
    </head>
    <body>
        <div class="min-h-screen bg-gray-100">
            <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
                <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                    @if ($errors->any())
                        <div class="mb-2">
                            <div class="font-medium text-red-600">
                                {{ __('Whoops! Something went wrong.') }}
                            </div>

                            <ul class="mt-3 list-disc list-inside text-sm text-red-600">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form method="POST" action="{{ route('verify2fa.post') }}">
                        @csrf
                        <div>
                            <label class="block font-medium text-sm text-gray-700" for="otp">
                                OTP
                            </label>

                            <input
                                    class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 block mt-1 w-full"
                                    id="otp"
                                    type="text" name="otp"
                                    required="required"
                                    autofocus="autofocus"
                                    autocomplete="off"
                            />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 ml-3">
                                Verify
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </body>
    <script src="https://unpkg.com/tailwindcss-jit-cdn"></script>
</html>