const form = document.getElementById('form');
const firstName = document.getElementById('firstname');
const lastName = document.getElementById('lastname');
const email = document.getElementById('email');
const phone = document.getElementById('phone');

// form.addEventListener('submit', e => {
//     e.preventDefault();
// });
firstname.addEventListener('input', () => {
    validateFirstName();
});

lastname.addEventListener('input', () => {
    validateLastName();
});

email.addEventListener('input', () => {
    validateEmail();
});

phone.addEventListener('input', () => {
    validatePhone();
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
    const re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
};

const isValidPhone = phone => {
    // const rePhone = /^(?:\+420\s)?\d{3}\s\d{3}\s\d{3}$/;
    const rePhone = /^\+420\s\d{3}\s\d{3}\s\d{3}$/;
    return rePhone.test(String(phone));
};

const regexName = /^[A-ZČĎÉĚÍŇÓŘŠŤÚŮÝŽÁČĎÉĚÍŇÓŘŠŤÚŮÝŽ][a-zčďéěíňóřšťúůýžáčďéěíňóřšťúůýž]*$/;


const validateFirstName = () => {
    const firstNameValue = firstName.value.trim();

    if (firstNameValue === '') {
        setError(firstName, 'Zadejte prosím jméno');
    } else if (!regexName.test(firstNameValue)) {
        setError(firstName, 'Jméno musí začínat velkým písmenem a ostatní písmena musí být malá');
    } else {
        setSuccess(firstName);
    }
};

const validateLastName = () => {
    const lastNameValue = lastName.value.trim();

    if (lastNameValue === '') {
        setError(lastName, 'Zadejte prosím příjmení');
    } else if (!regexName.test(lastNameValue)) {
        setError(lastName, 'Příjmení musí začínat velkým písmenem a ostatní písmena musí být malá');
    } else {
        setSuccess(lastName);
    }
};

const validateEmail = () => {
    const emailValue = email.value.trim();

    if (emailValue === '') {
        setError(email, 'Zadejte prosím email');
    } else if (!isValidEmail(emailValue)) {
        setError(email, 'Zadejte prosím správnou emailovou adresu');
    } else {
        setSuccess(email);
    }
};

const validatePhone = () => {
    const phoneValue = phone.value.trim();

    if (phoneValue === '') {
        setError(phone, 'Zadejte prosím telefonní číslo');
    } else if (!isValidPhone(phoneValue)) {
        setError(phone, 'Zadejte prosím číslo ve správném formátu: +420 123 456 789');
    } else {
        setSuccess(phone);
    }
};