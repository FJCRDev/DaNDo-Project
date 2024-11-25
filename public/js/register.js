// como buena praxis vista a lo largo del curso y sobre todo este mes y pico durante las prácticas, siempre
// intentaré referenciar y crear las funciones y variables en inglés.

document.addEventListener('DOMContentLoaded', function() {
    // todas las constantes con las que vamos a jugar en la validación, es decir, los 4 campos a rellenar
    // (rol no lo cuento porque por predeterminado e ya uno)
    // y el botón de submit
    const nameinput = document.getElementById('name');
    const emailInput = document.getElementById('email');
    const passInput = document.getElementById('password');
    const passInputConfirm = document.getElementById('password_confirmation');
    const submitBtn = document.getElementById('submitBtn');

    //función para ver si el nombre es correcto
    function validateName() {
        if (nameinput.value.length > 10) {
            // Si el tamaño del campo fuese superior a 10, añadimos la clase para bootstrap de que es inválido para que salga con
            // el borde rojo y la alerta
            nameinput.classList.add('is-invalid');
            // devolviendo para que en el campo que creamos donde mostrar el error, se ponga el string explicando que es muy largo.
            return 'El nombre no debe superar los 10 caracteres.';
            // Misma lógica si está vacío
        } if (nameinput.value.length == 0){
            nameinput.classList.add('is-invalid');
            return 'El nombre es obligatorio.';
        }else {
            // Si todo esta bien, removemos la clase de inválido para que así no se quede puesto en caso de que lo hubieramos puesto
            // porque el usuario se pasase, por ejemplo.
            nameinput.classList.remove('is-invalid');
            return '';
        }
    }

    //función para validad el email
    function validateEmail() {
        // tuve que buscar en internet esto en concreo y segun el VSC está mal escrito pero funciona.
        // basicamente al hacer test comprueba si cumple o no el checkeo, con un booleano nos diría si eso sería correcto o no
        // de esta forma, cuando fuese correcto cambiará todo al else y remueve el campo inválido
        if (!/\.(com|es)$/.test(emailInput.value)) {
            emailInput.classList.add('is-invalid');
            return 'El correo debe terminar en .com o .es.';
        } else {
            emailInput.classList.remove('is-invalid');
            return '';
        }
    }

    //función para validad la contraseña
    function validatePassword() {
        // como aquí pueden salir varios errores, el tamaño, que tenga o no un número, que tenga o no un símbolo etc pues hacemos
        // una constante que es un array vacio al que le volcamos los errores conforme vayan saliendo.
        const errors = [];
        // si es menor que 8
        if (passInput.value.length < 8) {
            errors.push('La contraseña debe tener al menos 8 caracteres.');
        }
        // si NO tiene numero
        if (!/[0-9]/.test(passInput.value)) {
            errors.push('Debe contener un número.');
        }
        // si NO tiene simbolo especial
        if (!/[\W_]/.test(passInput.value)) {
            errors.push('Debe contener un símbolo especial.');
        }
        // si en algun momento, el array tiene algo, es decir, hay algun error, que ponga la clase inválido
        if (errors.length > 0) {
            passInput.classList.add('is-invalid');
            // sino, que lo quite
        } else {
            passInput.classList.remove('is-invalid');
        }
        // con esto hacemos que no salgan todos en linea recta y que vayan haciendo un salto de linea entre errores
        return errors.join('<br>');
    }

    //función para comprobar que la contraseña puesta en la confirmación es igual
    function validatePasswordConfirmation() {
        // si no tiene el mismo valor que la const de la pass original, que sea inválido, sino, que quite el inválido
        if (passInputConfirm.value !== passInput.value) {
            passInputConfirm.classList.add('is-invalid');
            return 'Las contraseñas no coinciden.';
        } else {
            passInputConfirm.classList.remove('is-invalid');
            return '';
        }
    }

    //una función que coge TODOS los innerHTML de los campos de errores que dejamos preparados en el formulario y les hace
    // validar la función correspondiente a cada uno
    function updateFormState() {
        document.getElementById('nameError').innerHTML = validateName();
        document.getElementById('emailError').innerHTML = validateEmail();
        document.getElementById('passwordError').innerHTML = validatePassword();
        document.getElementById('passwordConfirmationError').innerHTML = validatePasswordConfirmation();

        // mientras cualquier campo con la clase is invalid tenga un tamaño superior a 0, es decir, que tenga algo escrito informando
        // de su error, deshabilita el botón de submit.
        submitBtn.disabled = document.querySelectorAll('.is-invalid').length > 0;
    }

    //Para asegurarnos que ocurre con cada tecleo del usuario, hacemos un event listener de cada uno con inputs en sus campos.
    nameinput.addEventListener('input', updateFormState);
    emailInput.addEventListener('input', updateFormState);
    passInput.addEventListener('input', updateFormState);
    passInputConfirm.addEventListener('input', updateFormState);

    // Al ifnal de todo ya, corremos la funcion que revisa si está todo bien para: primero que informe al usuario de primeras que
    // tiene que rellenar de primeras y para que nos deshabilite el botón de submit y evitar que lo pulsen sin rellenar.
    updateFormState();
});