
<?php require_once(__DIR__ . '/../partials/header.php'); ?>

<div class="container py-5">
	<div class="row justify-content-center align-items-center" style="min-height:60vh;">
		<div class="col-md-6 col-lg-5">
			<div class="card shadow-sm">
				<div class="card-body p-4">
					<h2 class="card-title text-center mb-3">Dungeon Crawler</h2>

					<?php if(!empty($_GET['error'])): ?>
						<div class="alert alert-danger" role="alert">
							<?php echo htmlspecialchars($_GET['error']); ?>
						</div>
					<?php endif; ?>

					<a href="/game/start" class="btn btn-primary mb-2 w-100">Start Game</a>
                    <a type="button" class="btn btn-secondary mb-2 w-100" disabled>Load Game</a>
					<form method="POST" action="/logout">
						<button type="submit" class="btn btn-danger btn-lg w-100">
							Exit Game
						</button>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>


<?php require_once(__DIR__ . '/../partials/footer.php'); ?>