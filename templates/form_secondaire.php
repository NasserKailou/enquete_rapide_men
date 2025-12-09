<!-- SECTION 1: IDENTIFICATION DE L'ÉTABLISSEMENT -->
<div class="form-section active" data-section="1">
    <h2 class="section-title">
        <i class="fas fa-id-card"></i> I. Identification de l'Établissement
    </h2>
    
    <div class="form-grid">
        <div class="form-group col-2">
            <label for="nom_etablissement">Nom de l'établissement <span class="required">*</span></label>
            <input type="text" id="nom_etablissement" name="nom_etablissement" required class="form-control">
        </div>
        
        <div class="form-group">
            <label for="code_etablissement">Code <span class="required">*</span></label>
            <input type="text" id="code_etablissement" name="code_etablissement" required class="form-control" 
                   pattern="[A-Z0-9\-]+" placeholder="SEC-XXX-XXXXXX-XXXX">
            <small>Format: SEC-XXX-XXXXXX-XXXX (généré automatiquement si vide)</small>
        </div>
        
        <div class="form-group">
            <label for="date_creation">Date de création <span class="required">*</span></label>
            <input type="date" id="date_creation" name="date_creation" required class="form-control">
        </div>
        
        <div class="form-group">
            <label for="date_ouverture">Date d'ouverture <span class="required">*</span></label>
            <input type="date" id="date_ouverture" name="date_ouverture" required class="form-control">
        </div>
    </div>

    <h3 class="subsection-title">1.5 Localisation Administrative</h3>
    <div class="form-grid">
        <div class="form-group">
            <label for="region">Région <span class="required">*</span></label>
            <select id="region" name="region" required class="form-control" onchange="loadDepartements(this.value)">
                <option value="">-- Sélectionnez --</option>
                <?php foreach($regions_niger as $region): ?>
                    <option value="<?php echo $region; ?>"><?php echo $region; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="form-group">
            <label for="departement">Département <span class="required">*</span></label>
            <select id="departement" name="departement" required class="form-control" disabled>
                <option value="">-- Sélectionnez d'abord une région --</option>
            </select>
        </div>
        
        <div class="form-group">
            <label for="commune">Commune <span class="required">*</span></label>
            <input type="text" id="commune" name="commune" required class="form-control">
        </div>
        
        <div class="form-group">
            <label for="village_quartier">Village/Quartier <span class="required">*</span></label>
            <input type="text" id="village_quartier" name="village_quartier" required class="form-control">
        </div>
    </div>

    <h3 class="subsection-title">1.6 Localisation Scolaire</h3>
    <div class="form-grid">
        <div class="form-group">
            <label for="dren_apl">DREN/A/PL <span class="required">*</span></label>
            <input type="text" id="dren_apl" name="dren_apl" required class="form-control">
        </div>
        
        <div class="form-group">
            <label for="iden_apl">IDEN/A/PL <span class="required">*</span></label>
            <input type="text" id="iden_apl" name="iden_apl" required class="form-control">
        </div>
        
        <div class="form-group">
            <label for="iesg_iefa">IESG/IEFA <span class="required">*</span></label>
            <input type="text" id="iesg_iefa" name="iesg_iefa" required class="form-control">
        </div>
    </div>

    <h3 class="subsection-title">Caractéristiques</h3>
    <div class="form-grid">
        <div class="form-group">
            <label>1.7 Zone <span class="required">*</span></label>
            <div class="radio-group">
                <label class="radio-label">
                    <input type="radio" name="zone" value="Rurale" required> Rurale
                </label>
                <label class="radio-label">
                    <input type="radio" name="zone" value="Urbaine" required> Urbaine
                </label>
            </div>
        </div>
        
        <div class="form-group">
            <label>1.8 Cycle accueilli <span class="required">*</span></label>
            <div class="checkbox-group">
                <label class="checkbox-label">
                    <input type="checkbox" name="cycle_accueilli[]" value="CEG"> CEG
                </label>
                <label class="checkbox-label">
                    <input type="checkbox" name="cycle_accueilli[]" value="Lycée"> Lycée
                </label>
                <label class="checkbox-label">
                    <input type="checkbox" name="cycle_accueilli[]" value="Complexe"> Complexe
                </label>
            </div>
        </div>
        
        <div class="form-group">
            <label>1.9 Statut <span class="required">*</span></label>
            <div class="radio-group">
                <label class="radio-label">
                    <input type="radio" name="statut" value="Public" required> Public
                </label>
                <label class="radio-label">
                    <input type="radio" name="statut" value="Privé" required> Privé
                </label>
            </div>
        </div>
        
        <div class="form-group">
            <label>1.10 Type d'enseignement</label>
            <div class="checkbox-group">
                <label class="checkbox-label">
                    <input type="checkbox" name="type_enseignement[]" value="Traditionnel"> Traditionnel
                </label>
                <label class="checkbox-label">
                    <input type="checkbox" name="type_enseignement[]" value="Franco arabe"> Franco arabe
                </label>
            </div>
        </div>
    </div>

    <div class="form-grid">
        <div class="form-group col-2">
            <label>1.11 Est-ce que votre établissement a fonctionné l'année précédente ? <span class="required">*</span></label>
            <div class="radio-group">
                <label class="radio-label">
                    <input type="radio" name="fonction_annee_precedente" value="1" required> Oui
                </label>
                <label class="radio-label">
                    <input type="radio" name="fonction_annee_precedente" value="0" required> Non
                </label>
            </div>
        </div>
    </div>
