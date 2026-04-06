<?php

use App\Models\MonsterModel;
use App\Models\Templates\MonsterTemplate;

/** @var MonsterTemplate|null $monsters
  */
?>
<?php require_once(__DIR__ . '/../partials/header.php'); ?>

<div class="container mt-5 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold text-dark">Monster Templates</h1>
        <a href="/admin/monsters/create" class="btn btn-success btn-lg">
            <i class="bi bi-plus-circle"></i> Create New Monster
        </a>
        <a href="/admin/dashboard" class="btn btn-success btn-lg ms-2">
            <i class="bi bi-plus-circle"></i> Go back
        </a>
    </div>

    <?php if(empty($monsters)): ?>
        <div class="alert alert-info text-center py-5">
            <p class="fs-5">No monsters found. <a href="/admin/monsters/create">Create one</a></p>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover table-striped align-middle" border="1">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>HP</th>
                        <th>Strength</th>
                        <th>Dex</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($monsters as $monster): ?>
                    
                        <tr>
                            <td><?= htmlspecialchars($monster->name) ?? '' ?></td>
                            <td><?= htmlspecialchars($monster->hp) ?? '' ?></td>
                            <td><?= htmlspecialchars($monster->strength) ?? '' ?></td>
                            <td><?= htmlspecialchars($monster->dex) ?? '' ?></td>
                            <td>
                                <a class="btn btn-success" href="/admin/monsters/edit/<?= $monster->id ?>">Edit</a>
                                <form action="/admin/monsters/delete/<?= $monster->id ?>" method="POST" style="display: inline;">
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this monster?')">Delete</button>
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