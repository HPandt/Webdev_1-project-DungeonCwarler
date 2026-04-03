 <?php
    use App\Models\Templates\CharacterTemplate;
    /** 
     * @var CharacterTemplate[] $characters
    */
 ?>
<?php require_once(__DIR__ . '/../partials/header.php'); ?>


<div class="container mt-5 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold text-dark">Characters Template</h1>
        <a href="/admin/characters/create" class="btn btn-success btn-lg">
            <i class="bi bi-plus-circle"></i> Create New Character
        </a>
    </div>

    <?php if(empty($characters)): ?>
        <div class="alert alert-info text-center py-5">
            <p class="fs-5">No characters found. <a href="/admin/characters/create">Create one</a></p>
        </div>
    <?php else: ?>
        <div class="table-responsive">

            <table class="table table-primary" border="1">
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
                <?php foreach ($characters as $char): ?>
                    <tr>
                        <td><?= htmlspecialchars($char->name) ?? '' ?></td>
                        <td><?= htmlspecialchars($char->maxHp) ?? '' ?></td>
                        <td><?= htmlspecialchars($char->strength) ?? '' ?></td>
                        <td><?= htmlspecialchars($char->dex) ?? '' ?></td>
                        <td>
                            <a class="btn btn-success" href="/admin/characters/edit/<?= $char->id ?>">Edit</a>
                            <form action="/admin/characters/delete/<?= $char->id ?>" method="POST" style="display: inline;">
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this character?')">Delete</button>
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