</div>

<!-- SECTION 2: INFRASTRUCTURES -->
<div class="form-section" data-section="2">
    <h2 class="section-title">
        <i class="fas fa-building"></i> II. Infrastructures
    </h2>
    
    <h3 class="subsection-title">Salles de Classes</h3>
    <div class="form-grid">
        <div class="form-group">
            <label for="total_salles_sec">Nombre total de salles de classe <span class="required">*</span></label>
            <input type="number" id="total_salles_sec" name="total_salles" min="0" value="0" required class="form-control">
        </div>
        
        <div class="form-group">
            <label for="salles_mauvais_etat">Dont mauvais état</label>
            <input type="number" id="salles_mauvais_etat" name="salles_mauvais_etat" min="0" value="0" class="form-control">
        </div>
        
        <div class="form-group">
            <label for="salles_paillote">Dont en paillote</label>
            <input type="number" id="salles_paillote" name="salles_paillote" min="0" value="0" class="form-control">
        </div>
        
        <div class="form-group">
            <label for="salles_non_occupees">Dont non occupées</label>
            <input type="number" id="salles_non_occupees" name="salles_non_occupees" min="0" value="0" class="form-control">
        </div>
    </div>

    <h3 class="subsection-title">Mobiliers et Équipements</h3>
    <div class="form-grid">
        <div class="form-group">
            <label for="total_tables_bancs_sec">Nombre total de tables-bancs</label>
            <input type="number" id="total_tables_bancs_sec" name="mobiliers[total_tables_bancs]" min="0" value="0" class="form-control">
        </div>
        
        <div class="form-group">
            <label for="tables_bancs_a_rehabiliter">Dont à réhabiliter</label>
            <input type="number" id="tables_bancs_a_rehabiliter" name="mobiliers[tables_bancs_a_reparer]" min="0" value="0" class="form-control">
        </div>
        
        <div class="form-group">
            <label for="tableau_chevalet">Tableau à chevalet</label>
            <input type="number" id="tableau_chevalet" name="mobiliers[tableau_chevalet]" min="0" value="0" class="form-control">
        </div>
    </div>

    <h3 class="subsection-title">Autres Infrastructures</h3>
    <div class="form-grid">
        <div class="form-group">
            <label>Bibliothèque</label>
            <div class="radio-group">
                <label class="radio-label">
                    <input type="radio" name="infrastructures[bibliotheque]" value="1"> Oui
                </label>
                <label class="radio-label">
                    <input type="radio" name="infrastructures[bibliotheque]" value="0"> Non
                </label>
            </div>
        </div>
        
        <div class="form-group">
            <label>Infirmerie</label>
            <div class="radio-group">
                <label class="radio-label">
                    <input type="radio" name="infrastructures[infirmerie]" value="1"> Oui
                </label>
                <label class="radio-label">
                    <input type="radio" name="infrastructures[infirmerie]" value="0"> Non
                </label>
            </div>
        </div>
        
        <div class="form-group">
            <label for="laboratoires">Laboratoires (nombre)</label>
            <input type="number" id="laboratoires" name="infrastructures[laboratoires]" min="0" value="0" class="form-control">
        </div>
        
        <div class="form-group">
            <label>Salle informatique</label>
            <div class="radio-group">
                <label class="radio-label">
                    <input type="radio" name="infrastructures[salle_informatique]" value="1"> Oui
                </label>
                <label class="radio-label">
                    <input type="radio" name="infrastructures[salle_informatique]" value="0"> Non
                </label>
            </div>
        </div>
        
        <div class="form-group">
            <label for="latrines_total_sec">Latrines (total)</label>
            <input type="number" id="latrines_total_sec" name="infrastructures[total_latrines]" min="0" value="0" class="form-control">
        </div>
        
        <div class="form-group">
            <label>Clôture</label>
            <div class="radio-group">
                <label class="radio-label">
                    <input type="radio" name="infrastructures[cloture]" value="1"> Oui
                </label>
                <label class="radio-label">
                    <input type="radio" name="infrastructures[cloture]" value="0"> Non
                </label>
            </div>
        </div>
    </div>
