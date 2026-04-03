<?php

use App\Models\UserModel;

/** @var UserModel|null $users */
?>
<?php require_once(__DIR__ . '/../partials/header.php'); ?>
 
<div class="container mt-5 mb-5">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold text-light">Users</h1>
        <a href="/admin/users/create" class="btn btn-success btn-lg">
            <i class="bi bi-plus-circle"></i> Create New User
        </a>
    </div><?php if(empty($users)): ?>
        <div class="alert alert-info text-center py-5">
            <p class="fs-5">No users found. <a href="/admin/users/create">Create one</a></p>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover table-striped align-middle">
                <thead class="table-dark border-bottom border-3 border-light">
                    <tr>
                        <th style="min-width: 150px;">Name</th>
                        <th style="min-width: 200px;">Email</th>
                        <th style="min-width: 100px;">Role</th>
                        <th style="text-align: center; min-width: 120px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($users as $user): ?>
                        <tr>
                            <td>
                                <strong><?= htmlspecialchars($user->name) ?></strong>
                            </td>
                            <td>
                                <small class="text-muted"><?= htmlspecialchars($user->email) ?></small>
                            </td>
                            <td>
                                <span class="badge <?= $user->role === 'admin' ? 'bg-danger' : 'bg-primary' ?>">
                                    <?= ucfirst($user->role) ?>
                                </span>
                            </td>
                            <td style="text-align: center;">
                                <a href="/admin/users/edit/<?= $user->id ?>" class="btn btn-sm btn-outline-success me-2">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <form action="/admin/users/delete/<?= $user->id ?>" method="POST" style="display: inline;">
                                    <button type="submit" class="btn btn-sm btn-outline-danger" 
                                            onclick="return confirm('Delete <?= htmlspecialchars($user->name) ?>? This action cannot be undone.')">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>



<?php require_once(__DIR__ . '/../partials/footer.php'); ?>