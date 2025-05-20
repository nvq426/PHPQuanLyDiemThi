document.addEventListener('DOMContentLoaded', function() {
    // Chuyển đổi dark mode
    const themeToggle = document.getElementById('theme-toggle');
    if (themeToggle) {
        // Mặc định chọn dark mode nếu không có cài đặt trước đó
        if (!localStorage.getItem('theme')) {
            document.body.classList.add('dark-mode');
            localStorage.setItem('theme', 'dark');
        } else if (localStorage.getItem('theme') === 'dark') {
            document.body.classList.add('dark-mode');
        }

        themeToggle.addEventListener('click', function() {
            document.body.classList.toggle('dark-mode');
            localStorage.setItem('theme', document.body.classList.contains('dark-mode') ? 'dark' : 'light');
        });
    }

    // Xử lý ẩn/hiện mật khẩu với checkbox
    window.togglePasswordVisibility = function() {
        const passwordInput = document.getElementById('password');
        const showPasswordCheckbox = document.getElementById('show-password-checkbox');
        passwordInput.type = showPasswordCheckbox.checked ? 'text' : 'password';
    };

    // Xử lý popup
    const createButtons = document.querySelectorAll('.create-btn');
    const closeButtons = document.querySelectorAll('.close-btn');
    const overlays = document.querySelectorAll('.overlay');

    createButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const popupID = button.getAttribute('data-popup');
            const popup = document.getElementById(popupID);
            const overlay = document.getElementById(popupID + '-overlay');
            if (popup && overlay) {
                popup.style.display = 'block';
                overlay.style.display = 'block';
            }
        });
    });

    closeButtons.forEach(button => {
        button.addEventListener('click', function() {
            const popupID = button.getAttribute('data-popup');
            const popup = document.getElementById(popupID);
            const overlay = document.getElementById(popupID + '-overlay');
            if (popup && overlay) {
                popup.style.display = 'none';
                overlay.style.display = 'none';
            }
        });
    });

    overlays.forEach(overlay => {
        overlay.addEventListener('click', function() {
            const popupID = overlay.getAttribute('data-popup');
            const popup = document.getElementById(popupID);
            if (popup) {
                popup.style.display = 'none';
                overlay.style.display = 'none';
            }
        });
    });

    // Xử lý tìm kiếm thông minh
    const searchInputs = document.querySelectorAll('input[list]');
    searchInputs.forEach(input => {
        input.addEventListener('input', function() {
            const datalist = document.getElementById(input.getAttribute('list'));
            const options = datalist.getElementsByTagName('option');
            const value = input.value.toLowerCase();
            for (let option of options) {
                const text = option.textContent.toLowerCase();
                option.style.display = text.includes(value) ? '' : 'none';
            }
        });
    });
});