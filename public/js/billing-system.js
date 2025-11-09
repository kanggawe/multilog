// Billing System JavaScript Enhancements

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    
    // Initialize all enhancements
    initializeSearch();
    initializeCharts();
    initializeAnimations();
    initializeUtilities();
    
});

// Search functionality for tables
function initializeSearch() {
    const searchInputs = document.querySelectorAll('.table-search');
    
    searchInputs.forEach(function(input) {
        const targetTableId = input.getAttribute('data-target');
        const table = document.getElementById(targetTableId);
        
        if (table) {
            input.addEventListener('keyup', function() {
                const filter = this.value.toLowerCase();
                const rows = table.querySelector('tbody').getElementsByTagName('tr');
                
                for (let i = 0; i < rows.length; i++) {
                    let row = rows[i];
                    let cells = row.getElementsByTagName('td');
                    let found = false;
                    
                    for (let j = 0; j < cells.length; j++) {
                        if (cells[j].textContent.toLowerCase().includes(filter)) {
                            found = true;
                            break;
                        }
                    }
                    
                    row.style.display = found ? '' : 'none';
                }
            });
        }
    });
}

// Chart initialization and management
function initializeCharts() {
    // Auto-resize charts when window resizes
    window.addEventListener('resize', function() {
        if (typeof Chart !== 'undefined') {
            Chart.helpers.each(Chart.instances, function(instance) {
                instance.resize();
            });
        }
    });
}

// Animation utilities
function initializeAnimations() {
    // Animate numbers counting up
    const animatedNumbers = document.querySelectorAll('.animated-number');
    
    animatedNumbers.forEach(function(element) {
        const target = parseInt(element.textContent.replace(/[^0-9]/g, ''));
        let current = 0;
        const increment = target / 50;
        const timer = setInterval(function() {
            current += increment;
            if (current >= target) {
                element.textContent = formatNumber(target);
                clearInterval(timer);
            } else {
                element.textContent = formatNumber(Math.floor(current));
            }
        }, 30);
    });
    
    // Fade in cards on scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('fade-in');
            }
        });
    }, observerOptions);
    
    document.querySelectorAll('.card').forEach(function(card) {
        observer.observe(card);
    });
}

// Utility functions
function initializeUtilities() {
    // Auto-format currency inputs
    const currencyInputs = document.querySelectorAll('.currency-input');
    
    currencyInputs.forEach(function(input) {
        input.addEventListener('input', function() {
            let value = this.value.replace(/[^0-9]/g, '');
            if (value) {
                this.value = formatCurrency(parseInt(value));
            }
        });
    });
    
    // Copy to clipboard functionality
    const copyButtons = document.querySelectorAll('.copy-btn');
    
    copyButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            const target = this.getAttribute('data-copy-target');
            const element = document.querySelector(target);
            
            if (element) {
                const text = element.textContent || element.value;
                navigator.clipboard.writeText(text).then(function() {
                    showToast('Copied to clipboard!', 'success');
                });
            }
        });
    });
    
    // Form validation enhancements
    const forms = document.querySelectorAll('form');
    
    forms.forEach(function(form) {
        form.addEventListener('submit', function(e) {
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(function(field) {
                if (!field.value.trim()) {
                    field.classList.add('is-invalid');
                    isValid = false;
                } else {
                    field.classList.remove('is-invalid');
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                showToast('Please fill in all required fields', 'error');
            }
        });
    });
}

// Utility functions
function formatNumber(number) {
    return new Intl.NumberFormat('id-ID').format(number);
}

function formatCurrency(number) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(number);
}

function showToast(message, type = 'info') {
    // Create toast element
    const toast = document.createElement('div');
    toast.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    toast.style.top = '20px';
    toast.style.right = '20px';
    toast.style.zIndex = '9999';
    toast.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(toast);
    
    // Auto-remove after 3 seconds
    setTimeout(function() {
        toast.remove();
    }, 3000);
}

function showLoading() {
    const loading = document.createElement('div');
    loading.id = 'loading-overlay';
    loading.className = 'loading-overlay';
    loading.innerHTML = '<div class="spinner"></div>';
    document.body.appendChild(loading);
}

function hideLoading() {
    const loading = document.getElementById('loading-overlay');
    if (loading) {
        loading.remove();
    }
}

// Export functions for global use
window.BillingSystem = {
    formatNumber,
    formatCurrency,
    showToast,
    showLoading,
    hideLoading
};

// Real-time updates (if needed)
function initializeRealTimeUpdates() {
    // This can be used for WebSocket connections or polling
    // to update dashboard data in real-time
    
    setInterval(function() {
        // Update dashboard statistics
        updateDashboardStats();
    }, 30000); // Update every 30 seconds
}

function updateDashboardStats() {
    // Fetch updated statistics via AJAX
    fetch('/api/billing/stats')
        .then(response => response.json())
        .then(data => {
            // Update dashboard elements with new data
            updateStatCards(data);
        })
        .catch(error => {
            console.log('Stats update failed:', error);
        });
}

function updateStatCards(data) {
    // Update stat cards with animation
    Object.keys(data).forEach(function(key) {
        const element = document.querySelector(`[data-stat="${key}"]`);
        if (element) {
            const currentValue = parseInt(element.textContent.replace(/[^0-9]/g, ''));
            const newValue = data[key];
            
            if (currentValue !== newValue) {
                element.classList.add('pulse');
                setTimeout(function() {
                    element.textContent = formatNumber(newValue);
                    element.classList.remove('pulse');
                }, 500);
            }
        }
    });
}

// Initialize real-time updates if enabled
// initializeRealTimeUpdates();