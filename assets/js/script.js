// ==========================================
// SKILL GAP ANALYZER - JAVASCRIPT
// ==========================================

document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 Skill Gap Analyzer loaded successfully!');
    
    // Inisialisasi semua fungsi
    initAnimations();
    initSkillCheckbox();
    initFormValidation();
    initMobileMenu();
    initCharts(); // TAMBAHAN: Inisialisasi grafik
});

// ==========================================
// 1. ANIMASI FADE-IN
// ==========================================
function initAnimations() {
    const elements = document.querySelectorAll('.dashboard-card, .trending-item, .skill-item, .feature-item, .step-item');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry, index) => {
            if (entry.isIntersecting) {
                setTimeout(() => {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }, index * 80);
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    });
    
    elements.forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(30px)';
        el.style.transition = 'all 0.6s cubic-bezier(0.4, 0, 0.2, 1)';
        observer.observe(el);
    });
}

// ==========================================
// 2. SKILL CHECKBOX INTERAKTIF
// ==========================================
function initSkillCheckbox() {
    const skillItems = document.querySelectorAll('.skill-item');
    
    skillItems.forEach(item => {
        const checkbox = item.querySelector('input[type="checkbox"]');
        const label = item.querySelector('label');
        const select = item.querySelector('select');
        
        if (checkbox) {
            // Click pada item toggle checkbox
            item.addEventListener('click', function(e) {
                if (e.target.tagName === 'SELECT' || e.target.tagName === 'OPTION') return;
                if (e.target.tagName === 'INPUT') return;
                if (e.target.tagName === 'LABEL') return;
                if (e.target.tagName === 'TEXTAREA') return;
                checkbox.checked = !checkbox.checked;
                updateSkillItemStyle(item, checkbox.checked);
                // Trigger change event
                const event = new Event('change');
                checkbox.dispatchEvent(event);
            });
            
            checkbox.addEventListener('change', function() {
                updateSkillItemStyle(item, this.checked);
                // Toggle deskripsi
                const descContainer = item.querySelector('.skill-desc-wrapper, .skill-desc');
                if (descContainer) {
                    descContainer.style.display = this.checked ? 'block' : 'none';
                }
            });
            
            updateSkillItemStyle(item, checkbox.checked);
        }
    });
}

function updateSkillItemStyle(item, isChecked) {
    if (isChecked) {
        item.style.borderColor = 'rgba(46, 204, 113, 0.4)';
        item.style.background = 'rgba(46, 204, 113, 0.08)';
        item.style.boxShadow = '0 0 20px rgba(46, 204, 113, 0.05)';
        item.classList.add('selected');
    } else {
        item.style.borderColor = 'rgba(255, 255, 255, 0.06)';
        item.style.background = 'rgba(255, 255, 255, 0.03)';
        item.style.boxShadow = 'none';
        item.classList.remove('selected');
    }
}

// ==========================================
// 3. FORM VALIDASI
// ==========================================
function initFormValidation() {
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        const inputs = form.querySelectorAll('input[required]');
        
        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                validateField(this);
            });
            
            input.addEventListener('input', function() {
                if (this.classList.contains('error')) {
                    validateField(this);
                }
            });
        });
        
        form.addEventListener('submit', function(e) {
            let isValid = true;
            const inputs = this.querySelectorAll('input[required]');
            
            inputs.forEach(input => {
                if (!validateField(input)) {
                    isValid = false;
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                showNotification('⚠️ Mohon lengkapi semua field yang diperlukan!', 'warning');
            }
        });
    });
}

function validateField(input) {
    const value = input.value.trim();
    let isValid = true;
    let errorMessage = '';
    
    if (value === '') {
        isValid = false;
        errorMessage = 'Field ini harus diisi!';
    } else if (input.type === 'email' && !isValidEmail(value)) {
        isValid = false;
        errorMessage = 'Email tidak valid!';
    } else if (input.type === 'password' && value.length < 6) {
        isValid = false;
        errorMessage = 'Password minimal 6 karakter!';
    }
    
    if (!isValid) {
        input.classList.add('error');
        input.style.borderColor = 'rgba(231, 76, 60, 0.5)';
        input.style.boxShadow = '0 0 0 4px rgba(231, 76, 60, 0.1)';
        
        let errorEl = input.parentElement.querySelector('.field-error');
        if (!errorEl) {
            errorEl = document.createElement('small');
            errorEl.className = 'field-error';
            errorEl.style.color = '#e74c3c';
            errorEl.style.fontSize = '12px';
            errorEl.style.marginTop = '4px';
            errorEl.style.display = 'block';
            input.parentElement.appendChild(errorEl);
        }
        errorEl.textContent = errorMessage;
    } else {
        input.classList.remove('error');
        input.style.borderColor = '';
        input.style.boxShadow = '';
        
        const errorEl = input.parentElement.querySelector('.field-error');
        if (errorEl) {
            errorEl.remove();
        }
    }
    
    return isValid;
}

function isValidEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}

