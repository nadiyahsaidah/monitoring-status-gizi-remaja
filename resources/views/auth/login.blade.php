<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            background: #f3eafe;
            font-family: 'Poppins', sans-serif;
        }
        .login-container {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background: linear-gradient(135deg, #004080 50%, #f3eafe 50%);
        }
        .login-box {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            overflow: hidden;
            max-width: 900px;
            width: 100%;
            display: flex;
            flex-direction: column;
        }
        .login-left {
            background: #f3eafe;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 40px;
            text-align: center;
        }
        .login-right {
            padding: 50px;
        }
        .login-right h2 {
            font-weight: bold;
            margin-bottom: 30px;
        }
        .login-right .form-control {
            border-radius: 10px;
            background: #f3f4f6;
            border: none;
        }
        .login-right .btn-primary {
            background: #7367f0;
            border: none;
            border-radius: 10px;
            width: 100%;
            padding: 10px;
            font-weight: bold;
            position: relative; /* Position relative for absolute loading spinner */
        }
        .login-right .btn-primary:hover {
            background: #5a56c5;
        }
        .error-message {
            color: red;
            font-size: 0.8em;
        }
        .loading-spinner {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            display: none;
        }
        .login-left img {
            max-width: 150px;
        }
        .login-left h3 {
            margin-top: 20px;
            color: #004080;
        }
        @media (min-width: 768px) {
            .login-box {
                flex-direction: row;
            }
            .login-left, .login-right {
                width: 50%;
            }
        }
    </style>
</head>
<body>
<div class="login-container">
    <div class="login-box">
        <div class="login-left">
            <img src="{{ asset('assets/img/logo/logo.png') }}" alt="Logo">
            <h3>Website Monitoring Status Gizi Remaja di Puskesmas Kasihan II</h3>
        </div>
        <div class="login-right">
            <h2>User Authentication</h2>
            <form id="loginForm" action="{{ route('login') }}" method="post">
                @csrf
                <div class="">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" placeholder="Username Pengguna" name="username" value="{{ old('username') }}">
                    @error('username')
                    <span class="error-message m-0">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mt-3">
                    <label for="password" class="form-label">Kata Sandi</label>
                    <input type="password" class="form-control" id="password" placeholder="Kata Sandi" name="password">
                    @error('password')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary mt-3 position-relative">Log In
                    <div class="loading-spinner">
                        <div class="spinner-border text-light" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </button>
                @if ($errors->has('login'))
                    <div class="mt-3 error-message">
                        {{ $errors->first('login') }}
                    </div>
                @endif
            </form>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script>
    document.getElementById('loginForm').addEventListener('submit', function() {
        document.querySelector('.loading-spinner').style.display = 'block';
        document.querySelector('.btn-primary').disabled = true;
        document.querySelector('.btn-primary').innerHTML = '<div class="spinner-border text-light" role="status"><span class="visually-hidden">Loading...</span></div>';
    });
</script>
</body>
</html>
