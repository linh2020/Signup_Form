const username_listen = document.querySelector('#username');
const email_listen = document.querySelector('#email');
const password_listen = document.querySelector('#password');
const form = document.querySelector('#signup');

const valid_username = () => {
    let valid = false;

    const username = username_listen.value.trim();

    if (username == '') {
        sendError(username_listen, 'Username cannot be blank.');
    } else if (username.length < 5 || username.length > 20) {
        sendError(username_listen, 'Username must be between 5 and 20 characters.')
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
    } else if (!(/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*])(?=.{6,})/.test(password))) {
        sendError(password_listen, 'Passwords require 1 each of a-z, A-Z, 0-9, and !@#$%^&*');
    } else {
        sendCorrect(password_listen);
        valid = true;
    }

    return valid;
};

const valid_email = () => {
    let valid = false;
    const email = email_listen.value.trim();
    if (email == '') {
        sendError(email_listen, 'Email cannot be blank.');
    } else if (!(/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(email))) {
        sendError(email_listen, 'Email is not valid.')
    } else {
        sendCorrect(email_listen);
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

form.addEventListener('submit', function (e) {
    // e.preventDefault();
    let isUsernameValid = valid_username(),
        isEmailValid = valid_email(),
        isPasswordValid = valid_password();

    if (!(isUsernameValid && isPasswordValid && isEmailValid)) {
        e.preventDefault();
    } else {
        form.submit();
    }
});