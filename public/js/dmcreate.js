// document.addEventListener('DOMContentLoaded', function () {
//     const selectedCharacters = new Set();

//     function fetchPlayers() {
//         fetch("{{ route('dm.getPlayers') }}")
//             .then(response => response.json())
//             .then(players => {
//                 const playersList = document.getElementById('players-list');
//                 playersList.innerHTML = '';

//                 players.forEach(player => {
//                     const row = document.createElement('tr');
//                     row.setAttribute('data-id', player.id);
//                     row.classList.add('player-row');
//                     row.style.cursor = 'pointer';
//                     row.innerHTML = `
//                         <td>${player.name}</td>
//                         <td>${player.email}</td>
//                     `;
//                     playersList.appendChild(row);
//                 });

//                 document.querySelectorAll('.player-row').forEach(row => {
//                     row.addEventListener('click', function () {
//                         document.querySelectorAll('.player-row').forEach(r => r.classList.remove('table-active'));
//                         this.classList.add('table-active');
//                         const playerId = this.getAttribute('data-id');
//                         fetchCharacterSheets(playerId);
//                     });
//                 });
//             })
//             .catch(error => console.error('Error al obtener jugadores:', error));
//     }

//     function fetchCharacterSheets(playerId) {
//         fetch(`/dm/players/${playerId}/character-sheets`)
//             .then(response => response.json())
//             .then(sheets => {
//                 const sheetsList = document.getElementById('character-sheets-list');
//                 sheetsList.innerHTML = '';

//                 sheets.forEach(sheet => {
//                     const row = document.createElement('tr');
//                     row.innerHTML = `
//                         <td>${sheet.name}</td>
//                         <td>${sheet.level}</td>
//                         <td>${sheet.race}</td>
//                         <td>${sheet.class}</td>
//                         <td><button type="button" class="btn btn-success btn-sm add-character-btn" data-id="${sheet.id}" data-name="${sheet.name}" data-level="${sheet.level}" data-race="${sheet.race}" data-class="${sheet.class}">Añadir personaje</button></td>
//                     `;
//                     sheetsList.appendChild(row);
//                 });

//                 document.querySelectorAll('.add-character-btn').forEach(button => {
//                     button.addEventListener('click', function () {
//                         const characterData = {
//                             id: this.getAttribute('data-id'),
//                             name: this.getAttribute('data-name'),
//                             level: this.getAttribute('data-level'),
//                             race: this.getAttribute('data-race'),
//                             class: this.getAttribute('data-class')
//                         };
//                         addCharacterToSession(characterData);
//                     });
//                 });
//             })
//             .catch(error => console.error('Error al obtener fichas de personaje:', error));
//     }

//     function addCharacterToSession(character) {
//         const selectedCharactersList = document.getElementById('selected-characters-list');

//         if (selectedCharacters.has(character.id)) {
//             console.log(`Personaje ya existe en la lista con ID: ${character.id}`);
//             return;
//         }

//         const characterRow = document.createElement('tr');
//         characterRow.setAttribute('data-id', character.id);
//         characterRow.innerHTML = `
//             <td>${character.name}</td>
//             <td>${character.level}</td>
//             <td>${character.race}</td>
//             <td>${character.class}</td>
//             <td><button type="button" class="btn btn-danger btn-sm remove-character-btn" data-id="${character.id}">Eliminar</button></td>
//         `;

//         selectedCharactersList.appendChild(characterRow);
//         selectedCharacters.add(character.id);
//         updateSelectedCharactersInput();

//         characterRow.querySelector('.remove-character-btn').addEventListener('click', function () {
//             selectedCharacters.delete(character.id);
//             characterRow.remove();
//             updateSelectedCharactersInput();
//         });
//     }

//     function updateSelectedCharactersInput() {
//     const characterIds = Array.from(selectedCharacters);
//         document.getElementById('character_ids').value = JSON.stringify(characterIds);
//     }

//     fetchPlayers();

//     document.getElementById('create-session-btn').addEventListener('click', function (event) {
//         // Log de datos antes del envío
//         const title = document.getElementById('title').value;
//         const description = document.getElementById('description').value;
//         const date = document.getElementById('date').value;
//         const characterIds = document.getElementById('character_ids').value;
//         // Verificar si hay personajes seleccionados
//         if (!characterIds || characterIds === '[]') {
//             alert("Seleccione al menos un personaje para la sesión.");
//             return;
//         }

//         // Enviar el formulario después de loggear
//         document.getElementById('session-form').submit();
//     });

//     const form = document.getElementById('session-form');
//     const titleInput = document.getElementById('title');
//     const dateInput = document.getElementById('date');
//     const errors = {
//         title: document.getElementById('title-error'),
//         date: document.getElementById('date-error')
//     };

//     function validateInput() {
//         errors.title.textContent = '';
//         errors.date.textContent = '';

//         if (titleInput.value.length === 0 || titleInput.value.length > 25) {
//             errors.title.textContent = 'El título debe tener entre 1 y 25 caracteres.';
//         }



//         if (!dateInput.value) {
//             errors.date.textContent = 'La fecha de la sesión es obligatoria.';
//         }
//         const today = new Date().toISOString().split('T')[0];
//         if (dateInput.value < today) {
//             errors.date.textContent = 'La fecha de la sesión no puede ser anterior a la fecha actual.';
//         }
//     }

//     titleInput.addEventListener('input', validateInput);
//     dateInput.addEventListener('change', validateInput);
//     validateInput();
// });
