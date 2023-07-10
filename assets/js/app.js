// Rechercher l'élément HTML qui est une checkbox pour basculer entre les thèmes.
const toggleSwitch = document.querySelector('.theme input[type="checkbox"]');

// Rechercher si le thème a été préalablement stocké dans le localStorage, sinon utiliser 'null'.
const currentTheme = localStorage.getItem("theme") ? localStorage.getItem("theme") : null;

// Fonction pour basculer le thème.
function switchTheme(e) {
    if (e.target.checked) { // Si le bouton est coché
        // Définir un attribut sur l'élément racine (html) pour indiquer que le thème sombre est activé.
        document.documentElement.setAttribute("dark-theme", "dark");
        // Stocker la préférence de thème dans le localStorage.
        localStorage.setItem("theme", "dark");
    } else { // Si le bouton n'est pas coché
        // Définir un attribut sur l'élément racine (html) pour indiquer que le thème clair est activé.
        document.documentElement.setAttribute("dark-theme", "light");
        // Stocker la préférence de thème dans le localStorage.
        localStorage.setItem("theme", "light");
    }
}

// Si un thème a été stocké précédemment,
if (currentTheme) {
    // Définir le thème actuel sur la base de ce qui a été stocké dans le localStorage.
    document.documentElement.setAttribute("dark-theme", currentTheme);
    // Si le thème stocké est 'dark', cocher le bouton.
    if (currentTheme === "dark") {
        toggleSwitch.checked = true;
    }
}

// Ajouter un écouteur d'événement sur le bouton pour basculer le thème lorsqu'il est modifié.
toggleSwitch.addEventListener("change", switchTheme);


const modalAjouter = document.getElementById("myModal-un");
const btnsAjouter = Array.from(document.getElementsByClassName("ajouter_event"));
const closeAjouter = document.getElementsByClassName("close")[0];
const event_date = document.getElementById("event_date");

btnsAjouter.forEach(function(btn) {
    btn.onclick = function() {
        event_date.value = this.dataset.date;
        modalAjouter.style.display = "block";
        refreshTimeOptions();
    }
});

closeAjouter.onclick = function() {
    modalAjouter.style.display = "none";
}

window.onclick = function(event) {
    if (event.target == modalAjouter) {
        modalAjouter.style.display = "none";
    }
}

const modalRejoindre = document.getElementById("myModal-deux");
const btnsRejoindre = Array.from(document.getElementsByClassName("join_event"));
const closeRejoindre = Array.from(document.getElementsByClassName("close"));

btnsRejoindre.forEach(function(btn, index) {
    btn.onclick = function() {
        const event_date = document.getElementById("event_date");
        const event_description = document.getElementById("event_description");
        const event_organizer = document.getElementById("event_organizer");
        const event_time = document.getElementById("event_time");

        event_description.textContent = "Description: " + this.dataset.description;
        event_organizer.textContent = "Organisateur: " + this.dataset.organizer;
        event_time.textContent = "Heure: " + this.dataset.time;

        modalRejoindre.style.display = "block";
    }
});

closeRejoindre.forEach((btn) => {
    btn.onclick = function() {
        modalRejoindre.style.display = "none";
    }
});

window.onclick = function(event) {
    if (event.target == modalRejoindre) {
        modalRejoindre.style.display = "none";
    }
}

const modal = document.getElementById("myModal-trois");
const span = document.getElementsByClassName("close")[0];
const eventEls = document.querySelectorAll('.edit_event');

eventEls.forEach((el) => {
    el.addEventListener('click', (e) => {
        e.preventDefault();
        const eventId = el.getAttribute('data-id');
        document.querySelector('#updateEventForm input[name="event_id"]').value = eventId;
        document.querySelector('#deleteEventForm input[name="event_id"]').value = eventId;

        const eventDate = document.getElementById('event_date');
        const eventDescription = document.getElementById('event_description');
        const eventOrganizer = document.getElementById('event_organizer');
        const eventTime = document.getElementById('event_time');
        const updateDescription = document.getElementById('update_description');
        const updateEventForm = document.getElementById('updateEventForm');
        const deleteEventForm = document.getElementById('deleteEventForm');

        eventOrganizer.textContent = "Organisateur: " + el.dataset.organizer;
        eventTime.textContent = "Heure: " + el.dataset.time;
        updateDescription.value = el.dataset.description;

        updateEventForm.action += '?event_id=' + el.dataset.id;
        deleteEventForm.action += '?event_id=' + el.dataset.id;

        modal.style.display = "block";
    });
});

span.onclick = function() {
    modal.style.display = "none";
}

window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}

function confirmDelete(event) {
    event.preventDefault();
    let confirmation = confirm("Êtes-vous sûr de vouloir supprimer cet événement ?");
    if (confirmation) {
        document.getElementById("deleteEventForm").submit();
    }
}
function confirmCreate(event) {
    event.preventDefault();
    let confirmation = confirm("Êtes-vous sûr de vouloir créer cet événement ? Après la creation de cet évenement, vous pourrez changer la description mais vousl'heure.");
    if (confirmation) {
        // Soumettre le formulaire d'ajout d'événement
        document.getElementById("addEventForm").submit();
    }
}

function confirmUpdate(event) {
    event.preventDefault();
    let confirmation = confirm("Êtes-vous sûr de vouloir mettre à jour cet événement ?");
    if (confirmation) {
        // Soumettre le formulaire de mise à jour d'événement
        document.getElementById("updateEventForm").submit();
    }
}


document.addEventListener('DOMContentLoaded', (event) => {
    document.getElementById('event_date').addEventListener('change', refreshTimeOptions);
});

function refreshTimeOptions() {
    const selectElement = document.getElementById('heure');

    // Vider les options actuelles
    selectElement.innerHTML = "";

    let eventDate = event_date.value;
    let currentDateTime = new Date();
    let currentDate = currentDateTime.toISOString().substring(0,10);

    // Heure actuelle
    let currentHour = currentDateTime.getHours();

    if (eventDate === currentDate) {
        // Remplir le menu déroulant avec des heures futures uniquement
        for(let i = currentHour + 1; i < 24; i++) {
            let option = document.createElement('option');
            option.value = pad(i) + ':00';
            option.text = pad(i) + ':00';
            selectElement.add(option);
        }
    } else if(new Date(eventDate) > currentDateTime) {
        // Remplir le menu déroulant avec toutes les heures de la journée si la date de l'événement est après la date actuelle
        for(let i = 0; i < 24; i++) {
            let option = document.createElement('option');
            option.value = pad(i) + ':00';
            option.text = pad(i) + ':00"';
            selectElement.add(option);
        }
    }
}


// Ajouter un zéro de préfixe si le nombre est inférieur à 10
function pad(n) {
    return (n < 10) ? ("0" + n) : n;
}