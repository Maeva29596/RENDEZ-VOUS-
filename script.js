function navigate(page) {
  alert("Navigation vers : " + page);
}

document.getElementById('searchBar').addEventListener('input', function (e) {
  const query = e.target.value.toLowerCase();
  console.log("Recherche : " + query);
  // Ici tu peux filtrer dynamiquement les hôpitaux ou services en AJAX ou autre
});
