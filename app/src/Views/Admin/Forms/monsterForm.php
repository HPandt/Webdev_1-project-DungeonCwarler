<?php

use App\Models\Templates\MonsterTemplate;

/** 
 * @var MonsterTemplate|null $monster
 */
$monster = $monster ?? null;
$isEdit = $monster !== null;

$pageTitle  = $isEdit ? "Edit {$monster->name} Template"
   : "Create Monster Template";
$formAction = $isEdit ? "/admin/monsters/update"
   : "/admin/monsters/create";
?>
<?php require_once(__DIR__ . '/../../partials/header.php'); ?>
<div class="ms-2 mt-3">
   <a href="/admin/monsters" class="btn btn-success btn-lg ">
      <i class="bi bi-plus-circle"></i> Back to Monsters
   </a> 
</div>

<div class="container d-flex justify-content-center mb-3 mt-3 text-dark border-radius-10 p-8 ">
   <div class="card card-form shadow p-4" style="max-width: 600px; width: 100%;">
      <form class="form" method="POST" action="<?php echo $formAction; ?>" enctype="multipart/form-data">
         <?php if ($isEdit): ?>
            <input type="hidden" name="id" value="<?php echo $monster->id; ?>">
         <?php endif; ?>
         <h2 class="text-center mb-4 fw-bold"><?= $isEdit ? "Edit {$monster->name}" : "Create Monster" ?></h2>

         <label for="name">Name:</label>
         <input type="text" name="name"
            value="<?= htmlspecialchars($monster->name ?? '') ?>"
            placeholder="Name" required class="form-control mb-2">

         <?php if ($isEdit && !empty($monster->img)): ?>
            <div class="mb-2">
               <img src="/assets/img/<?php echo htmlspecialchars($monster->img); ?>" alt="<?= htmlspecialchars($monster->name) ?>" class="img-fluid mb-2" style="max-height: 200px;" />
            </div>
         <?php endif; ?>

         <label class="form-label" for="img">Image:</label>
         <input type="file" name="img" accept="image/*" class="form-control mb-2">

         <label for="base_hp">Base HP:</label>
         <input type="number" name="base_hp"
            value="<?= htmlspecialchars($monster->hp ?? '') ?>"
            placeholder="Base HP" required class="form-control mb-2">

         <label for="base_strength">Base Strength:</label>
         <input type="number" name="base_strength"
            value="<?= htmlspecialchars($monster->strength ?? '') ?>"
            placeholder="Base Strength" required class="form-control mb-2">

         <label for="base_dex">Base Dexterity:</label>
         <input type="number" name="base_dex"
            value="<?= htmlspecialchars($monster->dex ?? '') ?>"
            placeholder="Base Dexterity" required class="form-control mb-2">

         <label for="xp_reward">XP Reward:</label>
         <input type="number" name="xp_reward"
            value="<?= htmlspecialchars($monster->xp_reward ?? '') ?>"
            placeholder="XP Reward" required class="form-control mb-2">

         <button type="submit" class="btn btn-primary w-100">
            <?= $isEdit ? 'Update Monster' : 'Create Monster' ?>
         </button>
      </form>
   </div>
</div>


<?php require_once(__DIR__ . '/../../partials/footer.php'); ?>