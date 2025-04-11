<?php
// Traitement.php - Traitement du formulaire de note de frais
      // Traitement des adhérents
    $adherents = [];
      if (isset($_POST['adherent']) && is_array($_POST['adherent'])) {
          foreach ($_POST['adherent'] as $adherent) {
              if (!empty($adherent)) {
                  $adherents[] = ($adherent);
              }
          }
      }
    // Traitement des lignes de frais
    $lignes_frais = [];
    
    if (isset($_POST['date']) && is_array($_POST['date'])) {
        $count = count($_POST['date']);
        
        for ($i = 0; $i < $count; $i++) {
            if (!empty($_POST['date'][$i]) && !empty($_POST['motif'][$i]) && !empty($_POST['trajet'][$i])) {
                $ligne = [
                    'date' => ($_POST['date'][$i]),
                    'motif' => ($_POST['motif'][$i]),
                    'trajet' => ($_POST['trajet'][$i]),
                    'km' => floatval($_POST['km'][$i]),
                    'cout_trajet' => floatval($_POST['cout_trajet'][$i]),
                    'peages' => floatval($_POST['peages'][$i]),
                    'repas' => floatval($_POST['repas'][$i]),
                    'hebergement' => floatval($_POST['hebergement'][$i]),
                    'total' => floatval($_POST['total'][$i])
                ];
                
                $lignes_frais[] = $ligne;
            }
        }
    }
    
    // Récupération des totaux
    $total_general = floatval($_POST['total_general']);
    $total_dons = floatval($_POST['total_dons']);
    
    // Partie réservée à l'association (peut être vide)
    $num_recu = isset($_POST['num_recu']) ? ($_POST['num_recu']) : '';
    $date_remise = isset($_POST['date_remise']) ? ($_POST['date_remise']) : '';
    
    // Rediriger vers la page de confirmation
    header("Location: frais-benevolat-confirmation.php");
    exit();

?>
