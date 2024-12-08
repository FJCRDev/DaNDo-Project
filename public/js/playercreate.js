
document.addEventListener('DOMContentLoaded', function() {
    //de la misma forma que edit

    // constante que nos calcula el bono de competencia segun el nivel del personaje, a diferencia del bono, no vi ninguna
        // lógica al cálculo y creo que es algo más mecánico por ajustes de balance asi que tuve que hacer una serie de ifs
        // según el rango de nivel del pj.
    const calculateProficiencyBonus = (level) => {
        if (level >= 1 && level <= 4) return 2;
        if (level >= 5 && level <= 8) return 3;
        if (level >= 9 && level <= 12) return 4;
        if (level >= 13 && level <= 16) return 5;
        if (level >= 17 && level <= 20) return 6;
        return 0;
    };

    //Para hacer cumplir requisitos mínimos del proyecto, una función para hacer que el bono de competencia
    // salga con medio segundo de retraso, dando la sensación de cálculo
    // básicamente cogemos el valor de nivel (o 1 si no hay por algun motivo) y hacemos que calcule con lo de antes
    // el bono, después lo ponemos con un + en el valor de ese campo

    // luego que revise si con eso el formulario ya estaría  bien.
    const updateProficiencyBonusWithDelay = () => {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(() => {
            const level = parseInt(document.getElementById('level').value) || 1;
            const proficiencyBonus = calculateProficiencyBonus(level);
            document.getElementById('proficiency_bonus').value = `+${proficiencyBonus}` ;

            checkFormCompletion();
        }, 1000);
    };

    //la función matemática para el bono de atributo, de la misma forma que edit 

    // aquí sin embargo era más fácil, primero nos aseguramos que si de alguna forma el usuario es capaz de poner un valor
        // que no ea un numero o vacío, que para nosotros sea un 0.

        // Si es algo, le restamos 10 al número y entonces lo dividimos entre dos, de la misma forma que en el ejemplo de show
        //  de Esta forma por ejemplo un 18 - 10 = 8 / 2 = 4. Un +4
    const calculateBonus = (value) => {
        if (isNaN(value) || value === "") return 0;
        return Math.floor((value - 10) / 2);
    };


    let timeoutId;
    // igual que con la competencia, el bono de atributo le ponemos un delay para dar otra sensación de cálculo
    const updateBonusWithDelay = (input) => {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(() => {
            const value = parseInt(input.value);
            const bonus = calculateBonus(value);
            // cogemos el XXXX-bonus
            document.getElementById(`${input.id}-bonus`).textContent = `Bonificador: ${bonus}`;
            checkFormCompletion();
        }, 100);
    };

    // aquí perse que cada vez que haya un input en algún atributo que haga el acutalizar el bono con delay de antes
    document.querySelectorAll('.attribute-input').forEach(input => {
        input.addEventListener('input', function() {
            updateBonusWithDelay(this);
        });
    });     

    let totalProficiencies = 0;

    // Con este método nos encargamo de actualizar la cantidad de elecciones de competencias que tiene el usuario a escoger
    const updateProficiencyChoices = () => {
        // Cogemos la estadística de inteligencia (o 10 de default) y la pasamos por la funcion para calcualr el bono
        const intelligenceValue = parseInt(document.getElementById('intelligence').value) || 10;
        const intelligenceBonus = calculateBonus(intelligenceValue);
        // también cogemos que clase es la que ha escogido
        const classSelect = document.getElementById('class').value;
        let classProficiencies = 0;

        // para así aquí coger un fetch de la api de esa clase y recoger el bono de proficiencia que nos da dicha clase,
        // se lo sumamos al bono de inteligencia y eso sera nuestra classProficiencies.

        // Lo ponemso como texto explicatorio y si no tuviera clase cogida todavía, simplemente ponemos el bono de int
        // de esta forma sin importar el orden se actualiza el texto de skills info
        if (classSelect) {
            fetch(`https://www.dnd5eapi.co/api/classes/${classSelect}`)
                .then(response => response.json())
                .then(data => {
                    classProficiencies = data.proficiency_choices[0].choose || 0;
                    totalProficiencies = intelligenceBonus + classProficiencies;
                    document.getElementById('skills-info').textContent = `Tienes ${totalProficiencies} competencias para escoger!`;
                    updateCheckboxState();
                    checkFormCompletion();
                });
        } else {
            totalProficiencies = intelligenceBonus;
            document.getElementById('skills-info').textContent = `Tienes ${totalProficiencies} competencias para escoger!`;
            updateCheckboxState();
            checkFormCompletion();
        }
        // también en ambos casos actualizamos el estado de los checkboxes y si está el formulario listo o no
    };

    //Función donde recogemos todos los checkboxes que esten checked y se lo restamos al número de totalProficiencies
    // ya que es una variable que está creada en las funciones donde usamos esta funcion. Esto irá bajando sin parar
    // Sin embargo cuando las remaining proficiencies sean 0 o menor que 0 que desahbilite TODOS los checkboxes.
    const updateCheckboxState = () => {
        const selectedCheckboxes = document.querySelectorAll('.form-check-input:checked').length;
        const remainingProficiencies = totalProficiencies - selectedCheckboxes;
        document.getElementById('skills-info').textContent = `Tienes ${remainingProficiencies} competencias para escoger!`;
        document.querySelectorAll('.form-check-input').forEach(checkbox => {
            checkbox.disabled = !checkbox.checked && remainingProficiencies == 0 || remainingProficiencies < 0;
        });
    };

    //En esta función revisamos que el formulario esté bien. Para ello recogemos todos los valores en constantes
    // haciendoles trim y asegurandonoos que no esté nulos.

    // También, que las remaining proficiencies sean 0, es decir, que ya estén cogidas todas las skills.
    const checkFormCompletion = () => {
        const nameFilled = document.getElementById('name').value.trim() !== '';
        const raceFilled = document.getElementById('race').value !== '';
        const classFilled = document.getElementById('class').value !== '';
        const levelFilled = document.getElementById('level').value.trim() !== '';
        const attributesFilled = Array.from(document.querySelectorAll('.attribute-input')).every(input => input.value.trim() !== '');
        const remainingProficiencies = totalProficiencies - document.querySelectorAll('.form-check-input:checked').length;
        const competenciesCompleted = remainingProficiencies === 0;

        // Hacemos una constante que solo existe cuando todas las demas cosntantes están, es decir, todo está bien.
        const isFormComplete = nameFilled && raceFilled && classFilled && levelFilled && attributesFilled && competenciesCompleted;
        document.getElementById('save-button').disabled = !isFormComplete;
    };

    //event listener para que cada vez que haya un input de alguna manera en lso campos donde puede haberlo, haga el checkFormCompletion
    // de esta forma no importa cual sea el último campo a rellenar, se verificará correctamente la manera del código.
    document.getElementById('name').addEventListener('input', checkFormCompletion);
    document.getElementById('race').addEventListener('change', checkFormCompletion);
    document.getElementById('class').addEventListener('change', updateProficiencyChoices);
    document.getElementById('intelligence').addEventListener('input', updateProficiencyChoices);
    document.getElementById('level').addEventListener('input', updateProficiencyBonusWithDelay);


    document.querySelectorAll('.form-check-input').forEach(checkbox => {
        checkbox.addEventListener('change', () => {
            updateCheckboxState();
            checkFormCompletion();
        });
    });





    // Función para msotrar por js y front end los errores del formulario
    // Cogemos todos los  valores rellenabel del mismo y los guardamos en variables
    // De esta forma podemos mirar uno por uno si está todo bien
    const updateErrorMessages = () => {
        const name = document.getElementById('name').value;
        const race = document.getElementById('race').value;
        const classs = document.getElementById('class').value;
        const level = parseInt(document.getElementById('level').value);
        const attributes = ['strength', 'dexterity', 'constitution', 'intelligence', 'wisdom', 'charisma'];

        // accedemos a los XXXX-error y ponemos un checkeo sencillo, en estos casos que el nombre no sea largo
        // que la raza exista
        // que la clase exista
        // que el nivel sea un número comprendido entre 1 y 20
        document.getElementById('name-error').textContent = name.length > 25 ? 'El nombre no puede exceder 25 caracteres.' : '';
        document.getElementById('race-error').textContent = !race ? 'Debes seleccionar una raza.' : '';
        document.getElementById('class-error').textContent = !classs ? 'Debes seleccionar una clase.' : '';
        document.getElementById('level-error').textContent = isNaN(level) || level < 1 || level > 20 ? 'El nivel debe estar entre 1 y 20.' : '';
        
        // para los atributos igual, que sean un número comprendido entre 1 y 20
        attributes.forEach(attr => {
            const value = parseInt(document.getElementById(attr).value);
            document.getElementById(`${attr}-bonus`).textContent = `Bonificador: ${calculateBonus(value)}`;
            document.getElementById(`${attr}-error`).textContent = isNaN(value) || value < 8 || value > 20 ? 'El valor debe estar entre 8 y 20.' : '';
        });
    };

     //Despues pasamos por todos los campos rellenables y, de nuevo, vamos haciendo listeners de inputs para verifiar los errores.
    document.getElementById('name').addEventListener('input', updateErrorMessages);
    document.getElementById('race').addEventListener('change', updateErrorMessages);
    document.getElementById('class').addEventListener('change', updateErrorMessages);
    document.getElementById('level').addEventListener('input', updateErrorMessages);
    document.querySelectorAll('.attribute-input').forEach(input => {
        input.addEventListener('input', updateErrorMessages);
    });


    // Fetch de las razas para hacer un appendchild a la lista de opciones
    fetch('https://www.dnd5eapi.co/api/races')
        .then(response => response.json())
        .then(data => {
            const raceSelect = document.getElementById('race');
            data.results.forEach(race => {
                const option = document.createElement('option');
                option.value = race.index;
                option.textContent = race.name;
                raceSelect.appendChild(option);
            });
        });

    // Fetch de las clases para hacer un appendchild a la lista de opciones
    fetch('https://www.dnd5eapi.co/api/classes')
        .then(response => response.json())
        .then(data => {
            const classSelect = document.getElementById('class');
            data.results.forEach(cls => {
                const option = document.createElement('option');
                option.value = cls.index;
                option.textContent = cls.name;
                classSelect.appendChild(option);
            });
        });


    // Un listener al elemento de la raza para que cuando lo cambiemos por la lista, haga un fetch extra donde recoger el valor de la speed
    // y añadirlo al campo, aquí podríamos por la misma lógica ocger más cosas de la API, o incluso hacer un IDEM para la clase y por ejemplo
    // listar habilidades de la misma, pudiendo consultar el nivel del personaje y así listar hasta donde podrían llegar las cosas que tiene
    // desbloqueado el pj.
    document.getElementById('race').addEventListener('change', function() {
        const selectedRace = this.value;
        if (selectedRace) {
            fetch(`https://www.dnd5eapi.co/api/races/${selectedRace}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('speed').value = data.speed;
                });
        }
    });

    // Llamamos estas funciones nada mas inicializar el DOM para así asegurarnos que ya salen los errores y los checkboxes están tapados.
 updateErrorMessages();
 updateCheckboxState();

});