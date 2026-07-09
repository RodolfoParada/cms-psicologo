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
});
