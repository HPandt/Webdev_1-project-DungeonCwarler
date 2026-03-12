 <?php
    use App\Models\Templates\CharacterTemplate;
    /** 
     * @var CharacterTemplate[] $characters
    */
 ?>
<pre>
<!-- var_dump($charactersVm->characters);  -->
</pre>

<?php require_once(__DIR__ . '/../partials/header.php'); ?>

<div class="container py-5">
	<div class="row justify-content-center align-items-center" style="min-height:60vh;">
		<h2 class="text-center mb-4">Choose Your Character</h2>
        <?php foreach($characters as $character): ?>
            <div class="col-md-4 col-lg-3 mb-3">
                <div class="card h-100 shadow-sm text-center">
                    <img src="/assets/img/<?= $character->img ?>" class="card-img-top" alt="<?= htmlspecialchars($character->name) ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($character->name) ?></h5>
                        <p class="card-text">
                            Class: <?= htmlspecialchars($character->classLabel()) ?> <br>
                            Strength: <?= htmlspecialchars($character->strength) ?> <br>
                            HP: <?= htmlspecialchars($character->maxHp) ?> <br>
                            Dex: <?= htmlspecialchars($character->dex) ?>
                        </p>
                    </div>
                    <div class="card-footer">
                        <form method="POST" action="/game/startGame">
                            <input type="hidden" name="character_id" value="<?= htmlspecialchars($character->id) ?>">
                            <button type="submit" class="btn btn-primary w-100">Start Game</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
	</div>
</div>


<?php require_once(__DIR__ . '/../partials/footer.php'); ?>