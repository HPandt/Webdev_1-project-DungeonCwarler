 <?php
    use App\Models\UserModel;
    use App\Models\Enums\Roles;
    /** 
     * @var UserModel|null $user
    */
    $user = $user ?? null;
    $isEdit = $user !== null;

    $pageTitle  = $isEdit ? "Edit {$user->name}" 
    : "Create User";
    $formAction = $isEdit ? "/admin/users/update" 
    : "/admin/users/create";
 ?>
<?php require_once(__DIR__ . '/../../partials/header.php'); ?>

<div class="container d-flex justify-content-center mb-3 mt-3 text-dark border-radius-10 p-8">
  <div class="card shadow p-4" style="max-width: 600px; width: 100%;">
    <form class="form" method="POST" action="<?php echo $formAction; ?>">
            <?php if($isEdit): ?>
              <input type="hidden" name="id" value="<?php echo $user->id; ?>">
            <?php endif; ?>   
            <h2 class="text-center mb-4 fw-bold"><?= $isEdit ? "Edit {$user->name}" : "Create User" ?></h2>

            <label for="username">Username:</label>
            <input type="text" name="username"
            value="<?=  htmlspecialchars($user->name ?? '') ?>"
            placeholder="Username" required class="form-control mb-2">

            <label for="email">Email:</label>
            <input type="email" name="email"
            value="<?=  htmlspecialchars($user->email ?? '') ?>"
            placeholder="Email" required class="form-control mb-2">

            
            <label for="type">Type:</label>
            <select name="type" class="form-control mb-2">
              <option value="">Select Type</option>
              <?php foreach(Roles::cases() as $role): ?>
                  <option value="<?= $role->value ?>"
                      <?= isset($room) && $room->type->value === $role->value ? 'selected' : '' ?>>
                      <?= ucfirst($role->name) ?> 
                  </option>
              <?php endforeach; ?>
            </select>
            
            <label for="password">Password:</label>
            <input type="password" name="password" disabled
            value="<?=  htmlspecialchars($user->password_hash ?? '') ?>"
            <?= $isEdit ? 'disabled' : 'required' ?>
            placeholder="Password" class="form-control mb-2">

            <button type="submit" class="btn btn-primary w-100">
              <?= $isEdit ? 'Update User' : 'Create User' ?>
            </button>
      </form>
  </div>
    
</div>

<?php require_once(__DIR__ . '/../../partials/footer.php'); ?>