// ==========================================
// 4. NOTIFIKASI (TOAST)
// ==========================================
function showNotification(message, type = 'info') {
    const colors = {
        success: 'rgba(46, 204, 113, 0.9)',
        danger: 'rgba(231, 76, 60, 0.9)',
        warning: 'rgba(241, 196, 15, 0.9)',
        info: 'rgba(52, 152, 219, 0.9)'
    };
    
    const notification = document.createElement('div');
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 16px 24px;
        background: ${colors[type] || colors.info};
        color: #fff;
        border-radius: 14px;
        font-family: 'Inter', sans-serif;
        font-size: 14px;
        font-weight: 500;
        z-index: 9999;
        box-shadow: 0 10px 40px rgba(0,0,0,0.3);
        transform: translateX(120%);
        transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        max-width: 400px;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.1);
    `;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
    }, 100);
    
    setTimeout(() => {
        notification.style.transform = 'translateX(120%)';
        setTimeout(() => {
            notification.remove();
        }, 500);
    }, 3500);
}

// ==========================================
// 5. MOBILE MENU
// ==========================================
function initMobileMenu() {
    // Jika ada hamburger menu, tambahkan fungsi toggle
    const menuToggle = document.querySelector('.menu-toggle');
    const navLinks = document.querySelector('.nav-links');
    
    if (menuToggle && navLinks) {
        menuToggle.addEventListener('click', function() {
            navLinks.classList.toggle('active');
            this.classList.toggle('active');
        });
    }
}

// ==========================================
// 6. COPY TO CLIPBOARD
// ==========================================
window.copyToClipboard = function(text) {
    if (navigator.clipboard) {
        navigator.clipboard.writeText(text).then(() => {
            showNotification('✅ Berhasil disalin ke clipboard!', 'success');
        }).catch(() => {
            fallbackCopy(text);
        });
    } else {
        fallbackCopy(text);
    }
};

function fallbackCopy(text) {
    const textarea = document.createElement('textarea');
    textarea.value = text;
    textarea.style.position = 'fixed';
    textarea.style.opacity = '0';
    document.body.appendChild(textarea);
    textarea.select();
    document.execCommand('copy');
    textarea.remove();
    showNotification('✅ Berhasil disalin ke clipboard!', 'success');
}

// ==========================================
// 7. INISIALISASI GRAFIK (ANTI-BLUR)
// ==========================================
function initCharts() {
    // Tunggu hingga Chart.js selesai dimuat
    if (typeof Chart === 'undefined') {
        console.log('⏳ Menunggu Chart.js...');
        setTimeout(initCharts, 500);
        return;
    }
    
    console.log('📊 Inisialisasi grafik...');
    
    // Cari semua canvas grafik
    const chartCanvases = document.querySelectorAll('canvas[id$="Chart"]');
    
    chartCanvases.forEach(canvas => {
        // Perbaiki ukuran canvas agar tidak blur
        const container = canvas.parentElement;
        if (container) {
            const rect = container.getBoundingClientRect();
            const width = rect.width || container.clientWidth || 400;
            const height = Math.min(rect.height || 300, 400);
            
            // Set ukuran canvas sesuai container
            canvas.width = width;
            canvas.height = height;
            canvas.style.width = width + 'px';
            canvas.style.height = height + 'px';
        }
    });
    
    // Deteksi jika ada grafik yang sudah dibuat
    const charts = Chart.instances;
    if (Object.keys(charts).length > 0) {
        console.log('✅ Grafik sudah terinisialisasi (' + Object.keys(charts).length + ' grafik)');
        
        // Update ukuran grafik yang sudah ada
        Object.values(charts).forEach(chart => {
            if (chart && chart.resize) {
                chart.resize();
            }
        });
    }
}

// ==========================================
// 8. RESIZE HANDLER UNTUK GRAFIK
// ==========================================
let resizeTimeout;
window.addEventListener('resize', function() {
    clearTimeout(resizeTimeout);
    resizeTimeout = setTimeout(function() {
        // Update semua grafik saat window di-resize
        if (typeof Chart !== 'undefined') {
            const charts = Chart.instances;
            Object.values(charts).forEach(chart => {
                if (chart && chart.resize) {
                    chart.resize();
                }
            });
        }
        
        // Perbaiki ukuran canvas
        const chartCanvases = document.querySelectorAll('canvas[id$="Chart"]');
        chartCanvases.forEach(canvas => {
            const container = canvas.parentElement;
            if (container) {
                const rect = container.getBoundingClientRect();
                const width = rect.width || container.clientWidth || 400;
                const height = Math.min(rect.height || 300, 400);
                
                canvas.width = width;
                canvas.height = height;
                canvas.style.width = width + 'px';
                canvas.style.height = height + 'px';
            }
        });
    }, 300);
});

// ==========================================
// 9. FUNCTION UNTUK REFRESH GRAFIK
// ==========================================
window.refreshCharts = function() {
    if (typeof Chart !== 'undefined') {
        const charts = Chart.instances;
        Object.values(charts).forEach(chart => {
            if (chart && chart.update) {
                chart.update();
            }
        });
        console.log('🔄 Grafik direfresh');
    }
};

// ==========================================
// 10. CEK JIKA ADA GRAFIK DI HALAMAN
// ==========================================
// Jika ada canvas dengan id yang mengandung 'Chart', inisialisasi ulang
setTimeout(function() {
    const hasCharts = document.querySelectorAll('canvas[id$="Chart"]').length > 0;
    if (hasCharts && typeof Chart !== 'undefined') {
        console.log('📊 Mendeteksi grafik di halaman, memastikan tampilan jernih...');
        const charts = Chart.instances;
        Object.values(charts).forEach(chart => {
            if (chart && chart.resize) {
                chart.resize();
            }
        });
    }
}, 1000);

console.log('✨ Skill Gap Analyzer ready!');