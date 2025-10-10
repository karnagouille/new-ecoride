

//Formulaire Profil voiture

const filter = document.getElementById("filter");
const btn = document.getElementById("btn");

btn?.addEventListener("click", (event) => {
    event.preventDefault();
    filter?.classList.remove("hidden");
});


//Formulaire critéres de recherche

const filters = document.getElementById("filters");
const btns = document.getElementById("btns");

btns?.addEventListener("click", (event) => {
    event.preventDefault();
    filters?.classList.toggle("hidden");
});



// Boutton démarrer/arrivée
document.querySelectorAll('.start').forEach(startBtn => {
    startBtn.addEventListener('click', () => {

        const finishBtn = startBtn.closest('.startstop').querySelector('.finish');
        if (finishBtn) {
            finishBtn.classList.toggle('hidden');
        }
    });
});


// Photo de profil

    const profil = document.getElementById('profil');
const preview = document.getElementById('preview');

if (profil) {
    profil.addEventListener('change', handleFiles, false);
}

function handleFiles() {
    const file = this.files[0];
    if (file) {
        preview.src = URL.createObjectURL(file);
        preview.style.display = 'block';
        preview.onload = () => URL.revokeObjectURL(preview.src);
    }
}


    document.querySelectorAll('.arrived').forEach(button => {
    button.addEventListener('click', (event) => {
        const url = event.currentTarget.dataset.url;
        window.location.href = url;
    });
});



