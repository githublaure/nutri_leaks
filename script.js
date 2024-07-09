document.addEventListener('DOMContentLoaded', function() {
    let deleteMode = false;
    let score = 0;
    let bestScore = localStorage.getItem('bestScore') || 0;
    let remainingPairs = 0;

    console.log('Current user:', currentUser);
    console.log('Current role:', currentRole);

    function fetchFoods() {
        console.log('Fetching foods...');
        return fetch('get_foods.php')
            .then(response => {
                console.log('Response received:', response);
                return response.json();
            })
            .then(data => {
                console.log('Data fetched:', data); // Vérifiez les données
                return data;
            })
            .catch(error => console.error('Error fetching foods:', error));
    }

    function setupGame(foods, role) {
        console.log('Setting up game with foods:', foods);
        const foodsContainer = document.getElementById('foods');
        const benefitsContainer = document.getElementById('benefits');
        foodsContainer.innerHTML = ''; // Réinitialiser le contenu du conteneur des aliments
        benefitsContainer.innerHTML = ''; // Réinitialiser le contenu du conteneur des bienfaits

        // Mélanger les aliments
        shuffleArray(foods);

        remainingPairs = foods.length; // Initialiser le compteur de paires restantes

        foods.forEach(food => {
            const foodElement = document.createElement('div');
            foodElement.classList.add('item');

            // Ajouter un conteneur pour le texte de l'aliment pour éviter que le bouton ne soit ajouté au texte
            const foodText = document.createElement('span');
            foodText.textContent = food.name;
            foodText.classList.add('food-text');
            foodElement.appendChild(foodText);

            foodElement.id = `food-${food.id}`;
            makeDraggable(foodElement);

            // Ajouter le bouton de suppression si l'utilisateur est admin
            if (role === 'admin') {
                const deleteButton = document.createElement('button');
                deleteButton.textContent = 'Supprimer';
                deleteButton.classList.add('delete-button');
                deleteButton.addEventListener('click', () => deleteFood(food.id));
                deleteButton.style.display = 'none'; // Cacher par défaut
                foodElement.appendChild(deleteButton);
            }

            foodsContainer.appendChild(foodElement);
            console.log('Added food element:', foodElement);
        });

        // Mélanger les bienfaits
        shuffleArray(foods);

        foods.forEach(food => {
            const benefitElement = document.createElement('div');
            benefitElement.classList.add('item', 'benefit');
            benefitElement.textContent = food.benefit;
            benefitElement.setAttribute('data-food-id', food.id);
            makeDroppable(benefitElement);

            benefitsContainer.appendChild(benefitElement);
            console.log('Added benefit element:', benefitElement);
        });

        if (role === 'admin') {
            const deleteOptions = document.getElementById('show-delete-options');
            if (deleteOptions) {
                deleteOptions.addEventListener('click', (e) => {
                    e.preventDefault(); // Empêche le comportement par défaut du lien
                    deleteMode = !deleteMode;
                    toggleDeleteOptions(deleteMode);
                });
            }
        }

        updateScoreBoard();
    }

    function makeDraggable(element) {
        element.draggable = true;
        element.addEventListener('dragstart', (e) => {
            e.dataTransfer.setData('text/plain', element.id);
        });

        element.addEventListener('touchstart', (e) => {
            currentDraggedElementId = element.id;
        }, false);

        element.addEventListener('touchend', (e) => {
            currentDraggedElementId = null;
        }, false);
    }

    function makeDroppable(element) {
        element.addEventListener('dragover', (e) => {
            e.preventDefault();
            element.classList.add('drag-enter');
        });

        element.addEventListener('dragleave', () => {
            element.classList.remove('drag-enter');
        });

        element.addEventListener('drop', (e) => {
            handleDrop(element, e.dataTransfer.getData('text/plain').replace('food-', ''));
        });

        element.addEventListener('touchend', (e) => {
            if (currentDraggedElementId) {
                handleDrop(element, currentDraggedElementId.replace('food-', ''));
            }
        }, false);
    }

    function handleDrop(element, id) {
        console.log('Handling drop for element with id:', id);
        if (id === element.getAttribute('data-food-id')) {
            const foodElement = document.getElementById(`food-${id}`);
            const foodText = foodElement.querySelector('.food-text').textContent; // Récupérer uniquement le texte de l'aliment
            element.textContent = `${element.textContent} - ${foodText}`;
            foodElement.style.display = 'none';
            score++;
            remainingPairs--;
        } else {
            element.classList.add('drag-wrong');
            setTimeout(() => {
                element.classList.remove('drag-wrong');
            }, 1500);
            score--;
        }
        element.classList.remove('drag-enter');
        currentDraggedElementId = null;
        if (remainingPairs === 0) {
            gameFinished();
        }
        updateScoreBoard();
    }

    function toggleDeleteOptions(show) {
        console.log('Toggling delete options:', show);
        document.querySelectorAll('.delete-button').forEach(button => {
            button.style.display = show ? 'inline' : 'none';
        });
    }

    function deleteFood(id) {
        console.log('Deleting food with id:', id);
        fetch('delete_food.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id: id })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById(`food-${id}`).remove();
                document.querySelector(`[data-food-id="${id}"]`).remove();
            } else {
                alert('Error deleting food: ' + data.message);
            }
        });
    }

    function gameFinished() {
        alert('Félicitations ! Vous avez terminé le jeu.');
        if (score > bestScore) {
            bestScore = score;
            localStorage.setItem('bestScore', bestScore);
        }
        saveScore(currentUser, score);  // Enregistrer le score dans la base de données
    }

    function saveScore(username, score) {
        fetch('save_score.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ username: username, score: score })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('Score saved successfully.');
                displayBestScore();  // Mettre à jour le tableau des scores
            } else {
                alert('Error saving score: ' + data.message);
            }
        });
    }

    function displayBestScore() {
        fetch('get_best_score.php')
        .then(response => response.json())
        .then(data => {
            const bestScoreBoard = document.getElementById('best-score-board');
            bestScoreBoard.innerHTML = `<p>Best Score: ${data.score} by ${data.username}</p>`;
        });
    }

    function shuffleArray(array) {
        for (let i = array.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [array[i], array[j]] = [array[j], array[i]];
        }
    }

    // Appeler displayBestScore lors du chargement de la page
    fetchFoods().then(data => {
        const foods = data.foods;
        const role = data.role;
        console.log('Fetched foods:', foods); // Debugging log
        console.log('User role:', role); // Debugging log
        setupGame(foods, role);
        displayBestScore();  // Mettre à jour le tableau des scores
    });

    document.getElementById('restart-game').addEventListener('click', () => {
        score = 0; // Réinitialiser le score
        fetchFoods().then(data => {
            const foods = data.foods;
            const role = data.role;
            setupGame(foods, role);
        });
    });
});