</div>

<!-- SECTION 3: RÉPARTITION DES ÉLÈVES -->
<div class="form-section" data-section="3">
    <h2 class="section-title">
        <i class="fas fa-users"></i> III. Répartition des Élèves par Niveau et par Sexe
    </h2>
    
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th rowspan="2">Niveau et Série</th>
                    <th colspan="3">Effectifs élèves</th>
                    <th colspan="3">Dont Redoublants</th>
                    <th rowspan="2">Nb GP<br>(Classes)</th>
                </tr>
                <tr>
                    <th>Garçons</th>
                    <th>Filles</th>
                    <th>Total</th>
                    <th>Garçons</th>
                    <th>Filles</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <!-- Premier cycle (Collège) -->
                <tr><td colspan="8" style="background: #E8F5E9; font-weight: bold; text-align: center;">PREMIER CYCLE</td></tr>
                <?php 
                $niveaux_college = [
                    '6ème' => '6eme',
                    '5ème' => '5eme',
                    '4ème' => '4eme',
                    '3ème' => '3eme'
                ];
                foreach($niveaux_college as $label => $value): 
                ?>
                <tr>
                    <td class="row-header"><?php echo $label; ?></td>
                    <td><input type="number" name="effectifs[<?php echo $value; ?>][garcons]" min="0" value="0" class="form-control-sm effectif-sec" data-niveau="<?php echo $value; ?>"></td>
                    <td><input type="number" name="effectifs[<?php echo $value; ?>][filles]" min="0" value="0" class="form-control-sm effectif-sec" data-niveau="<?php echo $value; ?>"></td>
                    <td class="total-cell" id="total_<?php echo $value; ?>">0</td>
                    <td><input type="number" name="effectifs[<?php echo $value; ?>][redoublants_garcons]" min="0" value="0" class="form-control-sm effectif-sec" data-niveau="<?php echo $value; ?>"></td>
                    <td><input type="number" name="effectifs[<?php echo $value; ?>][redoublants_filles]" min="0" value="0" class="form-control-sm effectif-sec" data-niveau="<?php echo $value; ?>"></td>
                    <td class="total-cell" id="total_redoub_<?php echo $value; ?>">0</td>
                    <td><input type="number" name="effectifs[<?php echo $value; ?>][nb_groupes]" min="0" value="0" class="form-control-sm"></td>
                </tr>
                <?php endforeach; ?>
                <tr class="total-row">
                    <td>Total 1er cycle</td>
                    <td id="total_1er_garcons">0</td>
                    <td id="total_1er_filles">0</td>
                    <td id="total_1er_eleves">0</td>
                    <td id="total_1er_redoub_garcons">0</td>
                    <td id="total_1er_redoub_filles">0</td>
                    <td id="total_1er_redoub">0</td>
                    <td id="total_1er_classes">0</td>
                </tr>

                <!-- Second cycle (Lycée) -->
                <tr><td colspan="8" style="background: #FFF3E0; font-weight: bold; text-align: center;">SECOND CYCLE</td></tr>
                <?php 
                $niveaux_lycee = [
                    '2nde A' => '2nde_a',
                    '2nde C' => '2nde_c',
                    '1ère A' => '1ere_a',
                    '1ère C' => '1ere_c',
                    '1ère D' => '1ere_d',
                    'Tle A' => 'tle_a',
                    'Tle C' => 'tle_c',
                    'Tle D' => 'tle_d'
                ];
                foreach($niveaux_lycee as $label => $value): 
                ?>
                <tr>
                    <td class="row-header"><?php echo $label; ?></td>
                    <td><input type="number" name="effectifs[<?php echo $value; ?>][garcons]" min="0" value="0" class="form-control-sm effectif-sec" data-niveau="<?php echo $value; ?>" data-cycle="2nd"></td>
                    <td><input type="number" name="effectifs[<?php echo $value; ?>][filles]" min="0" value="0" class="form-control-sm effectif-sec" data-niveau="<?php echo $value; ?>" data-cycle="2nd"></td>
                    <td class="total-cell" id="total_<?php echo $value; ?>">0</td>
                    <td><input type="number" name="effectifs[<?php echo $value; ?>][redoublants_garcons]" min="0" value="0" class="form-control-sm effectif-sec" data-niveau="<?php echo $value; ?>" data-cycle="2nd"></td>
                    <td><input type="number" name="effectifs[<?php echo $value; ?>][redoublants_filles]" min="0" value="0" class="form-control-sm effectif-sec" data-niveau="<?php echo $value; ?>" data-cycle="2nd"></td>
                    <td class="total-cell" id="total_redoub_<?php echo $value; ?>">0</td>
                    <td><input type="number" name="effectifs[<?php echo $value; ?>][nb_groupes]" min="0" value="0" class="form-control-sm nb-classes-sec" data-cycle="2nd"></td>
                </tr>
                <?php endforeach; ?>
                <tr class="total-row">
                    <td>Total 2nd cycle</td>
                    <td id="total_2nd_garcons">0</td>
                    <td id="total_2nd_filles">0</td>
                    <td id="total_2nd_eleves">0</td>
                    <td id="total_2nd_redoub_garcons">0</td>
                    <td id="total_2nd_redoub_filles">0</td>
                    <td id="total_2nd_redoub">0</td>
                    <td id="total_2nd_classes">0</td>
                </tr>

                <tr class="total-row" style="background: linear-gradient(135deg, #C8E6C9, #A5D6A7);">
                    <td><strong>Total Établissement</strong></td>
                    <td id="total_etab_garcons">0</td>
                    <td id="total_etab_filles">0</td>
                    <td id="total_etab_eleves">0</td>
                    <td id="total_etab_redoub_garcons">0</td>
                    <td id="total_etab_redoub_filles">0</td>
                    <td id="total_etab_redoub">0</td>
                    <td id="total_etab_classes">0</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="info-box">
        <i class="fas fa-info-circle"></i>
        <p>Les totaux sont calculés automatiquement. GP = Groupes Pédagogiques (nombre de classes).</p>
    </div>
