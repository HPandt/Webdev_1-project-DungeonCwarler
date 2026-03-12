 <?php

use App\Models\CharacterModel;
use App\Models\ViewModels\CharacterViewModel;
 /** @var CharacterViewModel $characters  
  * @var CharacterModel $character
 */
 
 ?>

<div class="card shadow-sm text-center">
    <img class="card-img-top character-img mb-3" style="height: 20rem;" 
            src="/assets/img/<?php echo htmlspecialchars($character->img); ?>" 
            alt="Character Avatar">
    <div class="card-body">
        
        <h5 class="card-title">
            <?php echo htmlspecialchars($character->name); ?>
        </h5>
        <ul class="list-unstyled">
            <?php require __DIR__ . '/player_stats.php'; ?>
        </ul>
    </div>
</div>


            
        
