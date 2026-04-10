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
 <div class="ms-2 mt-3">
   <a href="/admin/users" class="btn btn-success btn-lg ">
     <i class="bi bi-plus-circle"></i> Back to Users
   </a>
 </div>

 <div class="container d-flex justify-content-center mb-3 mt-3 text-dark border-radius-10 p-8">
   <div class="card card-form shadow p-4" style="max-width: 600px; width: 100%;">
     <form class="form" method="POST" action="<?php echo $formAction; ?>">
       <?php if ($isEdit): ?>
         <input type="hidden" name="id" value="<?php echo $user->id; ?>">
       <?php endif; ?>
       <h2 class="text-center mb-4 fw-bold"><?= $isEdit ? "Edit {$user->name}" : "Create User" ?></h2>

       <label for="username">Username:</label>
       <input type="text" name="username"
         value="<?= htmlspecialchars($user->name ?? '') ?>"
         placeholder="Username" required class="form-control mb-2">

       <label for="email">Email:</label>
       <input type="email" name="email"
         value="<?= htmlspecialchars($user->email ?? '') ?>"
         placeholder="Email" required class="form-control mb-2">


       <label for="role">Role:</label>
       <select name="role" class="form-control mb-2">
         <?php if (!$isEdit): ?>
            <option value="">Select Role</option>
          <?php endif; ?>
         
         <?php foreach (Roles::cases() as $role): ?>
           <option value="<?= $role->value ?>"
             <?= isset($user) && $user->role->value === $role->value ? 'selected' : '' ?>>
             <?= ucfirst($role->name) ?>
           </option>
         <?php endforeach; ?>
       </select>

       <label for="password">Password:</label>
       <?php if ($isEdit): ?>
         <input type="password" name="password" value="<?= htmlspecialchars($user->password_hash ?? '') ?>" disabled placeholder="Password hash" class="form-control mb-2">
       <?php else: ?>
         <input type="password" name="password" value="" required placeholder="Password" class="form-control mb-2">
       <?php endif; ?>

       <button type="submit" class="btn btn-primary w-100">
         <?= $isEdit ? 'Update User' : 'Create User' ?>
       </button>
     </form>
   </div>

 </div>

 <?php require_once(__DIR__ . '/../../partials/footer.php'); ?>