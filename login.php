<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
  <title>SB Admin 2 - Login</title>

  <!-- Styles & Icons -->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet"/>
  <link
    href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900"
    rel="stylesheet"
  />
  <link href="css/sb-admin-2.min.css" rel="stylesheet"/>
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css"
    rel="stylesheet"
  />

  <style>
    .position-relative { position: relative; }
  </style>
</head>
<body class="bg-gradient-primary">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-xl-10 col-lg-12 col-md-9">
        <div class="card o-hidden border-0 shadow-lg my-5">
          <div class="card-body p-0">
            <div class="row">
              <div class="col-lg-12">
                <div class="p-5">

                  <!-- Tampilkan pesan error jika ada -->
                  <?php
                  session_start();
                  if (!empty($_SESSION['login_error'])): ?>
                    <div class="alert alert-danger">
                      <?= htmlentities($_SESSION['login_error']) ?>
                    </div>
                  <?php
                    unset($_SESSION['login_error']);
                  endif;
                  ?>

                  <div class="text-center mb-4">
                    <h1 class="h4 text-gray-900">Welcome!</h1>
                  </div>

                  <form
                    class="user"
                    action="controller/transaksi/login.php"
                    method="POST"
                  >
                    <div class="form-group mb-3">
                      <input
                        type="text"
                        name="username"
                        class="form-control form-control-user"
                        placeholder="Enter Username"
                        required
                      />
                    </div>

                    <div class="form-group position-relative mb-4">
                      <input
                        type="password"
                        name="password"
                        class="form-control form-control-user"
                        id="exampleInputPassword"
                        placeholder="Password"
                        required
                      />
                      <i
                        class="bi bi-eye-slash position-absolute"
                        id="togglePassword"
                        style="top:50%; right:1rem; transform:translateY(-50%); cursor:pointer;"
                      ></i>
                    </div>

                    <button
                      type="submit"
                      class="btn btn-primary btn-user btn-block"
                    >
                      Login
                    </button>
                  </form>

                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Scripts -->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="js/sb-admin-2.min.js"></script>

  <script>
    // Toggle visibility password
    const toggle = document.getElementById('togglePassword');
    const input = document.getElementById('exampleInputPassword');

    toggle.addEventListener('click', () => {
      const show = input.type === 'password';
      input.type = show ? 'text' : 'password';
      toggle.classList.toggle('bi-eye');
      toggle.classList.toggle('bi-eye-slash');
    });
  </script>
</body>
</html>