</div>

<!-- SECTION 4: PERSONNEL ADMINISTRATIF ET DE SOUTIEN -->
<div class="form-section" data-section="4">
    <h2 class="section-title">
        <i class="fas fa-user-tie"></i> IV. Personnel Administratif et de Soutien
    </h2>
    
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Poste</th>
                    <th>Existant</th>
                    <th>Besoin</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $postes = [
                    'Directeur / Proviseur' => 'directeur',
                    'Censeur(s)' => 'censeur',
                    'Surveillant(s) général(aux)' => 'surveillant',
                    'Sécretaire(s)' => 'secretaire',
                    'Bibliothécaire' => 'bibliothecaire',
                    'Laborantin' => 'laborantin',
                    'Planton' => 'planton',
                    'Gardien' => 'gardien',
                    'Manœuvre' => 'manoeuvre',
                    'Intendant/Econome' => 'intendant',
                    'Autre' => 'autre'
                ];
                
                foreach ($postes as $label => $name):
                ?>
                <tr>
                    <td class="row-header"><?php echo $label; ?></td>
                    <td><input type="number" name="personnel_admin[<?php echo $name; ?>][existant]" min="0" value="0" class="form-control-sm"></td>
                    <td><input type="number" name="personnel_admin[<?php echo $name; ?>][besoin]" min="0" value="0" class="form-control-sm"></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- SECTION 5: PERSONNEL ENSEIGNANT -->
