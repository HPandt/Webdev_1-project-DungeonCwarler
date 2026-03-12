<?php

use App\Models\MonsterModel;
use App\Models\ViewModels\MonsterViewModel;

/** @var MonsterViewModel|null $monster
  */
?>

<div class="monster-screen">
    <?php if (!$monster): ?>
        <div class="text-muted text-center">
            No enemies here.
        </div>
    <?php else: ?>
        <div class="monster-card card m-2" style="width: 18rem;">
            <img
                src="<?= htmlspecialchars($monster->img) ?>"
                class="card-img-top"
                alt="<?= htmlspecialchars($monster->name) ?>"
            >
            <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($monster->name) ?></h5>
                <p class="card-text">
                    HP: <?= htmlspecialchars($monster->currentHp)?>/<?= htmlspecialchars($monster->Hp)?><br>
                    Strength: <?= htmlspecialchars($monster->strength) ?><br>
                    Agility: <?= htmlspecialchars($monster->agility) ?><br>
                </p>
            </div>
        </div>
    <?php endif; ?>
</div>
