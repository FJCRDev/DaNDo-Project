// document.addEventListener('DOMContentLoaded', function() {
// const calculateProficiencyBonus = (level) => {
//     if (level >= 1 && level <= 4) return 2;
//     if (level >= 5 && level <= 8) return 3;
//     if (level >= 9 && level <= 12) return 4;
//     if (level >= 13 && level <= 16) return 5;
//     if (level >= 17 && level <= 20) return 6;
//     return 0;
// };

// // const calculateBonus = (value) => {
// //     if (value <= 1) return -5;
// //     if (value <= 3) return -4;
// //     if (value <= 5) return -3;
// //     if (value <= 7) return -2;
// //     if (value <= 9) return -1;
// //     if (value <= 11) return 0;
// //     if (value <= 13) return +1;
// //     if (value <= 15) return +2;
// //     if (value <= 17) return +3;
// //     if (value <= 19) return +4;
// //     return +5;
// // };

// const calculateBonus = (value) => {
//     if (isNaN(value) || value === "") return 0;
//     return Math.floor((value - 10) / 2);
// };

// let totalProficiencies = 0;

// const updateProficiencyBonus = () => {
//     const level = parseInt(document.getElementById('level').value) || 1;
//     const proficiencyBonus = calculateProficiencyBonus(level);
//     document.getElementById('proficiency_bonus').value = `+${proficiencyBonus}`;
// };

// const updateAttributeBonuses = () => {
//     document.querySelectorAll('.attribute-input').forEach(input => {
//         const bonus = calculateBonus(parseInt(input.value) || 0);
//         document.getElementById(`${input.id}-bonus`).textContent = `Bonificador: ${bonus}`;
//     });
// };

// const updateProficiencyChoices = () => {
//     const intelligenceValue = parseInt(document.getElementById('intelligence').value) || 10;
//     const intelligenceBonus = calculateBonus(intelligenceValue);
//     const classSelect = document.getElementById('class').value;
//     let classProficiencies = 0;

//     if (classSelect) {
//         fetch(`https://www.dnd5eapi.co/api/classes/${classSelect}`)
//             .then(response => response.json())
//             .then(data => {
//                 classProficiencies = data.proficiency_choices[0].choose || 0;
//                 totalProficiencies = intelligenceBonus + classProficiencies;
//                 document.getElementById('skills-info').textContent = `Tienes ${totalProficiencies} competencias para escoger!`;
//                 updateCheckboxState();
//             });
//     } else {
//         totalProficiencies = intelligenceBonus;
//         document.getElementById('skills-info').textContent = `Tienes ${totalProficiencies} competencias para escoger!`;
//         updateCheckboxState();
//     }
// };



// fetch('https://www.dnd5eapi.co/api/races')
//     .then(response => response.json())
//     .then(data => {
//         const raceSelect = document.getElementById('race');
//         data.results.forEach(race => {
//             const option = document.createElement('option');
//             option.value = race.index;
//             option.textContent = race.name;
//             raceSelect.appendChild(option);
//         });
//         raceSelect.value = "{{ $data['race'] }}";
//     }).then(() => updateProficiencyChoices());

// fetch('https://www.dnd5eapi.co/api/classes')
//     .then(response => response.json())
//     .then(data => {
//         const classSelect = document.getElementById('class');
//         data.results.forEach(cls => {
//             const option = document.createElement('option');
//             option.value = cls.index;
//             option.textContent = cls.name;
//             classSelect.appendChild(option);
//         });
//         classSelect.value = "{{ $data['class'] }}";
//     }).then(() => updateProficiencyChoices());

// document.querySelectorAll('.attribute-input').forEach(input => {
//     input.addEventListener('input', updateAttributeBonuses);
// });

// document.getElementById('level').addEventListener('input', updateProficiencyBonus);
// document.getElementById('class').addEventListener('change', updateProficiencyChoices);

// // Llamadas para inicializar los valores de bono de competencia y atributos al cargar la página
// updateProficiencyBonus();
// updateAttributeBonuses();
// });