<div class="form-section" data-section="5">
    <h2 class="section-title">
        <i class="fas fa-chalkboard-teacher"></i> V. Personnel Enseignant (en situation de classe)
    </h2>
    
    <div class="info-box">
        <i class="fas fa-info-circle"></i>
        <p>Remplir uniquement pour les disciplines enseignées dans votre établissement.</p>
    </div>

    <div class="table-responsive">
        <table class="data-table" style="font-size: 0.85rem;">
            <thead>
                <tr>
                    <th rowspan="2">Disciplines</th>
                    <th colspan="2">P.E.S</th>
                    <th colspan="2">C.E</th>
                    <th colspan="2">Prof de CEG</th>
                    <th colspan="2">Autres</th>
                    <th colspan="2">Contractuel</th>
                </tr>
                <tr>
                    <th>H</th><th>F</th>
                    <th>H</th><th>F</th>
                    <th>H</th><th>F</th>
                    <th>H</th><th>F</th>
                    <th>H</th><th>F</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $disciplines = [
                    'FR' => 'fr',
                    'FR/HG' => 'fr_hg',
                    'H-G' => 'hg',
                    'Anglais' => 'anglais',
                    'Etude. Islam' => 'etude_islam',
                    'L. Arabe' => 'l_arabe',
                    'Philo' => 'philo',
                    'Maths' => 'maths',
                    'M/PC' => 'm_pc',
                    'M/SVT' => 'm_svt',
                    'PC/SVT' => 'pc_svt',
                    'PC' => 'pc',
                    'SVT' => 'svt',
                    'EF' => 'ef',
                    'EPS' => 'eps',
                    'ASCN' => 'ascn'
                ];
                
                foreach ($disciplines as $label => $name):
                ?>
                <tr>
                    <td class="row-header"><?php echo $label; ?></td>
                    <td><input type="number" name="personnel[<?php echo $name; ?>][pes_h]" min="0" value="0" class="form-control-sm" style="width: 50px;"></td>
                    <td><input type="number" name="personnel[<?php echo $name; ?>][pes_f]" min="0" value="0" class="form-control-sm" style="width: 50px;"></td>
                    <td><input type="number" name="personnel[<?php echo $name; ?>][ce_h]" min="0" value="0" class="form-control-sm" style="width: 50px;"></td>
                    <td><input type="number" name="personnel[<?php echo $name; ?>][ce_f]" min="0" value="0" class="form-control-sm" style="width: 50px;"></td>
                    <td><input type="number" name="personnel[<?php echo $name; ?>][prof_ceg_h]" min="0" value="0" class="form-control-sm" style="width: 50px;"></td>
                    <td><input type="number" name="personnel[<?php echo $name; ?>][prof_ceg_f]" min="0" value="0" class="form-control-sm" style="width: 50px;"></td>
                    <td><input type="number" name="personnel[<?php echo $name; ?>][autres_h]" min="0" value="0" class="form-control-sm" style="width: 50px;"></td>
                    <td><input type="number" name="personnel[<?php echo $name; ?>][autres_f]" min="0" value="0" class="form-control-sm" style="width: 50px;"></td>
                    <td><input type="number" name="personnel[<?php echo $name; ?>][contractuel_h]" min="0" value="0" class="form-control-sm" style="width: 50px;"></td>
                    <td><input type="number" name="personnel[<?php echo $name; ?>][contractuel_f]" min="0" value="0" class="form-control-sm" style="width: 50px;"></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <h2 class="section-title" style="margin-top: 2rem;">
        <i class="fas fa-signature"></i> Informations de Contact et Validation
    </h2>
    
    <div class="form-grid">
        <div class="form-group">
            <label for="date_remplissage">Date de remplissage <span class="required">*</span></label>
            <input type="date" id="date_remplissage" name="date_remplissage" required class="form-control" value="<?php echo date('Y-m-d'); ?>">
        </div>
        
        <div class="form-group">
            <label for="telephone_directeur">Numéro Tél. Chef d'établissement <span class="required">*</span></label>
            <input type="tel" id="telephone_directeur" name="telephone_directeur" required class="form-control" placeholder="+227 XX XX XX XX">
        </div>
        
        <div class="form-group col-2">
            <label for="nom_signature_directeur">Nom et signature du Chef d'établissement <span class="required">*</span></label>
            <input type="text" id="nom_signature_directeur" name="nom_signature_directeur" required class="form-control">
        </div>
        
        <div class="form-group">
            <label for="telephone_statisticien">Téléphone du statisticien de l'IESG/IEFA</label>
            <input type="tel" id="telephone_statisticien" name="telephone_statisticien" class="form-control" placeholder="+227 XX XX XX XX">
        </div>
    </div>

    <div class="success-box">
        <i class="fas fa-check-circle"></i>
        <p><strong>Dernière étape !</strong> Vérifiez vos informations et soumettez le formulaire.</p>
    </div>
