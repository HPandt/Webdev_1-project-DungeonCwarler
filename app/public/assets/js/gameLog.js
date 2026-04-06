

document.addEventListener('DOMContentLoaded', () => {
    console.log("GameLog load...");
    
    const log = document.getElementById('game-log');
    
    fetch("/api/game/start")
    .then(res => res.json())
    .then(data => {
        
        appendToLog({
            type: "info",
            text: `You woke up in the Dungeon ${data.roomName}`
        });
        appendToLog({
            type: "info",
            text: data.description
        });
        appendToLog({
                    type: 'info',
                    text: 'Which way will you venture forward?'
                });
        appendToLog({
            type: "system",
            text: "================================"
        });
    })
    document.querySelectorAll('.direction-button').forEach(button => {
            button.addEventListener('click', () => {
                const direction = button.dataset.direction;
                console.log("Button clicked:", direction);

                fetch("/api/game/choose-direction", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded"
                    },
                    body: `direction=${direction}`
                })
                .then(response => response.text())
                .then(text =>{
                    console.log("RAW TEXT:", text);
                    return JSON.parse(text);
                })
                .then(data => {
                    
                    // Update the log with the new room description
                    // log.textContent = data.description;
                    //divide text by types for more rouge/rpg like feel
                    if (data.error) {
                        appendToLog({
                            type: 'danger',
                            text: data.error
                        });
                        return;
                    }
                    
                    appendToLog({
                        type: 'info',
                        text: `You picked the door in the ${direction} direction.`
                    });
                    appendToLog({
                        type: 'system',
                        text: "===================="
                    });
                    appendToLog({
                        type: 'info',
                        text:`You enter: ${data.roomName}`
                    });
                    appendToLog({
                        type: 'info',
                        text: data.description
                    });

                    console.log("Monster data:", data.monster);
                    console.log("Has monster:", data.monster && data.monster.name);

                    const attackButton = document.getElementById('attack-button');
                    
                    //avoids crashes with api changes
                    if (data.monster && data.monster.name) {
                        console.log("Monster room - disabling movement buttons and show attack");
                        appendToLog({
                            type: 'danger',
                            text: `A ${data.monster.name} appears!`
                        });

                        updateMonsterDisplay(data.monster);                        

                        //disable movement buttons during combat
                        document.querySelectorAll('.direction-button').forEach(btn => {
                            console.log("Disabling button:", btn);
                            btn.disabled = true;
                        });

                        if (attackButton) {
                            console.log("set display to block");
                            attackButton.style.display = 'block';
                            attackButton.disabled = false;
                        } else{
                            console.log('attack btn not found');
                        }

                        //store monster data in session for combat
                        window.currentMonster = data.monster;
                    } else{
                        console.log("No monster in this room");
                        //no monster 
                        appendToLog({
                            type: 'info',
                            text: 'Which way will you proceed now?'
                        });
                        document.querySelectorAll('.direction-button').forEach(btn => {
                            btn.disabled = false;
                        });
                        
                        if (attackButton) {
                            attackButton.style.display = 'none';
                        }
                    }
                })
                .catch(error => {
                    console.error("Error choosing direction:", error);
                    appendToLog({
                        type: 'danger',
                        text: "An error occurred while moving. Please try again."
                    });
                    document.querySelectorAll('.direction-button').forEach(btn => btn.disabled = false);
                });
            });
        });
    const attackButton = document.getElementById('attack-button');
    if (attackButton) {
        attackButton.addEventListener('click', () => {
            console.log("Attack button clicked");
            attackButton.disabled = true; // Disable the button immediately to prevent multiple clicks

            fetch("/api/game/attack", {
                method: "POST",
                headers:{
                    "Content-Type": "application/x-www-form-urlencoded"
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error: ${response.status}` );
                }
                return response.text();
            })
            .then(text => {
                console.log("RAW TEXT:", text);
                try {
                    return JSON.parse(text);
                } catch (error) {
                    console.error("Error parsing JSON:", error);
                    console.error("Response text:", text);
                    throw error;
                }
            })
            .then(data => {
                console.log("Attack response:", data); 
                if(data.error){
                    appendToLog({
                        type: "danger",
                        text: data.error
                    });
                    attackButton.disabled = false;
                    return;
                }

                if (data.turn) {
                    addTurnSeparator(data.turn);
                }

                //combatlog 
                if(data.log && Array.isArray(data.log)){
                    attackButton.disabled = true;
                    data.log.forEach((line, index) => {
                        setTimeout(() => {
                        appendToLog(line);
                        //stops people from spanning attack and delays text
                        if (index === data.log.length -1) {
                            attackButton.disabled = false;
                        }
                    }, index * 150); // Delay each log line by 0ms
                    });
                }
                updateHpDisplay(data.playerHp, data.monsterHp);

                appendToLog({
                    type: 'system',
                    text: "===================="
                });
                if(data.playerDefeated){
                    appendToLog({
                        type: 'danger',
                        text: "You have been defeated! Better luck next time. Quit the game and try again!"
                    });
                    attackButton.style.display = 'none';
                    attackButton.disabled = true;
                    document.querySelectorAll('.direction-button').forEach(btn => btn.disabled = true);
                    return;
                }
                
                // CHECK IF EXIT ROOM
                if (data.type === 'exit') {
                    appendToLog({
                        type: 'success',
                        text: '✨ You have escaped the dungeon! You have won! ✨ Congratulations on your victory! 🎉 You are a true adventurer!'
                    });
                    
                    // Disable all buttons
                    document.querySelectorAll('.direction-button').forEach(btn => btn.disabled = true);
                    attackButton.style.display = 'none';
                    
                    // Create exit game button
                    const exitButton = document.createElement('button');
                    exitButton.className = 'btn btn-success mt-3';
                    exitButton.textContent = 'Exit Game';
                    exitButton.onclick = () => {
                        window.location.href = '/game/dashboard';
                    };
                    
                    const buttonContainer = document.querySelector('.d-flex.justify-content-center');
                    if (buttonContainer) {
                        buttonContainer.innerHTML = '';
                        buttonContainer.appendChild(exitButton);
                    }
                    
                    clearMonsterDisplay();
                    return;
                }

                if (data.playerDefeated) {
                    appendToLog({
                        type: 'danger',
                        text: "You have been defeated! Better luck next time. Quit the game and try again!"
                    });
                    attackButton.style.display = 'none';
                    attackButton.disabled = true;
                    document.querySelectorAll('.direction-button').forEach(btn => btn.disabled = true);
                    
                     // Create exit game button
                    const exitButton = document.createElement('button');
                    exitButton.className = 'btn btn-success mt-3';
                    exitButton.textContent = 'Exit Game';
                    exitButton.onclick = () => {
                        window.location.href = '/game/dashboard';
                    };
                    
                    const buttonContainer = document.querySelector('.d-flex.justify-content-center');
                    if (buttonContainer) {
                        buttonContainer.innerHTML = '';
                        buttonContainer.appendChild(exitButton);
                    }
                    
                    clearMonsterDisplay();
                    return;
                }

                //check if monster is dead
                if (data.monsterDefeated) {
                    appendToLog({
                        type: 'info',
                        text: `You have defeated the ${window.currentMonster.name}!`
                    });
                    attackButton.style.display = 'none';
                    attackButton.disabled = true;
                    //get rid of monster display and show movement options
                    clearMonsterDisplay();
                    document.querySelectorAll('.direction-button').forEach(btn => btn.disabled = false);

                    appendToLog({
                        type: 'info',
                        text: 'You have survied! And must carry on! which way will you proceed now?'
                    });
                    window.currentMonster = null;
                } else{
                    attackButton.disabled = false;
                }
            })
            .catch(error => {
                console.error("Error attacking:", error);
                appendToLog({
                    type: 'danger',
                    text: "An error occurred while attacking. Please try again."
                });
                attackButton.disabled = false;
            });
        });
    }

    function appendToLog(text) {
        const log = document.getElementById("game-log");

        const div = document.createElement('div');
        div.classList.add('log-entry');

        if(typeof text === 'string'){
            div.textContent = text;
        }else{
            div.textContent = text.text;
            div.classList.add(`log-${text.type}`);
        }
        
        requestAnimationFrame(() => {
            div.classList.add("log-visible");
        });
        log.appendChild(div);
        log.scrollTop = log.scrollHeight; // Auto-scroll to the bottom
    }
    function addTurnSeparator(turn){
        appendToLog({
            type: "system",
            text: `---------- Turn ${turn} ----------`
        })
    }
});

function updateMonsterDisplay(monster) {
    const monsterScreen = document.getElementById('monster-screen');
    if(monsterScreen){
        monsterScreen.innerHTML =`
        <div class="card h-100">
                <img
                src="/assets/img/${monster.img}"
                class="card-img-top monster-img"
                
                alt="${monster.name}"
                >
                <div class="card-body d-flex flex-column align-items-center">
                    <h5 class="card-title">${monster.name}</h5>
                    <div class="text-center">
                        <p><strong>HP:</strong> <span id="monster-hp">${monster.currentHp}</span> / ${monster.base_hp}</p>
                        <p><strong>Strength:</strong> ${monster.base_strength}</p>
                    </div>
                </div>
            </div>
        `;
    }
}

function clearMonsterDisplay() {
    const monsterScreen = document.getElementById('monster-screen');
    if(monsterScreen){
        monsterScreen.innerHTML = `
        <div class="text-muted text-center">
            No enemies here.
        </div>
        `;
    }
}

function updateHpDisplay(characterHp, monsterHp){
    //if player
    if (characterHp !== null) {
        const playerHpElement = document.getElementById('player-hp');
        console.log("Updating player HP display:", characterHp);
        if (playerHpElement) {
            playerHpElement.textContent = characterHp;
        }
    }
    //if monster
    if (monsterHp !== null) {
        const monsterHpElement = document.getElementById('monster-hp');
        if (monsterHpElement) {
            monsterHpElement.textContent = monsterHp;
        }
    }
}