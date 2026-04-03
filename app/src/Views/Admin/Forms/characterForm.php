 <?php
    use App\Models\Templates\CharacterTemplate;
    use App\Models\Enums\CharacterClass;
    /** 
     * @var CharacterTemplate|null $character
    */
    $character = $character ?? null;
    $isEdit = $character !== null;

    $pageTitle  = $isEdit ? "Edit {$character->name} Template" 
    : "Create Character Template";
    $formAction = $isEdit ? "/admin/characters/update" 
    : "/admin/characters/create";
 ?>
 <?php require_once(__DIR__ . '/../../partials/header.php'); ?>
 <div class="container d-flex justify-content-center mb-3 mt-3 text-dark border-radius-10 p-8 ">
  <div class="card shadow p-4" style="max-width: 600px; width: 100%;">
  <form class="form" method="POST" action="<?php echo $formAction; ?>">
          <?php if($isEdit): ?>
            <input type="hidden" name="id" value="<?php echo $character->id; ?>">
          <?php endif; ?>   
          <h2 class="text-center mb-4 fw-bold"><?= $isEdit ? "Edit {$character->name}" : "Create Character" ?></h2>

          <label class="form-label" for="name">Character name:</label>
          <input type="text" name="name"
          value="<?=  htmlspecialchars($character->name ?? '') ?>"
          placeholder="Name" required class="form-control mb-2">

          <label class="form-label" for="hp">Base HP:</label>
          <input type="number" name="base_hp"
          value="<?=  htmlspecialchars($character->maxHp ?? '') ?>"
          placeholder="Base HP" required class="form-control mb-2">

          <label class="form-label" for="base_strength">Base Strength:</label>
          <input type="number" name="base_strength"
          value="<?=  htmlspecialchars($character->strength ?? '') ?>"
          placeholder="Base Strength" required class="form-control mb-2">

          <label class="form-label" for="base_dex">Base Dexterity:</label>
          <input type="number" name="base_dex"
          value="<?=  htmlspecialchars($character->dex ?? '') ?>"
          placeholder="Base Dexterity" required class="form-control mb-2">

          <label class="form-label" for="base_luck">Base Luck:</label>
          <input type="number" name="base_luck"
          value="<?=  htmlspecialchars($character->luck ?? '') ?>"
          placeholder="Base Luck" required class="form-control mb-2">

          <label class="form-label" for="class">Choose Class:</label>
          <select name="class" class="form-control mb-2" required>
            <option value="">Select Class</option>
            <?php foreach(CharacterClass::cases() as $class): ?>
                <option value="<?= $class->value ?>"
                    <?= isset($character) && $character->class->value === $class->value ? 'selected' : '' ?>>
                    <!-- Display the class name with the first letter capitalized -->
                    <?= ucfirst($class->name) ?> 
                </option>
            <?php endforeach; ?>
          </select>
          <button type="submit" class="btn btn-primary w-50 mx-auto mt-3 mb-2">
            <?= $isEdit ? 'Update Character' : 'Create Character' ?>
          </button>
    </form>
  </div>
  
 </div>



<?php require_once(__DIR__ . '/../../partials/footer.php'); ?>