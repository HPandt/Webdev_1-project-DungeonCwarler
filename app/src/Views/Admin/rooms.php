 <?php
  use App\Models\Templates\RoomTemplate;
/** @var RoomTemplate|null $rooms
  */
 ?>
<?php require_once(__DIR__ . '/../partials/header.php'); ?>

<div class="container mt-5 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="fw-bold text-light">Rooms Template</h1>
            <a href="/admin/rooms/create" class="btn btn-success btn-lg">
                <i class="bi bi-plus-circle"></i> Create New Room
            </a>
    </div>
    <?php if(empty($rooms)): ?>
        <div class="alert alert-info text-center py-5">
            <p class="fs-5">No rooms found. <a href="/admin/rooms/create">Create one</a></p>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover table-striped align-middle" border="1">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($rooms as $room): ?>
                    <tr>
                        <td><?= htmlspecialchars($room->name) ?? '' ?></td>
                        <td><?= htmlspecialchars($room->type->value) ?? '' ?></td>
                        <td><?= substr(strip_tags($room->description) ?? '', 0, 20) ?>...</td>
                        <td>
                            <a class="btn btn-success" href="/admin/rooms/edit/<?= $room->id ?>">Edit</a>
                            <form action="/admin/rooms/delete/<?= $room->id ?>" method="POST" style="display: inline;">
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this room?')">Delete</button>
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