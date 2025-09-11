const filter = document.getElementById("filter");
const btn = document.getElementById("btn");

btn?.addEventListener("click", (event) => {
    event.preventDefault();
    filter?.classList.remove("hidden");
});

const btnpassager = document.getElementById("btnpassager");

btnpassager?.addEventListener("click", (event) => {
    filter?.classList.add("hidden");
});

const filters = document.getElementById("filters");
const btns = document.getElementById("btns");

btns?.addEventListener("click", (event) => {
    event.preventDefault();
    filters?.classList.toggle("hidden");
});


