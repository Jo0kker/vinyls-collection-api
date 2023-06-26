<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #3d3c3c;
            font-family: Arial, sans-serif;
        }

        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        div {
            margin-bottom: 10px;
        }

        label {
            margin-bottom: 5px;
        }

        input {
            width: 100%;
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #ddd;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 10px;
            border: none;
            background-color: #2d5f5d;
            color: #fff;
            border-radius: 4px;
            cursor: pointer;
        }

        .error {
            color: red;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
@if ($errors->any())
    <div class="error">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<form method="POST" action="{{ route('login') }}">
    @csrf

    <div>
        <label for="email">Email</label>
        <input type="email" id="email" name="email" required autofocus>
    </div>

    <div>
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>
    </div>

    <div>
        <button type="submit">Login</button>
    </div>

</form>
</body>
</html>
