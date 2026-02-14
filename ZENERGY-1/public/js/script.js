/**
 * File: js/script.js
 * Deskripsi: JavaScript untuk fitur FAQ Zenergy
 * Author: Zenergy Team
 * Version: 1.0.0
 */

// Tunggu sampai DOM selesai loading
document.addEventListener('DOMContentLoaded', function() {
    initializeFAQ();
});

/**
 * Fungsi utama untuk inisialisasi FAQ
 */
function initializeFAQ() {
    // Inisialisasi accordion
    initAccordion();
    
    // Inisialisasi tab filter
    initTabFilter();
    
    // Inisialisasi search (jika ada)
    initSearch();
    
    // Smooth scroll untuk navigasi
    initSmoothScroll();
}

/**
 * Fungsi untuk accordion FAQ
 */
function initAccordion() {
    const faqItems = document.querySelectorAll('.faq-item');
    
    if (faqItems.length === 0) {
        console.warn('Tidak ada FAQ items yang ditemukan');
        return;
    }
    
    faqItems.forEach(function(item) {
        const question = item.querySelector('.faq-question');
        const answer = item.querySelector('.faq-answer');
        
        if (!question || !answer) {
            console.warn('FAQ item tidak memiliki struktur yang benar');
            return;
        }
        
        // Event listener untuk klik pada pertanyaan
        question.addEventListener('click', function() {
            // Cek apakah item ini sudah aktif
            const isActive = item.classList.contains('active');
            
            // Tutup semua accordion yang terbuka
            closeAllAccordions();
            
            // Jika item ini tidak aktif sebelumnya, buka
            if (!isActive) {
                openAccordion(item, answer);
            }
        });
    });
}

/**
 * Fungsi untuk membuka accordion
 * @param {HTMLElement} item - Element FAQ item
 * @param {HTMLElement} answer - Element FAQ answer
 */
function openAccordion(item, answer) {
    item.classList.add('active');
    
    // Set max-height untuk animasi smooth
    answer.style.maxHeight = answer.scrollHeight + 'px';
    
    // Optional: scroll ke item yang dibuka
    setTimeout(function() {
        const offset = 100; // Offset dari top
        const elementPosition = item.getBoundingClientRect().top + window.pageYOffset;
        const offsetPosition = elementPosition - offset;
        
        window.scrollTo({
            top: offsetPosition,
            behavior: 'smooth'
        });
    }, 300);
}

/**
 * Fungsi untuk menutup accordion
 * @param {HTMLElement} item - Element FAQ item
 * @param {HTMLElement} answer - Element FAQ answer
 */
function closeAccordion(item, answer) {
    item.classList.remove('active');
    answer.style.maxHeight = '0';
}

/**
 * Fungsi untuk menutup semua accordion
 */
function closeAllAccordions() {
    const faqItems = document.querySelectorAll('.faq-item');
    
    faqItems.forEach(function(item) {
        const answer = item.querySelector('.faq-answer');
        closeAccordion(item, answer);
    });
}

/**
 * Fungsi untuk filter berdasarkan tab
 */
function initTabFilter() {
    const tabs = document.querySelectorAll('.tab-btn');
    const sections = document.querySelectorAll('.faq-section');
    
    if (tabs.length === 0 || sections.length === 0) {
        console.warn('Tab atau section tidak ditemukan');
        return;
    }
    
    tabs.forEach(function(tab) {
        tab.addEventListener('click', function() {
            // Ambil kategori dari data attribute
            const category = this.getAttribute('data-category');
            
            // Update active state pada tab
            updateActiveTab(tabs, tab);
            
            // Filter section berdasarkan kategori
            filterSections(sections, category);
            
            // Tutup semua accordion saat ganti tab
            closeAllAccordions();
        });
    });
}

/**
 * Update tab yang aktif
 * @param {NodeList} tabs - List semua tab
 * @param {HTMLElement} activeTab - Tab yang akan diaktifkan
 */
function updateActiveTab(tabs, activeTab) {
    tabs.forEach(function(tab) {
        tab.classList.remove('active');
    });
    activeTab.classList.add('active');
}

/**
 * Filter section berdasarkan kategori
 * @param {NodeList} sections - List semua section
 * @param {string} category - Kategori yang dipilih
 */
function filterSections(sections, category) {
    sections.forEach(function(section) {
        const sectionCategory = section.getAttribute('data-category');
        
        if (category === 'semua') {
            // Tampilkan semua section dengan animasi
            showSection(section);
        } else {
            // Tampilkan/sembunyikan berdasarkan kategori
            if (sectionCategory === category) {
                showSection(section);
            } else {
                hideSection(section);
            }
        }
    });
}

/**
 * Tampilkan section dengan animasi
 * @param {HTMLElement} section - Section yang akan ditampilkan
 */
function showSection(section) {
    section.style.display = 'block';
    
    // Animasi fade in
    setTimeout(function() {
        section.style.opacity = '0';
        section.style.transition = 'opacity 0.3s ease';
        
        setTimeout(function() {
            section.style.opacity = '1';
        }, 10);
    }, 0);
}

/**
 * Sembunyikan section dengan animasi
 * @param {HTMLElement} section - Section yang akan disembunyikan
 */
