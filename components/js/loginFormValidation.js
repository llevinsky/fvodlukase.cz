const form = document.getElementById('form');
const username = document.getElementById('username');
const password = document.getElementById('password');
const phpErrorMessages = document.querySelectorAll('.php-error');

username.addEventListener('input', () => {
    hidePhpErrorMessage();
    validateUsername();
});

password.addEventListener('input', () => {
    hidePhpErrorMessage();
    validatePassword();
});

const hidePhpErrorMessage = () => {
    phpErrorMessages.forEach(errorMessage => {
        errorMessage.style.display = 'none';
    });
};

const setError = (element, message) => {
    const inputControl = element.parentElement;
    const errorDisplay = inputControl.querySelector('.error');

    errorDisplay.innerText = message;
    inputControl.classList.add('error');
    inputControl.classList.remove('success');
    errorDisplay.style.display = 'block';
};

const setSuccess = element => {
    const inputControl = element.parentElement;
    const errorDisplay = inputControl.querySelector('.error');

    errorDisplay.innerText = '';
    inputControl.classList.add('success');
    inputControl.classList.remove('error');
};

const validateUsername = () => {
    const usernameValue = username.value.trim();

    if (usernameValue === '') {
        setError(username, 'Uživatelské jméno je vyžadováno!');
    } else if (usernameValue.length < 9) {
        setError(username, 'Uživatelské jméno musí obsahovat alespoň 8 znaků!');
    } else {
        setSuccess(username);
    }
};

const validatePassword = () => {
    const passwordValue = password.value.trim();
    const usernameValue = username.value.trim();
    const regexPassword = /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[^\w\s]).{8,}$/;

    if (passwordValue === '') {
        setError(password, 'Heslo je vyžadováno!');
    } else if (passwordValue.length < 8) {
        setError(password, 'Heslo musí mít minimálně 8 znaků!');
    } else if (passwordValue === usernameValue) {
        setError(password, 'Heslo nesmí být stejné jako uživatelské jméno!');
    } else if (!regexPassword.test(passwordValue)) {
        setError(password, 'Heslo musí obsahovat alespoň jedno velké písmeno, jedno malé písmeno, jednu číslici a jeden speciální znak!');
    } else {
        setSuccess(password);
    }
};

form.addEventListener('submit', event => {
    validateUsername();
    validatePassword();

    const errorControls = form.querySelectorAll('.input-control.error');

    if (errorControls.length > 0) {
        event.preventDefault();
    }
});
