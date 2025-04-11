document.addEventListener('DOMContentLoaded', function() {
    // Initialisation
    const tarifKmInput = document.getElementById('tarif_km');
    const addRowButton = document.getElementById('addRow');
    const fraisTable = document.getElementById('fraisTable').getElementsByTagName('tbody')[0];
    const totalGeneralInput = document.getElementById('total_general');
    const totalDonsInput = document.getElementById('total_dons');
    const printBtn = document.getElementById('printBtn');
    const addAdherentBtn = document.querySelector('.add-adherent-btn');
    const adherentsContainer = document.querySelector('.adherents-container');
    
    // Définir l'année actuelle par défaut
    const currentYear = new Date().getFullYear();
    document.getElementById('annee').value = currentYear;
    
    // Fonction pour calculer le coût du trajet
    function calculateTrajetCost(km, tarif) {
        return (km * tarif).toFixed(2);
    }
    
    // Fonction pour calculer le total d'une ligne
    function calculateRowTotal(row) {
        const kmValue = parseFloat(row.querySelector('.km').value) || 0;
        const tarifKm = parseFloat(tarifKmInput.value) || 0;
        
        const coutTrajet = parseFloat(calculateTrajetCost(kmValue, tarifKm)) || 0;
        const peages = parseFloat(row.querySelector('.peages').value) || 0;
        const repas = parseFloat(row.querySelector('.repas').value) || 0;
        const hebergement = parseFloat(row.querySelector('.hebergement').value) || 0;
        
        const total = coutTrajet + peages + repas + hebergement;
        
        row.querySelector('.cout-trajet').value = coutTrajet.toFixed(2);
        row.querySelector('.total-ligne').value = total.toFixed(2);
        
        updateTotals();
    }
    
    // Fonction pour mettre à jour les totaux généraux
    function updateTotals() {
        let total = 0;
        const totalLines = document.querySelectorAll('.total-ligne');
        
        totalLines.forEach(function(input) {
            total += parseFloat(input.value) || 0;
        });
        
        totalGeneralInput.value = total.toFixed(2);
        totalDonsInput.value = total.toFixed(2);
    }
    
    // Fonction pour ajouter une nouvelle ligne
    function addNewRow() {
        const newRow = document.createElement('tr');
        newRow.className = 'frais-row';
        
        newRow.innerHTML = `
            <td><input type="date" name="date[]" required></td>
            <td><input type="text" name="motif[]" required></td>
            <td><input type="text" name="trajet[]" required></td>
            <td><input type="number" name="km[]" class="km" required min="0"></td>
            <td><input type="number" name="cout_trajet[]" class="cout-trajet" readonly step="0.01"></td>
            <td><input type="number" name="peages[]" class="peages" min="0" value="0" step="0.01"></td>
            <td><input type="number" name="repas[]" class="repas" min="0" value="0" step="0.01"></td>
            <td><input type="number" name="hebergement[]" class="hebergement" min="0" value="0" step="0.01"></td>
            <td><input type="number" name="total[]" class="total-ligne" readonly step="0.01"></td>
            <td><button type="button" class="delete-row">Supprimer</button></td>
        `;
        
        fraisTable.appendChild(newRow);
        
        // Ajouter les écouteurs d'événements à la nouvelle ligne
        attachRowEventListeners(newRow);
    }
    
    // Fonction pour attacher les écouteurs d'événements à une ligne
    function attachRowEventListeners(row) {
        const kmInput = row.querySelector('.km');
        const peagesInput = row.querySelector('.peages');
        const repasInput = row.querySelector('.repas');
        const hebergementInput = row.querySelector('.hebergement');
        const deleteBtn = row.querySelector('.delete-row');
        
        // Calcul automatique lorsque les valeurs changent
        kmInput.addEventListener('input', function() {
            calculateRowTotal(row);
        });
        
        peagesInput.addEventListener('input', function() {
            calculateRowTotal(row);
        });
        
        repasInput.addEventListener('input', function() {
            calculateRowTotal(row);
        });
        
        hebergementInput.addEventListener('input', function() {
            calculateRowTotal(row);
        });
        
        // Suppression d'une ligne
        deleteBtn.addEventListener('click', function() {
            // Ne pas supprimer s'il n'y a qu'une seule ligne
            if (fraisTable.querySelectorAll('.frais-row').length > 1) {
                row.remove();
                updateTotals();
            } else {
                alert("Vous ne pouvez pas supprimer la dernière ligne.");
            }
        });
    }
    
    // Fonction pour ajouter un nouvel adhérent
    function addNewAdherent() {
        const newAdherentDiv = document.createElement('div');
        newAdherentDiv.className = 'adherent-input';
        
        newAdherentDiv.innerHTML = `
            <input type="text" name="adherent[]" placeholder="Nom, prénom, licence n°">
            <button type="button" class="remove-adherent-btn">-</button>
        `;
        
        adherentsContainer.appendChild(newAdherentDiv);
        
        // Ajouter l'écouteur d'événement pour le bouton de suppression
        const removeBtn = newAdherentDiv.querySelector('.remove-adherent-btn');
        removeBtn.addEventListener('click', function() {
            newAdherentDiv.remove();
        });
    }
    
    // Écouteur d'événement pour le bouton "Ajouter une ligne"
    addRowButton.addEventListener('click', addNewRow);
    
    // Écouteur d'événement pour le bouton "Ajouter un adhérent"
    addAdherentBtn.addEventListener('click', addNewAdherent);
    
    // Écouteur d'événement pour le changement de tarif kilométrique
    tarifKmInput.addEventListener('input', function() {
        const rows = fraisTable.querySelectorAll('.frais-row');
        rows.forEach(function(row) {
            calculateRowTotal(row);
        });
    });
    
   
    // Attacher les écouteurs d'événements à la première ligne
    const firstRow = fraisTable.querySelector('.frais-row');
    if (firstRow) {
        attachRowEventListeners(firstRow);
    }
    
    // Ajouter un écouteur pour le formulaire afin d'empêcher l'envoi si certaines validations échouent
    document.getElementById('fraisForm').addEventListener('submit', function(event) {
        const rows = fraisTable.querySelectorAll('.frais-row');
        let isValid = true;
        
        rows.forEach(function(row) {
            const dateInput = row.querySelector('input[name="date[]"]');
            const motifInput = row.querySelector('input[name="motif[]"]');
            const trajetInput = row.querySelector('input[name="trajet[]"]');
            const kmInput = row.querySelector('input[name="km[]"]');
            
            if (!dateInput.value || !motifInput.value || !trajetInput.value || !kmInput.value) {
                isValid = false;
            }
        });
        
        if (!isValid) {
            alert('Veuillez remplir tous les champs obligatoires pour chaque ligne de frais.');
            event.preventDefault();
        }
    });
});