function hideSection(section) {
    section.style.opacity = '0';
    section.style.transition = 'opacity 0.2s ease';
    
    setTimeout(function() {
        section.style.display = 'none';
    }, 200);
}

/**
 * Fungsi untuk fitur search (opsional)
 */
function initSearch() {
    const searchInput = document.querySelector('#search-faq');
    
    if (!searchInput) {
        return; // Search tidak tersedia
    }
    
    // Debounce untuk performa lebih baik
    let searchTimeout;
    
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        
        searchTimeout = setTimeout(function() {
            const searchTerm = searchInput.value.toLowerCase().trim();
            performSearch(searchTerm);
        }, 300);
    });
}

/**
 * Perform search pada FAQ
 * @param {string} searchTerm - Kata kunci pencarian
 */
function performSearch(searchTerm) {
    const faqItems = document.querySelectorAll('.faq-item');
    let hasResults = false;
    
    if (searchTerm === '') {
        // Tampilkan semua jika search kosong
        faqItems.forEach(function(item) {
            item.style.display = 'block';
        });
        hideNoResultsMessage();
        return;
    }
    
    faqItems.forEach(function(item) {
        const question = item.querySelector('.faq-question span');
        const answer = item.querySelector('.faq-answer p');
        
        const questionText = question ? question.textContent.toLowerCase() : '';
        const answerText = answer ? answer.textContent.toLowerCase() : '';
        
        // Cek apakah search term ada di pertanyaan atau jawaban
        if (questionText.includes(searchTerm) || answerText.includes(searchTerm)) {
            item.style.display = 'block';
            highlightSearchTerm(item, searchTerm);
            hasResults = true;
        } else {
            item.style.display = 'none';
        }
    });
    
    // Tampilkan pesan jika tidak ada hasil
    if (!hasResults) {
        showNoResultsMessage(searchTerm);
    } else {
        hideNoResultsMessage();
    }
}

/**
 * Highlight search term dalam teks
 * @param {HTMLElement} item - FAQ item
 * @param {string} searchTerm - Term yang dicari
 */
function highlightSearchTerm(item, searchTerm) {
    const question = item.querySelector('.faq-question span');
    
    if (question && searchTerm) {
        const originalText = question.textContent;
        const regex = new RegExp(`(${searchTerm})`, 'gi');
        const highlightedText = originalText.replace(regex, '<mark>$1</mark>');
        question.innerHTML = highlightedText;
    }
}

/**
 * Tampilkan pesan tidak ada hasil
 * @param {string} searchTerm - Term yang dicari
 */
function showNoResultsMessage(searchTerm) {
    let message = document.querySelector('.no-results-message');
    
    if (!message) {
        message = document.createElement('div');
        message.className = 'no-results-message';
        document.querySelector('.faq-content').appendChild(message);
    }
    
    message.innerHTML = `
        <div class="no-results">
            <i class="fas fa-search"></i>
            <p>Tidak ada hasil untuk "<strong>${escapeHtml(searchTerm)}</strong>"</p>
            <p class="hint">Coba gunakan kata kunci lain atau hubungi customer service kami.</p>
        </div>
    `;
    message.style.display = 'block';
}

/**
 * Sembunyikan pesan tidak ada hasil
 */
function hideNoResultsMessage() {
    const message = document.querySelector('.no-results-message');
    if (message) {
        message.style.display = 'none';
    }
}

/**
 * Escape HTML untuk keamanan
 * @param {string} text - Text yang akan di-escape
 * @returns {string} - Text yang sudah di-escape
 */
function escapeHtml(text) {
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.replace(/[&<>"']/g, function(m) { return map[m]; });
}

/**
 * Smooth scroll untuk link internal
 */
function initSmoothScroll() {
    const links = document.querySelectorAll('a[href^="#"]');
    
    links.forEach(function(link) {
        link.addEventListener('click', function(e) {
            const targetId = this.getAttribute('href');
            
            if (targetId === '#') return;
            
            const targetElement = document.querySelector(targetId);
            
            if (targetElement) {
                e.preventDefault();
                
                const offsetTop = targetElement.offsetTop - 80;
                
                window.scrollTo({
                    top: offsetTop,
                    behavior: 'smooth'
                });
            }
        });
    });
}

/**
 * Fungsi utility: Detect mobile device
 * @returns {boolean}
 */
function isMobile() {
    return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
}

/**
 * Fungsi untuk open all accordions (untuk testing)
 */
function openAllAccordions() {
    const faqItems = document.querySelectorAll('.faq-item');
    
    faqItems.forEach(function(item) {
        const answer = item.querySelector('.faq-answer');
        openAccordion(item, answer);
    });
}

/**
 * Fungsi untuk get active category
 * @returns {string}
 */
function getActiveCategory() {
    const activeTab = document.querySelector('.tab-btn.active');
    return activeTab ? activeTab.getAttribute('data-category') : 'semua';
}

/**
 * Export fungsi untuk debugging (opsional)
 */
if (typeof window !== 'undefined') {
    window.FAQDebug = {
        openAll: openAllAccordions,
        closeAll: closeAllAccordions,
        getActiveCategory: getActiveCategory,
        isMobile: isMobile
    };
}

// Console log untuk debugging
console.log('Zenergy FAQ Script Loaded Successfully! 🚀');
