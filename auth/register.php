<?php require_once '../config/functions.php'; ?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register PMB</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<main class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h1 class="h4 mb-3">Register Pendaftar</h1>

                    <?php if (isset($_GET['error'])): ?>
                        <div class="alert alert-danger"><?= e($_GET['error']); ?></div>
                    <?php endif; ?>

                    <form action="proses_register.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Nama</label>
                            <input type="text" name="nama" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Register</button>
                    </form>

                    <p class="text-center mt-3 mb-0">
                        Sudah punya akun?
                        <a href="login.php">Login</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</main>

</body>
</html>
