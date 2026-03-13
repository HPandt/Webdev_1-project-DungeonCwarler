



document.addEventListener('DOMContentLoaded', () => {
    console.log("GameLog loaded");
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
                    appendToLog({
                        type: 'info',
                        text: 'Which way will you proceed now?'
                    });
                    
                    //avoids crashes with api changes
                    if (data.monster && data.monster.name) {
                        appendToLog({
                            type: 'danger',
                            text: `A ${data.monster.name} appears!`
                        });
                    }})
                .catch(error => {
                    console.error("Error choosing direction:", error);
                    appendToLog({
                        type: 'danger',
                        text: "An error occurred while moving. Please try again."
                    });
                });
            });
        });
    const attackButton = document.getElementById('attack-button');
    if (attackButton) {
        attackButton.addEventListener('click', () => {
            fetch("/api/game/attack", {
                method: "POST",
            })
            .then(response => response.json())
            .then(data => {
                if(data.error){
                    appendToLog({
                        type: "danger",
                        text: data.error
                    });
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

                appendToLog({
                    type: 'system',
                    text: "===================="
                });
            })
            .catch(error => {
                console.error("Error attacking:", error);
                appendToLog({
                    type: 'danger',
                    text: "An error occurred while attacking. Please try again."
                });
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