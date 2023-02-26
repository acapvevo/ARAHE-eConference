const countryList = document.getElementById('countryList');
const countryInputField = document.getElementById('address.country');
const countries = Country.getAllCountries();

countries.forEach(function (country) {
    var option = document.createElement('option');
    option.value = country.name;
    countryList.appendChild(option);
});

const stateFieldInput = document.getElementById('state');
const stateList = document.getElementById('stateList');
const states = State.getAllStates();

states.forEach(function (state) {
    var option = document.createElement('option');
    option.value = state.name;
    stateList.appendChild(option);
});

$.ajax({
    "url": "http://universities.hipolabs.com/search?name=",
    "method": "GET",
}).done(function (response) {
    const universityDatalist = document.getElementById('universityList');

    response.forEach(function (university) {
        var option = document.createElement('option');
        option.value = university.name;
        universityDatalist.appendChild(option);
    });
});


const phoneInputField = document.getElementById("contact.phoneNumber");
const faxInputField = document.getElementById("contact.faxNumber");

const phoneInput = window.intlTelInput(phoneInputField, {
    initialCountry: "my",
    preferredCountries: ["my"],
    utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
});
const faxInput = window.intlTelInput(faxInputField, {
    initialCountry: "my",
    preferredCountries: ["my"],
    utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
});

const phoneError = document.querySelector("#alert-error-phoneNumber");
const faxError = document.querySelector("#alert-error-faxNumber");

function process(event) {
    event.preventDefault();

    const phoneNumber = phoneInput.getNumber();
    const faxNumber = faxInput.getNumber();

    phoneError.style.display = "none";
    faxError.style.display = "none";

    phoneInputField.classList.remove("is-invalid");
    faxInputField.classList.remove("is-invalid");

    console.log(!faxNumber);

    if (phoneNumber && !phoneInput.isValidNumber()) {
        phoneError.style.display = "block";
        phoneError.innerHTML = `Invalid phone number.`;
        phoneInputField.classList.add("is-invalid");
    } else if (faxNumber && !faxInput.isValidNumber()) {
        faxError.style.display = "block";
        faxError.innerHTML = `Invalid fax number.`;
        faxInputField.classList.add("is-invalid");
    } else {
        phoneInputField.value = phoneNumber;
        faxInputField.value = faxNumber;

        document.getElementById("myForm").submit();
    }
}