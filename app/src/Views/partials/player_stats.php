<?php

use App\Models\CharacterModel;

/** @var CharacterModel $character  */ ?>
    
<li>Level: <?= htmlspecialchars($character->level) ?></li>
<li>Hp: <?= htmlspecialchars($character->hp) ?> / <?= htmlspecialchars($character->maxHp) ?></li>
<li>Strength: <?= htmlspecialchars($character->strength) ?></li>
<li>Agility: <?= htmlspecialchars($character->dex) ?></li>
<li>Luck: <?= htmlspecialchars($character->luck) ?></li>
<li>Experience: <?= htmlspecialchars($character->xp) ?></li>