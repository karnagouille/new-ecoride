

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






window.addEventListener('DOMContentLoaded', () => {
    // ----- Graphique 1 : Aujourd'hui -----
    fetch('/admin/chart')
        .then(response => response.json())
        .then(data => {
            const labels1 = ['Today'];
            const data1 = {
                labels: labels1,
                datasets: [{
                    label: 'Nombre de covoiturage par jour',
                    data: [data.today],
                    backgroundColor: ['rgba(54, 162, 235,1)'],
                    borderColor: ['rgb(54, 162, 235)'],
                    borderWidth: 1
                }]
            };

            const config1 = {
                type: 'bar',
                data: data1,
                options: {
                    scales: {
                        x: { ticks: { color: '#fcf6f6ff', font: { size: 14, weight: 'bold' } }, grid: { color: '#ddd' } },
                        y: { ticks: { color: '#f7f4f4ff', font: { size: 14 } }, grid: { color: '#ddd' } }
                    },
                    plugins: { legend: { labels: { color: '#f0ededff' } } }
                }
            };

            const ctx1 = document.getElementById('myChart');
            if (ctx1) new Chart(ctx1, config1);
        });

    // ----- Graphique 2 : Par jour de la semaine -----
    const labels2 = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
    const data2 = {
        labels: labels2,
        datasets: [{
            label: 'Nombre de trajets terminés par jour',
            data: [65, 59, 80, 81, 56, 55, 40],
            backgroundColor: [
                'rgba(255, 99, 132,1)',
                'rgba(255, 159, 64,1)',
                'rgba(255, 205, 86,1)',
                'rgba(75, 192, 192, 1)',
                'rgba(54, 162, 235,1)',
                'rgba(153, 102, 255, 1)',
                'rgba(201, 203, 207, 1)'
            ],
            borderColor: [
                'rgb(255, 99, 132)',
                'rgb(255, 159, 64)',
                'rgb(255, 205, 86)',
                'rgb(75, 192, 192)',
                'rgb(54, 162, 235)',
                'rgb(153, 102, 255)',
                'rgb(201, 203, 207)'
            ],
            borderWidth: 1
        }]
    };

    const config2 = {
        type: 'bar',
        data: data2,
        options: {
            scales: {
                x: { ticks: { color: '#fcf6f6ff', font: { size: 14, weight: 'bold' } }, grid: { color: '#ddd' } },
                y: { ticks: { color: '#f7f4f4ff', font: { size: 14 } }, grid: { color: '#ddd' } }
            },
            plugins: { legend: { labels: { color: '#f0ededff' } } }
        }
    };

    const ctx2 = document.getElementById('myCharts');
    if (ctx2) new Chart(ctx2, config2);
});

