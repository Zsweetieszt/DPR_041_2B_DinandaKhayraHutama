<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login - Gaji DPR</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
      min-height: 100vh;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    
    .login-container {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    
    .login-card {
      background: white;
      border-radius: 15px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
      padding: 40px;
      width: 100%;
      max-width: 400px;
    }
    
    .login-icon {
      width: 60px;
      height: 60px;
      background: linear-gradient(135deg, #dc3545, #a03449);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 15px;
      box-shadow: 0 5px 15px rgba(220, 53, 69, 0.3);
    }
    
    .login-icon i {
      color: white;
      font-size: 28px;
    }
    
    .btn-login {
      background: linear-gradient(135deg, #dc3545 0%, #a03449 100%);
      border: none;
      border-radius: 8px;
      padding: 12px;
      font-weight: 600;
    }
    
    .btn-login:hover {
      box-shadow: 0 5px 15px rgba(220, 53, 69, 0.4);
    }
  </style>
</head>
<body>
  <div class="login-container">
    <div class="login-card">
      <div class="login-header">
        <div class="login-icon">
          <i class="fas fa-user-tie"></i>
        </div>
        <h4 class="login-title">Gaji DPR Login</h4>
        <p class="text-muted">Akses Admin atau Publik</p>
      </div>
      
      @if($errors->any())
        <div class="alert alert-danger">
          <i class="fas fa-exclamation-triangle me-2"></i>
          {{ $errors->first() }}
        </div>
      @endif
      
      <form method="POST" action="{{ route('login.post') }}">
        @csrf
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input name="username" id="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username') }}" required>
            @error('username')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="mb-4">
            <label for="password" class="form-label">Password</label>
            <input name="password" type="password" id="password" class="form-control @error('password') is-invalid @enderror" required>
            @error('password')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
        
        <button class="btn btn-danger btn-login w-100">
          <i class="fas fa-sign-in-alt me-2"></i>
          Log In
        </button>
      </form>
      <div class="mt-3 text-center text-muted small">
          <p class="mb-1">Akun Uji Coba:</p>
          <p class="mb-0">Admin: <code>admin</code> / `admin123`</p>
          <p>Public: <code>citizen</code> / `public123`</p>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>