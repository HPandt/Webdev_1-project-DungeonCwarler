
<?php require_once(__DIR__ . '/../partials/header.php'); ?>

<div class="container py-5">
	<div class="row justify-content-center align-items-center" style="min-height:60vh;">
		<div class="col-md-4 col-lg-3 gap-2">
			<div class="card shadow-sm">
                <img class="img img-responsive img-card-top" src="" alt="" srcset="">
				<div class="card-body p-4">

					<h2 class="card-title text-center mb-3">Characters</h2>

					<?php if(!empty($_GET['error'])): ?>
						<div class="alert alert-danger" role="alert">
							<?php echo htmlspecialchars($_GET['error']); ?>
						</div>
					<?php endif; ?>

					<a href="/admin/characters" class="btn btn-success mb-2 w-100">Manage Characters</a>
				</div>
			</div>
		</div>
        <div class="col-md-4 col-lg-3 gap-2">
			<div class="card shadow-sm">
				<div class="card-body p-4">
                    <img class="img img-responsive img-card-top" src="" alt="" srcset="">
					<h2 class="card-title text-center mb-3">Monster management</h2>

					<?php if(!empty($_GET['error'])): ?>
						<div class="alert alert-danger" role="alert">
							<?php echo htmlspecialchars($_GET['error']); ?>
						</div>
					<?php endif; ?>

					<a href="/admin/monsters" class="btn btn-warning mb-2 w-100">Manage Monsters</a>
				</div>
			</div>
		</div>
        <div class="col-md-4 col-lg-3 gap-2">
			<div class="card shadow-sm">
				<div class="card-body p-4">
                    <img class="img img-responsive img-card-top" src="" alt="" srcset="">
                    <h2 class="card-title text-center mb-3">Room management</h2>

					<?php if(!empty($_GET['error'])): ?>
						<div class="alert alert-danger" role="alert">
							<?php echo htmlspecialchars($_GET['error']); ?>
						</div>
					<?php endif; ?>
                
					<a href="/admin/rooms" class="btn btn-primary mb-2 w-100">Manage Rooms</a>
					
				</div>
			</div>
		</div>
		<div class="col-md-4 col-lg-3 gap-2">
			<div class="card shadow-sm">
				<div class="card-body p-4">
                    <img class="img img-responsive img-card-top" src="" alt="" srcset="">
                    <h2 class="card-title text-center mb-3">User management</h2>

					<?php if(!empty($_GET['error'])): ?>
						<div class="alert alert-danger" role="alert">
							<?php echo htmlspecialchars($_GET['error']); ?>
						</div>
					<?php endif; ?>
                
					<a href="/admin/users" class="btn btn-primary mb-2 w-100">Manage Users</a>
					
				</div>
			</div>
		</div>
	</div>
    <form method="POST" action="/logout">
        <button type="submit" class="btn btn-danger btn-lg w-20 mt-4 mb-2 mx-auto d-block">
            Exit Admin Panel
        </button>
    </form>
</div>


<?php require_once(__DIR__ . '/../partials/footer.php'); ?>