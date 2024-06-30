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
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .login-form {
            padding: 50px;
            flex: 1;
        }
        .login-form h2 {
            font-weight: bold;
            margin-bottom: 30px;
        }
        .login-form .form-control {
            border-radius: 10px;
            background: #f3f4f6;
            border: none;
        }
        .login-form .btn-primary {
            background: #7367f0;
            border: none;
            border-radius: 10px;
            width: 100%;
            padding: 10px;
            font-weight: bold;
            position: relative; /* Position relative for absolute loading spinner */
        }
        .login-form .btn-primary:hover {
            background: #5a56c5;
        }
        .login-form .social-login {
            text-align: center;
            margin-top: 30px;
        }
        .login-form .social-login button {
            margin: 0 10px;
            border: none;
            background: transparent;
        }
        .login-form .social-login button img {
            width: 30px;
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
    </style>
</head>
<body>
<div class="container d-flex align-items-center justify-content-center vh-100">
    <div class="login-container">
        <div class="login-form">
            <h2>LOGIN</h2>
            <p>How to get started lorem ipsum dolor at?</p>
            <form id="loginForm" action="{{ route('login') }}" method="post">
                @csrf
                <div class="">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" placeholder="Nama Pengguna" name="username">
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
                <button type="submit" class="btn btn-primary mt-3 position-relative">Login
                    <div class="loading-spinner">
                        <div class="spinner-border text-light" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </button>
            </form>
            <div class="social-login">
                <p>Atau masuk menggunakan:</p>
                <button onclick="loginViaGoogle()"><img src="https://img.icons8.com/color/48/000000/google-logo.png" alt="Google"></button>
                <button onclick="loginViaFacebook()"><img src="https://img.icons8.com/fluency/48/000000/facebook-new.png" alt="Facebook"></button>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script>
     function loginViaFacebook() {
        if (confirm('Mau Login lewat facebook??')) {
            alert('gabisa whahahaha');
        }
    }
         function loginViaGoogle() {
        if (confirm('Mau Login lewat Google??')) {
            alert('gabisa whahahaha');
        }
    }
    document.getElementById('loginForm').addEventListener('submit', function() {
        document.querySelector('.loading-spinner').style.display = 'block';
        document.querySelector('.btn-primary').disabled = true;
        document.querySelector('.btn-primary').innerHTML = '<div class="spinner-border text-light" role="status"><span class="visually-hidden">Loading...</span></div>';
    });
</script>
</body>
</html>
