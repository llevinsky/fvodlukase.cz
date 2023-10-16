const form = document.getElementById('form');
const username = document.getElementById('username');
const firstName = document.getElementById('firstname');
const lastName = document.getElementById('lastname');
const email = document.getElementById('email');
const password = document.getElementById('password');
const password2 = document.getElementById('password2');

// form.addEventListener('submit', e => {
//     e.preventDefault();
// });

username.addEventListener('input', () => {
    validateUsername();
});

firstName.addEventListener('input', () => {
    validateFirstName();
});

lastName.addEventListener('input', () => {
    validateLastName();
});

email.addEventListener('input', () => {
    validateEmail();
});

password.addEventListener('input', () => {
    validatePassword();
});

password2.addEventListener('input', () => {
    validatePassword2();
});

const setError = (element, message) => {
    const inputControl = element.parentElement;
    const errorDisplay = inputControl.querySelector('.error');

    errorDisplay.innerText = message;
    inputControl.classList.add('error');
    inputControl.classList.remove('success');
};

const setSuccess = element => {
    const inputControl = element.parentElement;
    const errorDisplay = inputControl.querySelector('.error');

    errorDisplay.innerText = '';
    inputControl.classList.add('success');
    inputControl.classList.remove('error');
};

const isValidEmail = email => {
    const regexMail = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return regexMail.test(String(email).toLowerCase());
};

const regexName = /^[A-ZÁČĎÉĚÍŇÓŘŠŤÚŮÝŽáčďéěíňóřšťúůýž][a-záčďéěíňóřšťúůýž]+$/i;

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

const validateFirstName = () => {
    const firstNameValue = firstName.value.trim();

    if (firstNameValue === '') {
        setError(firstName, 'Zadejte prosím jméno!');
    } else if (!regexName.test(firstNameValue)) {
        setError(firstName, 'Jméno musí začínat velkým písmenem a ostatní písmena musí být malá!');
    } else {
        setSuccess(firstName);
    }
};

const validateLastName = () => {
    const lastNameValue = lastName.value.trim();

    if (lastNameValue === '') {
        setError(lastName, 'Zadejte prosím příjmení!');
    } else if (!regexName.test(lastNameValue)) {
        setError(lastName, 'Příjmení musí začínat velkým písmenem a ostatní písmena musí být malá!');
    } else {
        setSuccess(lastName);
    }
};

const validateEmail = () => {
    const emailValue = email.value.trim();

    if (emailValue === '') {
        setError(email, 'Zadejte prosím email!');
    } else if (!isValidEmail(emailValue)) {
        setError(email, 'Zadejte prosím správnou emailovou adresu!');
    } else {
        setSuccess(email);
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

const validatePassword2 = () => {
    const password2Value = password2.value.trim();
    const passwordValue = password.value.trim();

    if (password2Value === '') {
        setError(password2, 'Prosím potvrďte heslo!');
    } else if (password2Value !== passwordValue) {
        setError(password2, 'Hesla se neshodují!');
    } else {
        setSuccess(password2);
    }
};
