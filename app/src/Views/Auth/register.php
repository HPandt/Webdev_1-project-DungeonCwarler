<?php require(__DIR__ . '/../partials/header.php'); ?>

<div class="container py-5">
	<div class="row justify-content-center align-items-center" style="min-height:60vh;">
		<div class="col-md-6 col-lg-5">
			<div class="card shadow-sm">
				<div class="card-body p-4">
					<h3 class="card-title text-center mb-3">Sign in</h3>

					<?php if(!empty($_GET['error'])): ?>
						<div class="alert alert-danger" role="alert">
							<?php echo htmlspecialchars($_GET['error']); ?>
						</div>
					<?php endif; ?>

					<form method="post" class="form" action="/register" novalidate>
					<div class="mb-3">
							<label for="username" class="form-label">Username</label>
							<input type="text" class="form-control" id="username" name="username" placeholder="Username" required value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
						</div>
	
                    <div class="mb-3">
							<label for="email" class="form-label">Email address</label>
							<input type="email" class="form-control" id="email" name="email" placeholder="you@example.com" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
						</div>

						<div class="mb-3">
							<label for="password" class="form-label">Password</label>
							<input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
						</div>

						<div class="d-grid">
							<button type="submit" class="btn btn-primary">Create Account</button>
						</div>
						<div class="mt-3 text-center small">
							<a href="/login">Already have an account? Sign in</a>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<?php require(__DIR__ . '/../partials/footer.php'); ?>