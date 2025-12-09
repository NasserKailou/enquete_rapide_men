/**
 * Gestion du formulaire d'enquête
 * Navigation multi-étapes, validation, calculs automatiques
 */

let currentSection = 1;
let totalSections = 5;

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    initializeForm();
    showSection(currentSection);
    updateProgressBar();
    
    // Auto-save
    enableAutoSave();
    
    // Génération automatique du code
    autoGenerateCode();
});

/**
 * Initialiser le formulaire
 */
function initializeForm() {
    const sections = document.querySelectorAll('.form-section');
    totalSections = sections.length;
    
    // Bouton précédent
    const prevBtn = document.getElementById('prevBtn');
    if (prevBtn) {
        prevBtn.style.display = 'none';
    }
    
    // Gestion de la soumission
    const form = document.getElementById('enqueteForm');
    if (form) {
        form.addEventListener('submit', handleFormSubmit);
    }
}

/**
 * Afficher une section spécifique
 */
function showSection(sectionNumber) {
    const sections = document.querySelectorAll('.form-section');
    
    sections.forEach((section, index) => {
        if (index + 1 === sectionNumber) {
            section.classList.add('active');
            section.style.display = 'block';
        } else {
            section.classList.remove('active');
            section.style.display = 'none';
        }
    });
    
    // Gestion des boutons
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const submitBtn = document.getElementById('submitBtn');
    
    if (prevBtn) {
        prevBtn.style.display = sectionNumber === 1 ? 'none' : 'inline-flex';
    }
    
    if (sectionNumber === totalSections) {
        nextBtn.style.display = 'none';
        submitBtn.style.display = 'inline-flex';
    } else {
        nextBtn.style.display = 'inline-flex';
        submitBtn.style.display = 'none';
    }
    
    // Scroll to top
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

/**
 * Changer de section
 */
function changeSection(direction) {
    const newSection = currentSection + direction;
    
    // Validation avant de passer à la section suivante
    if (direction > 0 && !validateCurrentSection()) {
        showNotification('Veuillez remplir tous les champs obligatoires', 'error');
        return;
    }
    
    if (newSection >= 1 && newSection <= totalSections) {
        currentSection = newSection;
        showSection(currentSection);
        updateProgressBar();
    }
}

/**
 * Valider la section actuelle
 */
function validateCurrentSection() {
    const currentSectionElement = document.querySelector(`.form-section[data-section="${currentSection}"]`);
    if (!currentSectionElement) return true;
    
    const requiredInputs = currentSectionElement.querySelectorAll('[required]');
    let isValid = true;
    
    requiredInputs.forEach(input => {
        if (!input.value.trim() && input.type !== 'radio' && input.type !== 'checkbox') {
            input.style.borderColor = '#E74C3C';
            isValid = false;
        } else if ((input.type === 'radio' || input.type === 'checkbox')) {
            const name = input.name;
            const checked = currentSectionElement.querySelector(`[name="${name}"]:checked`);
            if (!checked) {
                isValid = false;
            }
        } else {
            input.style.borderColor = '#E0E0E0';
        }
    });
    
    return isValid;
}

/**
 * Mettre à jour la barre de progression
 */
function updateProgressBar() {
    const progressFill = document.getElementById('progressFill');
    const progressText = document.getElementById('progressText');
    const progressPercent = document.getElementById('progressPercent');
    
    const percentage = ((currentSection - 1) / (totalSections - 1)) * 100;
    
    if (progressFill) {
        progressFill.style.width = percentage + '%';
    }
    
    if (progressText) {
        progressText.textContent = `Section ${currentSection} / ${totalSections}`;
    }
    
    if (progressPercent) {
        progressPercent.textContent = Math.round(percentage) + '%';
    }
}

/**
 * Gérer la soumission du formulaire
 */
async function handleFormSubmit(e) {
    e.preventDefault();
    
    // Validation complète
    if (!validateForm()) {
        showNotification('Veuillez vérifier tous les champs obligatoires', 'error');
        return;
    }
    
    // Confirmation
    if (!confirm('Êtes-vous sûr de vouloir soumettre cette enquête ? Vous ne pourrez plus la modifier.')) {
        return;
    }
    
    // Afficher le spinner
    showSpinner();
    
    const formData = new FormData(e.target);
    
    try {
        const response = await fetch('submit_form.php', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        hideSpinner();
        
        if (result.success) {
            showNotification('Enquête soumise avec succès !', 'success');
            setTimeout(() => {
                window.location.href = 'success.php?id=' + result.etablissement_id;
            }, 2000);
        } else {
            showNotification('Erreur : ' + result.message, 'error');
        }
    } catch (error) {
        hideSpinner();
        showNotification('Erreur de communication avec le serveur', 'error');
        console.error('Error:', error);
    }
}

/**
 * Valider tout le formulaire
 */
function validateForm() {
    const form = document.getElementById('enqueteForm');
    const requiredInputs = form.querySelectorAll('[required]');
    let isValid = true;
    
    requiredInputs.forEach(input => {
        if (!input.value.trim() && input.type !== 'radio' && input.type !== 'checkbox') {
            isValid = false;
        } else if ((input.type === 'radio' || input.type === 'checkbox')) {
            const name = input.name;
            const checked = form.querySelector(`[name="${name}"]:checked`);
            if (!checked) {
                isValid = false;
            }
        }
    });
    
    return isValid;
}

/**
 * Activer la sauvegarde automatique
 */
function enableAutoSave() {
    const form = document.getElementById('enqueteForm');
    if (!form) return;
    
    let saveTimeout;
    
    form.addEventListener('input', function() {
        clearTimeout(saveTimeout);
        saveTimeout = setTimeout(() => {
            saveFormData();
        }, 2000); // Sauvegarder après 2 secondes d'inactivité
    });
    
    // Charger les données sauvegardées
    loadFormData();
}

/**
 * Sauvegarder les données du formulaire dans localStorage
 */
function saveFormData() {
    const form = document.getElementById('enqueteForm');
    if (!form) return;
    
    const formData = new FormData(form);
    const data = {};
    
    formData.forEach((value, key) => {
        if (data[key]) {
            if (Array.isArray(data[key])) {
                data[key].push(value);
            } else {
                data[key] = [data[key], value];
            }
        } else {
            data[key] = value;
        }
    });
    
    // Sauvegarder dans localStorage avec un identifiant unique
    const storageKey = `enquete_${cycle}_draft`;
    localStorage.setItem(storageKey, JSON.stringify(data));
    
    // Afficher un indicateur visuel
    showAutoSaveIndicator();
}

/**
 * Charger les données sauvegardées
 */
function loadFormData() {
    const storageKey = `enquete_${cycle}_draft`;
    const savedData = localStorage.getItem(storageKey);
    
    if (!savedData) return;
    
    try {
        const data = JSON.parse(savedData);
        const form = document.getElementById('enqueteForm');
        
        Object.keys(data).forEach(key => {
            const input = form.querySelector(`[name="${key}"]`);
            if (input) {
                if (input.type === 'radio' || input.type === 'checkbox') {
                    const value = Array.isArray(data[key]) ? data[key] : [data[key]];
                    value.forEach(val => {
                        const radioInput = form.querySelector(`[name="${key}"][value="${val}"]`);
                        if (radioInput) radioInput.checked = true;
                    });
                } else {
                    input.value = data[key];
                }
            }
        });
        
        showNotification('Données précédentes restaurées', 'info');
    } catch (error) {
        console.error('Erreur lors du chargement des données:', error);
    }
}

/**
 * Indicateur de sauvegarde automatique
 */
function showAutoSaveIndicator() {
    let indicator = document.getElementById('autoSaveIndicator');
    
    if (!indicator) {
        indicator = document.createElement('div');
        indicator.id = 'autoSaveIndicator';
        indicator.style.cssText = `
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #27AE60;
            color: white;
            padding: 10px 20px;
            border-radius: 50px;
            font-size: 0.85rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            z-index: 1000;
            opacity: 0;
            transition: opacity 0.3s ease;
        `;
        indicator.innerHTML = '<i class="fas fa-check"></i> Sauvegarde automatique';
        document.body.appendChild(indicator);
    }
    
    indicator.style.opacity = '1';
    
    setTimeout(() => {
        indicator.style.opacity = '0';
    }, 2000);
}

/**
 * Générer automatiquement le code établissement
 */
function autoGenerateCode() {
    const codeInput = document.getElementById('code_je') || 
                      document.getElementById('code_ecole') || 
                      document.getElementById('code_etablissement');
    
    const regionSelect = document.getElementById('region');
    
    if (codeInput && regionSelect && !codeInput.value) {
        regionSelect.addEventListener('change', function() {
            if (!codeInput.value) {
                const prefix = {
                    'prescolaire': 'PRE',
                    'primaire': 'PRI',
                    'secondaire': 'SEC'
                }[cycle] || 'ETB';
                
                const regionCode = this.value.substring(0, 3).toUpperCase();
                const date = new Date();
                const dateCode = date.getFullYear().toString().substr(-2) + 
                                 ('0' + (date.getMonth() + 1)).slice(-2) + 
                                 ('0' + date.getDate()).slice(-2);
                const random = Math.floor(1000 + Math.random() * 9000);
                
                codeInput.value = `${prefix}-${regionCode}-${dateCode}-${random}`;
            }
        });
    }
}

/**
 * Afficher une notification
 */
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.style.cssText = `
        position: fixed;
        top: 120px;
        right: 20px;
        max-width: 400px;
        padding: 1.5rem;
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        z-index: 9999;
        animation: slideIn 0.3s ease;
    `;
    
    const colors = {
        'success': '#27AE60',
        'error': '#E74C3C',
        'warning': '#F39C12',
        'info': '#3498DB'
    };
    
    const icons = {
        'success': 'fa-check-circle',
        'error': 'fa-exclamation-circle',
        'warning': 'fa-exclamation-triangle',
        'info': 'fa-info-circle'
    };
    
    notification.innerHTML = `
        <div style="display: flex; align-items: center; gap: 1rem;">
            <i class="fas ${icons[type]}" style="font-size: 1.5rem; color: ${colors[type]};"></i>
            <p style="margin: 0; color: #2C3E50; font-weight: 500;">${message}</p>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => notification.remove(), 300);
    }, 4000);
}

/**
 * Afficher le spinner de chargement
 */
function showSpinner() {
    const spinner = document.createElement('div');
    spinner.id = 'loadingSpinner';
    spinner.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 99999;
    `;
    
    spinner.innerHTML = `
        <div style="background: white; padding: 3rem; border-radius: 20px; text-align: center;">
            <div class="spinner"></div>
            <p style="margin-top: 1rem; color: #2C3E50; font-weight: 600;">Envoi en cours...</p>
        </div>
    `;
    
    document.body.appendChild(spinner);
}

/**
 * Masquer le spinner
 */
function hideSpinner() {
    const spinner = document.getElementById('loadingSpinner');
    if (spinner) {
        spinner.remove();
    }
}

/**
 * Animations CSS
 */
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(400px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(400px);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);

// Exposer les fonctions globalement
window.changeSection = changeSection;
window.calculateTotal = calculateTotal || function() {};
