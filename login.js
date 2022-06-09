const username_listen = document.querySelector('#username');
const email_listen = document.querySelector('#email');
const password_listen = document.querySelector('#password');
const login = document.querySelector('#login');

const valid_username = () => {
    let valid = false;

    const username = username_listen.value.trim();

    if (username == '') {
        sendError(username_listen, 'Username cannot be blank.');
    } else {
        sendCorrect(username_listen);
        valid = true;
    }
    return valid;
};

const valid_password = () => {
    let valid = false;

    const password = password_listen.value.trim();

    if (password == '') {
        sendError(password_listen, 'Password cannot be blank.');
    } else {
        sendCorrect(password_listen);
        valid = true;
    }

    return valid;
};

const sendError = (input, message) => {

    const formField = input.parentElement;
    formField.classList.remove('success');
    formField.classList.add('error');
    const error = formField.querySelector('small');
    error.textContent = message;
};

const sendCorrect = (input) => {
    const formField = input.parentElement;
    formField.classList.remove('error');
    formField.classList.add('success');
    const error = formField.querySelector('small');
    error.textContent = '';
};

login.addEventListener('submit', function (e) {
    // e.preventDefault();
    let isUsernameValid = valid_username(),
        isPasswordValid = valid_password();

    if (!(isUsernameValid && isPasswordValid)) {
        e.preventDefault();
    } else {
        login.submit();
    }
});