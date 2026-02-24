<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Keterlambatan Siswa</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            position: relative;
            overflow: hidden;
        }

        /* Background Image */
        .background-image {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            transform: scale(1);
            z-index: 0;
        }

        
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.1);
            z-index: 1;
        }

        /* Container utama */
        .login-wrapper {
            position: relative;
            z-index: 2;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            padding: 32px 64px;
        }

        /* Login Card dengan Glassmorphism */
        .login-card {
            width: 100%;
            max-width: 380px;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 30px;
            padding: 40px 36px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            animation: fadeInRight 0.8s ease-out;
            position: relative;
        }

        @keyframes fadeInRight {
            from {
                opacity: 0;
                transform: translateX(50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* New User Sign Up Link */
        .signup-link {
            position: absolute;
            top: 25px;
            right: 30px;
            font-size: 14px;
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            font-weight: 500;
        }

        .signup-link span {
            color: white;
            font-weight: 600;
            text-decoration: underline;
        }

        /* Login Title */
        .login-title {
            color: white;
            font-size: 40px;
            font-weight: 700;
            margin-bottom: 10px;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        /* Edugate Label */
        .edugate-label {
            max-width: 492px;
            font-family: 'Poppins';
            font-style: normal;
            font-weight: 700;
            font-size: 32px;
            line-height: 46px;
            color: #160B6A;
            margin: 0 auto 15px auto;
            padding: 0;
            text-align: center;
        }



        /* Welcome Text */
        .welcome-text {
            color: white;
            font-size: 15px;
            font-weight: 400;
            margin-bottom: 30px;
            line-height: 1.5;
            text-shadow: 0 1px 5px rgba(0, 0, 0, 0.1);
        }

        /* Form Group */
        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            color: white;
            font-size: 15px;
            font-weight: 500;
            margin-bottom: 10px;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .form-group input {
            width: 100%;
            padding: 14px 16px;
            border: none;
            border-radius: 15px;
            font-size: 15px;
            font-family: 'Poppins', sans-serif;
            background: rgba(255, 255, 255, 0.9);
            transition: all 0.3s ease;
            color: #333;
        }

        .form-group input:focus {
            outline: none;
            background: white;
            box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
        }

        .form-group input::placeholder {
            color: #999;
        }

        /* Login Button */
        .login-btn {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%);
            color: white;
            border: none;
            border-radius: 15px;
            font-size: 16px;
            font-weight: 600;
            font-family: 'Poppins', sans-serif;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 8px 20px rgba(30, 27, 75, 0.4);
            margin-top: 10px;
        }

        .login-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(30, 27, 75, 0.5);
        }

        .login-btn:active {
            transform: translateY(-1px);
        }

        /* Error Messages */
        .error-message {
            background: rgba(254, 226, 226, 0.95);
            color: #991b1b;
            padding: 12px 16px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-size: 14px;
            border-left: 4px solid #dc2626;
            animation: shake 0.5s ease;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            75% { transform: translateX(10px); }
        }

        /* Logo Sekolah */
        .school-logo {
            position: absolute;
            top: 40px;
            left: 60px;
            z-index: 3;
            animation: fadeIn 1s ease-out;
        }

        .school-logo img {
            width: 100px;
            height: 100px;
            object-fit: contain;
            filter: drop-shadow(0 4px 15px rgba(0, 0, 0, 0.3));
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .login-wrapper {
                justify-content: center;
                padding: 40px;
            }

            .edugate-label {
                font-size: 32px;
                line-height: 48px;
            }

            .school-logo {
                left: 40px;
                top: 30px;
            }

            .school-logo img {
                width: 80px;
                height: 80px;
            }
        }

        @media (max-width: 768px) {
            .login-wrapper {
                padding: 30px 20px;
            }

            .login-card {
                max-width: 100%;
                padding: 40px 30px;
            }

            .edugate-label {
                font-size: 28px;
                line-height: 42px;
                margin-bottom: 12px;
                max-width: 100%;
            }

            .login-title {
                font-size: 38px;
            }

            .welcome-text {
                font-size: 14px;
            }

            .school-logo {
                left: 20px;
                top: 20px;
            }

            .school-logo img {
                width: 60px;
                height: 60px;
            }
        }
    </style>
</head>
<body>
    <!-- Background Image -->
    <img src="{{ asset('images/sklh.png') }}" alt="School Background" class="background-image">
    
    <!-- Overlay -->
    <div class="overlay"></div>

    <!-- School Logo -->
   

    <!-- Login Wrapper -->
    <div class="login-wrapper">
        <!-- Login Card -->
        <div class="login-card">
            <!-- Sign Up Link -->
          

            <!-- Login Title -->
            <p class="edugate-label">EduGate.</p>
            <h1 class="login-title">Masuk</h1>

            <!-- Welcome Text -->
            <p class="welcome-text">Selamat datang kembali, silakan masuk ke akun Anda</p>

            <!-- Error Messages -->
            @if (session('status'))
                <div class="error-message">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="error-message">
                    @foreach ($errors->all() as $error)
                        {{ $error }}<br>
                    @endforeach
                </div>
            @endif

            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Username/Email Field -->
                <div class="form-group">
                    <label for="email">Username</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        required 
                        autofocus 
                        autocomplete="username"
                        placeholder=""
                    >
                </div>

                <!-- Password Field -->
                <div class="form-group">
                    <label for="password">Password</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        required 
                        autocomplete="current-password"
                        placeholder=""
                    >
                </div>

                <!-- Login Button -->
                <button type="submit" class="login-btn">Login</button>
            </form>
        </div>
    </div>
</body>
</html>
