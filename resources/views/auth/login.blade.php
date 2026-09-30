<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: system-ui, -apple-system, sans-serif;
            background: #f5f5f5;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }
        .box {
            background: #fff;
            padding: 2rem;
            width: 100%;
            max-width: 360px;
            border: 1px solid #ddd;
        }
        h1 {
            font-size: 1.25rem;
            margin: 0 0 1.5rem;
        }
        label {
            display: block;
            font-size: .875rem;
            margin-bottom: .25rem;
        }
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: .5rem;
            border: 1px solid #ccc;
            font-size: .875rem;
            margin-bottom: 1rem;
            box-sizing: border-box;
        }
        input:focus {
            outline: none;
            border-color: #333;
        }
        .row {
            display: flex;
            align-items: center;
            gap: .5rem;
            margin-bottom: 1rem;
            font-size: .875rem;
        }
        button {
            width: 100%;
            padding: .6rem;
            background: #111;
            color: #fff;
            border: 0;
            font-size: .875rem;
            cursor: pointer;
        }
        button:hover {
            background: #333;
        }
        .error {
            background: #fef2f2;
            border: 1px solid #fca5a5;
            color: #991b1b;
            padding: .5rem .75rem;
            font-size: .8rem;
            margin-bottom: 1rem;
        }
        .error ul {
            margin: 0;
            padding-left: 1rem;
        }
    </style>
</head>
<body>
    <div class="box">
        <h1>Login</h1>

        @if($errors->any())
            <div class="error">
                <ul>
                    @foreach($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>

            <div class="row">
                <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                <label for="remember" style="margin:0">Ingat saya</label>
            </div>

            <button type="submit">Masuk</button>
        </form>
    </div>
</body>
</html>
