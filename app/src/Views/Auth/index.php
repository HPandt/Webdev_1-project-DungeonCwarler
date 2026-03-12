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

					<form method="POST" class="form" action="/login" novalidate>
						<div class="mb-3">
							<label for="email" class="form-label">Email address</label>
							<input type="email" class="form-control" id="email" name="email" placeholder="you@example.com" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
						</div>

						<div class="mb-3">
							<label for="password" class="form-label">Password</label>
							<input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
						</div>

						<div class="d-grid">
							<button type="submit" class="btn btn-primary">Sign in</button>
						</div>

						<div class="mt-3 text-center small">
							<a href="/register">Create an account</a> · <a href="/forgot-password">Forgot password?</a>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<?php require(__DIR__ . '/../partials/footer.php'); ?>