</div>

<script>
// Calcul automatique des totaux pour le secondaire
function calculateTotalSecondaire() {
    // Premier cycle
    const niveaux1er = ['6eme', '5eme', '4eme', '3eme'];
    let total1erGarcons = 0, total1erFilles = 0;
    let total1erRedoubGarcons = 0, total1erRedoubFilles = 0;
    let total1erClasses = 0;
    
    niveaux1er.forEach(niveau => {
        const garcons = parseInt(document.querySelector(`[name="effectifs[${niveau}][garcons]"]`)?.value) || 0;
        const filles = parseInt(document.querySelector(`[name="effectifs[${niveau}][filles]"]`)?.value) || 0;
        const redoubG = parseInt(document.querySelector(`[name="effectifs[${niveau}][redoublants_garcons]"]`)?.value) || 0;
        const redoubF = parseInt(document.querySelector(`[name="effectifs[${niveau}][redoublants_filles]"]`)?.value) || 0;
        const nbClasses = parseInt(document.querySelector(`[name="effectifs[${niveau}][nb_groupes]"]`)?.value) || 0;
        
        // Totaux par niveau
        const totalEl = document.getElementById(`total_${niveau}`);
        const totalRedoubEl = document.getElementById(`total_redoub_${niveau}`);
        if (totalEl) totalEl.textContent = garcons + filles;
        if (totalRedoubEl) totalRedoubEl.textContent = redoubG + redoubF;
        
        total1erGarcons += garcons;
        total1erFilles += filles;
        total1erRedoubGarcons += redoubG;
        total1erRedoubFilles += redoubF;
        total1erClasses += nbClasses;
    });
    
    // Second cycle
    const niveaux2nd = ['2nde_a', '2nde_c', '1ere_a', '1ere_c', '1ere_d', 'tle_a', 'tle_c', 'tle_d'];
    let total2ndGarcons = 0, total2ndFilles = 0;
    let total2ndRedoubGarcons = 0, total2ndRedoubFilles = 0;
    let total2ndClasses = 0;
    
    niveaux2nd.forEach(niveau => {
        const garcons = parseInt(document.querySelector(`[name="effectifs[${niveau}][garcons]"]`)?.value) || 0;
        const filles = parseInt(document.querySelector(`[name="effectifs[${niveau}][filles]"]`)?.value) || 0;
        const redoubG = parseInt(document.querySelector(`[name="effectifs[${niveau}][redoublants_garcons]"]`)?.value) || 0;
        const redoubF = parseInt(document.querySelector(`[name="effectifs[${niveau}][redoublants_filles]"]`)?.value) || 0;
        const nbClasses = parseInt(document.querySelector(`[name="effectifs[${niveau}][nb_groupes]"]`)?.value) || 0;
        
        // Totaux par niveau
        const totalEl = document.getElementById(`total_${niveau}`);
        const totalRedoubEl = document.getElementById(`total_redoub_${niveau}`);
        if (totalEl) totalEl.textContent = garcons + filles;
        if (totalRedoubEl) totalRedoubEl.textContent = redoubG + redoubF;
        
        total2ndGarcons += garcons;
        total2ndFilles += filles;
        total2ndRedoubGarcons += redoubG;
        total2ndRedoubFilles += redoubF;
        total2ndClasses += nbClasses;
    });
    
    // Afficher les totaux
    const setTotal = (id, value) => {
        const el = document.getElementById(id);
        if (el) el.textContent = value;
    };
    
    // Totaux 1er cycle
    setTotal('total_1er_garcons', total1erGarcons);
    setTotal('total_1er_filles', total1erFilles);
    setTotal('total_1er_eleves', total1erGarcons + total1erFilles);
    setTotal('total_1er_redoub_garcons', total1erRedoubGarcons);
    setTotal('total_1er_redoub_filles', total1erRedoubFilles);
    setTotal('total_1er_redoub', total1erRedoubGarcons + total1erRedoubFilles);
    setTotal('total_1er_classes', total1erClasses);
    
    // Totaux 2nd cycle
    setTotal('total_2nd_garcons', total2ndGarcons);
    setTotal('total_2nd_filles', total2ndFilles);
    setTotal('total_2nd_eleves', total2ndGarcons + total2ndFilles);
    setTotal('total_2nd_redoub_garcons', total2ndRedoubGarcons);
    setTotal('total_2nd_redoub_filles', total2ndRedoubFilles);
    setTotal('total_2nd_redoub', total2ndRedoubGarcons + total2ndRedoubFilles);
    setTotal('total_2nd_classes', total2ndClasses);
    
    // Totaux établissement
    setTotal('total_etab_garcons', total1erGarcons + total2ndGarcons);
    setTotal('total_etab_filles', total1erFilles + total2ndFilles);
    setTotal('total_etab_eleves', total1erGarcons + total1erFilles + total2ndGarcons + total2ndFilles);
    setTotal('total_etab_redoub_garcons', total1erRedoubGarcons + total2ndRedoubGarcons);
    setTotal('total_etab_redoub_filles', total1erRedoubFilles + total2ndRedoubFilles);
    setTotal('total_etab_redoub', total1erRedoubGarcons + total1erRedoubFilles + total2ndRedoubGarcons + total2ndRedoubFilles);
    setTotal('total_etab_classes', total1erClasses + total2ndClasses);
}

// Attacher les événements
document.addEventListener('DOMContentLoaded', function() {
    const effectifInputs = document.querySelectorAll('.effectif-sec, .nb-classes-sec');
    effectifInputs.forEach(input => {
        input.addEventListener('change', calculateTotalSecondaire);
    });
});

// Charger les départements
const departementsData = <?php echo json_encode($departements_par_region); ?>;

function loadDepartements(region) {
    const depSelect = document.getElementById('departement');
    if (!depSelect) return;
    
    depSelect.innerHTML = '<option value="">-- Sélectionnez --</option>';
    
    if (region && departementsData[region]) {
        depSelect.disabled = false;
        departementsData[region].forEach(dep => {
            const option = document.createElement('option');
            option.value = dep;
            option.textContent = dep;
            depSelect.appendChild(option);
        });
    } else {
        depSelect.disabled = true;
    }
}
</script>
