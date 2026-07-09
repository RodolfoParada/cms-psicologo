document.addEventListener('DOMContentLoaded', function () {

    // Sidebar toggle (mobile)
    const sidebarToggle = document.getElementById('sidebar-toggle');
    const sidebar = document.getElementById('sidebar');
    const sidebarOverlay = document.getElementById('sidebar-overlay');

    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function () {
            sidebar.classList.toggle('open');
            sidebarOverlay.classList.toggle('open');
            document.body.style.overflow = sidebar.classList.contains('open') ? 'hidden' : '';
        });
    }

    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', function () {
            sidebar.classList.remove('open');
            sidebarOverlay.classList.remove('open');
            document.body.style.overflow = '';
        });
    }

    // Nav section toggle (collapsible menu groups)
    document.querySelectorAll('.nav-toggle').forEach(function (toggle) {
        toggle.addEventListener('click', function (e) {
            e.preventDefault();
            const parent = this.closest('.nav-section');
            const children = parent.querySelector('.nav-children');
            const arrow = this.querySelector('.arrow');

            children.classList.toggle('open');
            if (arrow) {
                arrow.classList.toggle('open');
            }
        });
    });

    // Modals
    document.querySelectorAll('[data-modal]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const modalId = this.getAttribute('data-modal');
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.add('open');
                document.body.style.overflow = 'hidden';
            }
        });
    });

    document.querySelectorAll('.modal-close, .modal-overlay').forEach(function (el) {
        el.addEventListener('click', function (e) {
            if (e.target === this) {
                const modal = this.closest('.modal-overlay');
                if (modal) {
                    modal.classList.remove('open');
                    document.body.style.overflow = '';
                }
            }
        });
    });

    // Auto-close flash messages
    document.querySelectorAll('.alert-auto-close').forEach(function (alert) {
        setTimeout(function () {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(function () {
                alert.remove();
            }, 500);
        }, 4000);
    });

    // Active nav link
    const currentPath = window.location.pathname;
    document.querySelectorAll('.nav-item, .nav-item-child').forEach(function (link) {
        const href = link.getAttribute('href');
        if (href && currentPath === href) {
            link.classList.add('active');

            // Open parent section if child
            const parentSection = link.closest('.nav-section');
            if (parentSection) {
                const children = parentSection.querySelector('.nav-children');
                const arrow = parentSection.querySelector('.arrow');
                if (children) children.classList.add('open');
                if (arrow) arrow.classList.add('open');
            }
        }
    });

    // === DASHBOARD THEME ===
    const dashboard = document.querySelector('.dashboard');
    const themeModal = document.getElementById('themeModal');
    const themeBtn = document.getElementById('btn-tema');
    const themeClose = document.getElementById('themeModalClose');
    const themeOptions = document.querySelectorAll('.theme-option');
    const colorSwatches = document.querySelectorAll('.color-swatch');
    const saveThemeBtn = document.getElementById('saveThemeBtn');

    let selectedTheme = dashboard ? dashboard.getAttribute('data-dashboard-theme') || 'claro' : 'claro';
    let selectedColor = dashboard ? dashboard.getAttribute('data-dashboard-color') || '#4a7cc4' : '#4a7cc4';

    // Apply theme to dashboard
    function applyTheme(theme, color) {
        if (dashboard) {
            dashboard.setAttribute('data-dashboard-theme', theme);
            dashboard.setAttribute('data-dashboard-color', color);
        }
        document.documentElement.style.setProperty('--accent', color);
        document.documentElement.style.setProperty('--color-primary', color);
    }

    // Theme modal
    if (themeBtn && themeModal) {
        themeBtn.addEventListener('click', function () {
            themeModal.classList.add('open');
            resetModalSelection();
        });
    }

    if (themeClose && themeModal) {
        themeClose.addEventListener('click', function () {
            themeModal.classList.remove('open');
        });
        themeModal.addEventListener('click', function (e) {
            if (e.target === this) themeModal.classList.remove('open');
        });
    }

    function resetModalSelection() {
        themeOptions.forEach(function (opt) {
            opt.classList.toggle('selected', opt.dataset.themeValue === selectedTheme);
        });
        colorSwatches.forEach(function (sw) {
            sw.classList.toggle('selected', sw.dataset.color === selectedColor);
        });
    }

    // Theme option click
    themeOptions.forEach(function (opt) {
        opt.addEventListener('click', function () {
            themeOptions.forEach(function (o) { o.classList.remove('selected'); });
            this.classList.add('selected');
            selectedTheme = this.dataset.themeValue;
        });
    });

    // Color swatch click
    colorSwatches.forEach(function (sw) {
        sw.addEventListener('click', function () {
            colorSwatches.forEach(function (s) { s.classList.remove('selected'); });
            this.classList.add('selected');
            selectedColor = this.dataset.color;
        });
    });

    // Save theme
    if (saveThemeBtn) {
        saveThemeBtn.addEventListener('click', function () {
            applyTheme(selectedTheme, selectedColor);

            // Persist to server
            fetch('/panel-psicologa/dashboard-tema', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    dashboard_tema: selectedTheme,
                    dashboard_color: selectedColor
                })
            }).then(function (r) { return r.json(); })
              .then(function (data) {
                  if (data.success) {
                      themeModal.classList.remove('open');
                  }
              });
        });
    }

    // Apply saved theme on load
    if (selectedColor) {
        document.documentElement.style.setProperty('--accent', selectedColor);
        document.documentElement.style.setProperty('--color-primary', selectedColor);
    }
});
