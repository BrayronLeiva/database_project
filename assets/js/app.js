/**
 * Book Manager Pro - JavaScript Interactivo
 */

// Confirmación mejorada para eliminaciones
function confirmDelete(bookTitle, bookAuthor) {
    const message = `¿Estás seguro de eliminar este libro?\n\n` +
                    `📖 Título: ${bookTitle}\n` +
                    `👤 Autor: ${bookAuthor}\n\n` +
                    `⚠️ Esta acción no se puede deshacer.`;
    return confirm(message);
}

// Animación de carga
function showLoading() {
    const loadingEl = document.createElement('div');
    loadingEl.id = 'loading-overlay';
    loadingEl.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
    `;
    loadingEl.innerHTML = `
        <div style="background: white; padding: 2rem; border-radius: 12px; text-align: center;">
            <div class="loading" style="font-size: 3rem; margin-bottom: 1rem;">📚</div>
            <div style="color: var(--text-primary);">Cargando...</div>
        </div>
    `;
    document.body.appendChild(loadingEl);
}

// Validación de formularios en tiempo real
document.addEventListener('DOMContentLoaded', function() {
    // Validación de año
    const yearInputs = document.querySelectorAll('input[type="number"][name="year"]');
    yearInputs.forEach(input => {
        input.addEventListener('input', function() {
            const year = parseInt(this.value);
            const currentYear = new Date().getFullYear();
            
            if (this.value && (year < 1000 || year > currentYear + 10)) {
                this.style.borderColor = 'var(--danger)';
                this.setCustomValidity('El año debe estar entre 1000 y ' + (currentYear + 10));
            } else {
                this.style.borderColor = '';
                this.setCustomValidity('');
            }
        });
    });

    // Auto-capitalizar primera letra
    const textInputs = document.querySelectorAll('input[type="text"][name="title"], input[type="text"][name="author"]');
    textInputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (this.value) {
                this.value = this.value.charAt(0).toUpperCase() + this.value.slice(1);
            }
        });
    });

    // Contador de caracteres para inputs largos
    const titleInput = document.querySelector('input[name="title"]');
    if (titleInput) {
        const counter = document.createElement('small');
        counter.style.cssText = 'display: block; margin-top: 0.25rem; color: var(--text-tertiary);';
        titleInput.parentElement.appendChild(counter);
        
        function updateCounter() {
            const length = titleInput.value.length;
            counter.textContent = `${length} caracteres`;
            if (length > 100) {
                counter.style.color = 'var(--warning)';
            } else {
                counter.style.color = 'var(--text-tertiary)';
            }
        }
        
        titleInput.addEventListener('input', updateCounter);
        updateCounter();
    }
});

// Función para copiar credenciales (en página de instalación)
function copyCredentials() {
    const text = 'Usuario: admin\nContraseña: clave123';
    navigator.clipboard.writeText(text).then(() => {
        alert('✅ Credenciales copiadas al portapapeles');
    }).catch(() => {
        alert('❌ No se pudo copiar. Por favor, copia manualmente.');
    });
}

// Toast notifications
function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `alert alert-${type}`;
    toast.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        min-width: 300px;
        z-index: 10000;
        animation: slideIn 0.3s ease-out;
    `;
    toast.textContent = message;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.animation = 'slideOut 0.3s ease-out';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// Añadir animaciones CSS
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
