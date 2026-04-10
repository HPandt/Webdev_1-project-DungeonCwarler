 <?php

  use App\Models\Templates\RoomTemplate;
  use App\Models\Enums\RoomType;

  /** 
   * @var RoomTemplate|null $room
   */
  $room = $room ?? null;
  $isEdit = $room !== null;

  $pageTitle  = $isEdit ? "Edit {$room->name} Template"
    : "Create Room Template";
  $formAction = $isEdit ? "/admin/rooms/update"
    : "/admin/rooms/create";
  ?>
 <?php require_once(__DIR__ . '/../../partials/header.php'); ?>
 <div class="ms-2 mt-3">
   <a href="/admin/rooms" class="btn btn-success btn-lg ">
     <i class="bi bi-plus-circle"></i> Back to Rooms
   </a>
 </div>

 <div class="container d-flex justify-content-center mb-3 mt-3 text-dark border-radius-10 p-8">
   <div class="card card-form shadow p-4" style="max-width: 600px; width: 100%;">
     <form class="form" method="POST" action="<?php echo $formAction; ?>">
       <?php if ($isEdit): ?>
         <input type="hidden" name="id" value="<?php echo $room->id; ?>">
       <?php endif; ?>
       <h2 class="text-center mb-4 fw-bold"><?= $isEdit ? "Edit {$room->name}" : "Create Room" ?></h2>

       <label for="name">Name:</label>
       <input type="text" name="name"
         value="<?= htmlspecialchars($room->name ?? '') ?>"
         placeholder="Name" required class="form-control mb-2">

       <label for="description">Description:</label>
       <input type="text" name="description"
         value="<?= htmlspecialchars($room->description ?? '') ?>"
         placeholder="Description" required class="form-control mb-2">

       <label for="trap_damage">Trap Damage:</label>
       <input type="number" name="trap_damage"
         value="<?= htmlspecialchars($room->trapDamage ?? '') ?>"
         placeholder="Trap Damage" class="form-control mb-2">

       <label for="type">Type:</label>
       <select name="type" class="form-control mb-2">
         <option value="">Select Type</option>
         <?php foreach (RoomType::cases() as $type): ?>
           <option value="<?= $type->value ?>"
             <?= isset($room) && $room->type->value === $type->value ? 'selected' : '' ?>>
             <?= ucfirst($type->name) ?>
           </option>
         <?php endforeach; ?>
       </select>

       <label for="monster_template">Monster:</label>
       <select name="monster_template" class="form-control mb-2">
         <option value="">Select Monster</option>
         <option value="">No monster</option>
         <?php foreach ($monsters as $monster): ?>
           <option value="<?= $monster->id ?>"
             <?= ($room?->monsterId ?? '') === $monster->id ? 'selected' : '' ?>>
             <?= htmlspecialchars($monster->name) ?>
           </option>
         <?php endforeach; ?>
       </select>
       <button type="submit" class="btn btn-primary w-100">
         <?= $isEdit ? 'Update Room' : 'Create Room' ?>
       </button>
     </form>
   </div>

 </div>





 <?php require_once(__DIR__ . '/../../partials/footer.php'); ?>