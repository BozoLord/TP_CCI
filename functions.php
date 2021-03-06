<?php
require_once('config.php');
// Upload du fichier Csv en base de donnée.
if(isset($_POST['form']) && $_POST['form'] == "uploadCsv"){
    if (isset($_FILES['fichier']) && $_FILES['fichier']['error'] == 0){
        $upload = basename($_FILES['fichier']['name']);
        $PDO->exec("TRUNCATE TABLE Villes");
        $PDO->exec("TRUNCATE TABLE Produits");
        $PDO->exec("TRUNCATE TABLE Magasins");
        $PDO->exec("TRUNCATE TABLE Produit_Magasin");


        $csvFile = $_FILES['fichier'];
        $data = [];

        $produits = [];
        $prix = [];
        $villes = [];
        $enseignes = [];

        $tmpName = $_FILES['fichier']['tmp_name'];
        $csvAsArray = array_map('str_getcsv', file($tmpName));
        // Récupération des lignes 
        foreach($csvAsArray as $line){
            //ligne = ['Patisseries', 'Prix', 'Artisan', 'Ville']
            $data[] = $line;
        }

        // loop dans les lignes pour les placer dans leur catégorie correspondante
        for($i = 0; $i<count($data); $i++){
            if ($i == 0 || $i == 4){
                continue;
            }

            if (!in_array($data[$i][0], $produits)){
                $produits[] = $data[$i][0];
            } 
            if (!in_array($data[$i][1], $prix)){
                $prix[] = $data[$i][1];
            }

            $enseigne = new stdClass();
            $enseigne->Nom = $data[$i][2];
            $enseigne->Ville = $data[$i][3];
            $enseigne->Adresse = $data[$i][4];
            $enseigne->Produits = [];
            if (!in_array($enseigne, $enseignes)){
                $enseignes[] = $enseigne;
            } else {
            }

            $ville = new stdClass();
            $ville->Nom = $data[$i][3];
            $ville->ID_Ville = -1;
            if (!in_array($ville, $villes)){
                $villes[] = $ville;
            }


        }
        // Insert des produits et villes car ils ne dépendent d'aucune table

        // Insert des produits
        $queryItems = "";
        for ($o = 0; $o < count($produits); $o++){
            if ($o == count($produits) -1){
                $queryItems .= "('" . $produits[$o] . "')";
            } else {
                $queryItems .= "('" . $produits[$o] . "'), ";
            }
        }
        $PDO->exec('INSERT INTO Produits (Nom) VALUES ' .$queryItems);

        // Insert des villes 
        $queryItems = "";
        for ($o = 0; $o < count($villes); $o++){
            if ($o == count($villes) -1){
                $queryItems .= "('" . $villes[$o]->Nom . "')";
            } else {
                $queryItems .= "('" . $villes[$o]->Nom . "'), ";
            }
        }
        $PDO->exec('INSERT INTO Villes (Nom) VALUES ' .$queryItems);
        // Récupération des villes pour les comparer et appliquer les clés étrangère à la table magasins

        $req = $PDO->prepare('SELECT * FROM Villes');
        if($req->execute()){
            $villes = $req->fetchAll();
            if (count($villes) > 0){
                for($x =0; $x < count($enseignes); $x++){
                    for($s = 0; $s < count($villes); $s++){
                        if($enseignes[$x]->Ville == $villes[$s]->Nom){
                            $enseignes[$x]->FK_ID_Ville = $villes[$s]->ID_Ville;
                            continue;
                        }
                    }
                }

                // Push du tableau Magasin dans la base avec les ID correspondants 

                $queryItems = "";
                for ($o = 0; $o < count($enseignes); $o++){
                    if ($o == count($enseignes) -1){
                        $queryItems .= '("' . $enseignes[$o]->Adresse . '","' . $enseignes[$o]->Nom . '",' . $enseignes[$o]->FK_ID_Ville . ')';
                    } else {
                        $queryItems .= '("' . $enseignes[$o]->Adresse . '","' . $enseignes[$o]->Nom . '",' . $enseignes[$o]->FK_ID_Ville . '),';
                    }
                }
                $PDO->exec('INSERT INTO Magasins (Adresse, Enseigne, FK_ID_Ville) VALUES ' .$queryItems);
            }
        } else {
            echo "Impossible de récupérer les villes";
        }   
        // Récupération des Magasins afin de comparer leur identitée et ajouter la table de liaison produit_magasin

        $req = $PDO->prepare('SELECT * FROM Magasins');
        if($req->execute()){
            $magasins = $req->fetchAll();
            if (count($magasins) > 0){
                // Récupération des produits pour comparer Nom du produit avec nom du produit sur la ligne. 
                $req = $PDO->prepare('SELECT * FROM Produits');
                if($req->execute()){
                    $produits = $req->fetchAll();
                    if (count($produits) > 0){
                        $produits_enseignes = [];
                        for ($i = 0; $i < count($magasins); $i++){
                            for($x = count($data) - 1; $x > 0; $x--){
                                if ($x == 0){
                                    continue;
                                }
                                // Si le nom du magasin récupéré en base est égale au nom du magasin sur la ligne, et que l'adresse est égale aussi
                                if ($magasins[$i]->Enseigne == $data[$x][2] && $magasins[$i]->Adresse == $data[$x][4]){
                                    $ens_prdt = new stdClass();

                                    // Formatage du prix : Retrait du symbole et remplace virgule par point.
                                    $formatedPrice = str_replace(array('€', '$'), '', $data[$x][1]);
                                    $formatedPrice = str_replace(array(','), '.', $formatedPrice);
                                    $ens_prdt->Prix = $formatedPrice;
                                    $ens_prdt->FK_ID_Magasin = $magasins[$i]->ID_Magasin;
                                    // Boucle dans les produits récupérés l'ID du produit en question
                                    for($e = 0; $e<count($produits); $e++){
                                        if ($produits[$e]->Nom == $data[$x][0]){
                                            $ens_prdt->FK_ID_Produit = $produits[$e]->ID_Produit;
                                            break;
                                        }
                                    }
                                    // Ajout du combo "Produit (Prix, ID) > Enseigne (ID)
                                    $produits_enseignes[] = $ens_prdt;

                                }
                            }
                        }
                        // Inserts des Combo Produit > Enseigne en base dans la table de liaison Produit_Magasin;
                        if (count($produits_enseignes) > 0){
                            $queryItems = "";
                            for ($o = 0; $o < count($produits_enseignes); $o++){
                                if ($o == count($produits_enseignes) -1){
                                    $queryItems .= '(' . $produits_enseignes[$o]->Prix . ',' . $produits_enseignes[$o]->FK_ID_Magasin . ',' . $produits_enseignes[$o]->FK_ID_Produit . ')';
                                } else {
                                    $queryItems .= '(' . $produits_enseignes[$o]->Prix . ',' . $produits_enseignes[$o]->FK_ID_Magasin . ',' . $produits_enseignes[$o]->FK_ID_Produit . '),';
                                }
                            }
                            $PDO->exec('INSERT INTO Produit_Magasin (Prix, FK_ID_Magasin, FK_ID_Produit) VALUES ' .$queryItems);
                            echo "Done sucess";
                        }
                    }
                }
            }
        }
    }
}
// Recherche
else if (isset($_POST['form']) && $_POST['form'] == "deepSearch"){
    if (strlen($_POST['input']) <= 2){
        return;
    } else {
        switch($_POST['type']){
            case "Magasins":
                $req = $PDO->prepare('SELECT Magasins.ID_Magasin, Magasins.Enseigne, Magasins.Adresse, Villes.Nom FROM Magasins JOIN Villes ON Magasins.FK_ID_Ville = Villes.ID_Ville WHERE Magasins.Enseigne LIKE  CONCAT("%",:enseigne,"%")');
                $req->bindValue(':enseigne', $_POST['input']);
                if($req->execute()){
                    $resultat = $req->fetchAll();
                    if (count($resultat) > 0){
                        echo json_encode($resultat);
                    } 
                } 
                break;
            case "Villes":
                $req = $PDO->prepare('SELECT * FROM Villes JOIN Magasins On Villes.ID_Ville = Magasins.FK_ID_Ville WHERE Villes.Nom LIKE CONCAT("%",:ville,"%")');
                $req->bindValue(':ville', $_POST['input']);
                if($req->execute()){
                    $resultat = $req->fetchAll();
                    if (count($resultat) > 0){
                        echo json_encode($resultat);
                    } 
                } 
                break;
            case "Produits":
                $req = $PDO->prepare('SELECT Produits.ID_Produit, Produits.Nom FROM Produits 
                WHERE Produits.Nom LIKE CONCAT("%",:produit,"%")');
                $req->bindValue(':produit', $_POST['input']);
                if($req->execute()){
                    $produits = $req->fetchAll();
                    if (count($produits) > 0){
                        for ($x = 0; $x < count($produits); $x++){
                            $req = $PDO->prepare('SELECT min(Prix) as minPrix, max(Prix) as maxPrix FROM Produit_Magasin 
                                        WHERE Produit_Magasin.FK_ID_Produit = :idProduit');
                            $req->bindValue(':idProduit', $produits[$x]->ID_Produit);
                            if($req->execute()){
                                $prix = $req->fetch();
                                if(count($prix) > 0){
                                    $produits[$x]->prixMin =  strval($prix->minPrix)."€";
                                    $produits[$x]->prixMax =  strval($prix->maxPrix)."€";
                                }
                            }
                        }
                    }
                    echo json_encode($produits);
                    break;
                }
        }
    }
}
// Affichage des détails suite à une recherche
else if (isset($_POST['form']) && $_POST['form'] == "getDetailsFromDeepSearch"){
    switch($_POST['type']){
            // Quand j'arrive après une clique suite à une recherche 'Magasin' ou 'Ville' j'affiche les produits proposés par le dit magasin
        case "Magasins":
        case "Villes":
            $req = $PDO->prepare('SELECT Magasins.Enseigne, Magasins.Enseigne, Magasins.Adresse, Villes.Nom as villeNom, Produit_Magasin.Prix, Produits.Nom, Produits.ID_Produit FROM Magasins JOIN Villes ON Villes.ID_Ville = Magasins.FK_ID_Ville JOIN Produit_Magasin ON Produit_Magasin.FK_ID_Magasin = Magasins.ID_Magasin JOIN Produits ON Produits.ID_Produit = Produit_Magasin.FK_ID_Produit WHERE Magasins.ID_Magasin = :idMag');
            $req->bindValue(':idMag', $_POST['targetID']);
            if($req->execute()){
                $resultat = $req->fetchAll();
                if (count($resultat) > 0){
                    for ($x = 0; $x < count($resultat); $x++){
                        $resultat[$x]->Prix = strval($resultat[$x]->Prix)."€";
                    }
                    echo json_encode($resultat);
                } 
            } 
            break;
        case "Produits":
            $req = $PDO->prepare('SELECT Produit_Magasin.Prix, Produit_Magasin.FK_ID_Magasin, Magasins.Adresse, Magasins.Enseigne, Villes.Nom, Villes.ID_Ville, Produits.Nom as prdtNom FROM Produit_Magasin JOIN Magasins ON Magasins.ID_Magasin = Produit_Magasin.FK_ID_Magasin JOIN Villes On Villes.ID_Ville = Magasins.FK_ID_Ville JOIN Produits ON Produits.ID_Produit = Produit_Magasin.FK_ID_Produit WHERE Produit_Magasin.FK_ID_Produit = :produit');
            $req->bindValue(':produit', $_POST['targetID']);
            if($req->execute()){
                $resultat = $req->fetchAll();
                if (count($resultat) > 0){
                    for ($x = 0; $x < count($resultat); $x++){
                        $resultat[$x]->Prix = strval($resultat[$x]->Prix)."€";
                    }
                    echo json_encode($resultat);
                } 
            }
            break;
    }
}
// Au Chargement de la page je fais un GET des produits et des informations associés
else {
    // Récupération des produits
    $req = $PDO->prepare('SELECT * FROM Produits');
    if($req->execute()){
        $produitsBDD = $req->fetchAll();
        if (count($produitsBDD) > 0){
            $produits = [];
            for($x = 0; $x<count($produitsBDD); $x++){
                $id = $produitsBDD[$x]->ID_Produit;
                // Récupération du prix le moins élevé
                $req = $PDO->prepare('SELECT Magasins.Adresse, Magasins.Enseigne, Magasins.ID_Magasin, Villes.Nom, Produit_Magasin.Prix FROM Magasins INNER JOIN Villes ON Magasins.FK_ID_Ville = Villes.ID_Ville INNER JOIN Produit_Magasin on Magasins.ID_Magasin = Produit_Magasin.FK_ID_Magasin WHERE ID_magasin = ( SELECT FK_ID_Magasin FROM Produit_Magasin WHERE FK_ID_Produit = :id ORDER BY prix ASC LIMIT 1 ) AND FK_ID_Produit = :id');
                $req->bindValue(':id', $id);
                $req->execute();
                $res = $req->fetch();
                $produit = new stdClass();
                $produit->Nom = $produitsBDD[$x]->Nom;
                $produit->ID_Produit = $produitsBDD[$x]->ID_Produit;
                // Traitement du prix min
                $produit->PrixMin = strval($res->Prix)."€";
                $infosPrixMin = new stdClass();
                $infosPrixMin->Adresse = $res->Adresse;
                $infosPrixMin->Enseigne = $res->Enseigne;
                $infosPrixMin->Ville = $res->Nom;
                $infosPrixMin->ID_Artisan = $res->ID_Magasin;
                $produit->InfosPrixMin = $infosPrixMin;
                // Récupération du prix le plus élevé
                $req = $PDO->prepare('SELECT Magasins.Adresse, Magasins.Enseigne, Magasins.ID_Magasin, Villes.Nom, Produit_Magasin.Prix FROM Magasins INNER JOIN Villes ON Magasins.FK_ID_Ville = Villes.ID_Ville INNER JOIN Produit_Magasin on Magasins.ID_Magasin = Produit_Magasin.FK_ID_Magasin WHERE ID_magasin = ( SELECT FK_ID_Magasin FROM Produit_Magasin WHERE FK_ID_Produit = :id ORDER BY prix DESC LIMIT 1 ) AND FK_ID_Produit = :id');
                $req->bindValue(':id', $id);
                $req->execute();
                $res = $req->fetch();
                // Traitement du prix max
                $produit->PrixMax = strval($res->Prix)."€";
                $produit->Diff = strval($res->Prix - $produit->PrixMin) . "€";
                $infosPrixMax = new stdClass();
                $infosPrixMax->Adresse = $res->Adresse;
                $infosPrixMax->Enseigne = $res->Enseigne;
                $infosPrixMax->Ville = $res->Nom;
                $infosPrixMax->ID_Artisan = $res->ID_Magasin;
                $produit->InfosPrixMax = $infosPrixMax;

                $produits[] = $produit;
            }
            echo json_encode($produits);
        } 
    } 
}
?>
