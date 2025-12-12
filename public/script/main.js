// ---------------- FORMULAIRES ----------------

// Formulaire Profil voiture
const filter = document.getElementById("filter");
const btn = document.getElementById("btn");
btn?.addEventListener("click", (event) => {
    event.preventDefault();
    filter?.classList.toggle("hidden");
});

// Formulaire critères de recherche
const filters = document.getElementById("filters");
const btns = document.getElementById("btns");
btns?.addEventListener("click", (event) => {
    event.preventDefault();
    filters?.classList.toggle("hidden");
});

// Boutons démarrer/arrivée
document.querySelectorAll('.start').forEach(startBtn => {
    startBtn.addEventListener('click', () => {
        const finishBtn = startBtn.closest('.startstop').querySelector('.finish');
        if (finishBtn) finishBtn.classList.toggle('hidden');
    });
});

// Photo de profil
const profil = document.getElementById('profil');
const preview = document.getElementById('preview');
if (profil) {
    profil.addEventListener('change', function handleFiles() {
        const file = this.files[0];
        if (file) {
            preview.src = URL.createObjectURL(file);
            preview.style.display = 'block';
            preview.onload = () => URL.revokeObjectURL(preview.src);
        }
    }, false);
}

// Boutons “arrived”
document.querySelectorAll('.arrived').forEach(button => {
    button.addEventListener('click', (event) => {
        const url = event.currentTarget.dataset.url;
        window.location.href = url;
    });
});



// ---------------- GRAPH CHART ----------------
window.addEventListener('DOMContentLoaded', () => {

    let weeklyTrajetsChart;
    let weeklyCreditsChart;

    async function fetchAndRenderCharts() {
        try {
            // Trajets
            const respTrajets = await fetch('/admin/week');
            const dataTrajets = await respTrajets.json();

            const ctxTrajets = document.getElementById('myChartsTrajets');
            const trajetsData = {
                labels: ['Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi','Dimanche'],
                datasets: [{
                    label: 'Nombre de trajets par jour',
                    data: dataTrajets.weekTrajets,
                    backgroundColor: 'rgba(54, 162, 235, 0.7)',
                    borderColor: 'rgb(54, 162, 235)',
                    borderWidth: 1
                }]
            };

            if (!weeklyTrajetsChart) {
                weeklyTrajetsChart = new Chart(ctxTrajets, { type: 'bar', data: trajetsData });
            } else {
                weeklyTrajetsChart.data.datasets[0].data = dataTrajets.weekTrajets;
                weeklyTrajetsChart.update();
            }

            // Crédits
            const respCredits = await fetch('/admin/credit');
            const dataCredits = await respCredits.json();

            const ctxCredits = document.getElementById('myChartsCredits');
            const creditsData = {
                labels: ['Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi','Dimanche'],
                datasets: [{
                    label: 'Nombre de crédits par jour',
                    data: dataCredits.weekCredits,
                    backgroundColor: 'rgba(255, 159, 64, 0.7)',
                    borderColor: 'rgb(255, 159, 64)',
                    borderWidth: 1
                }]
            };

            if (!weeklyCreditsChart) {
                weeklyCreditsChart = new Chart(ctxCredits, { type: 'bar', data: creditsData });
            } else {
                weeklyCreditsChart.data.datasets[0].data = dataCredits.weekCredits;
                weeklyCreditsChart.update();
            }

        } catch (error) {
            console.error(error);
        }
    }

    fetchAndRenderCharts();
    setInterval(fetchAndRenderCharts, 3600000); // maj toutes les heures
});

