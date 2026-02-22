document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('lkform');
    if (!form) return;

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        // Получаем данные формы
        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());

        // Отправляем данные на сервер
        fetch('process_application.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                // Показываем сообщение об успехе
                showNotification(result.message, 'success');
                // Очищаем форму
                form.reset();
            } else if (result.errors) {
                // Показываем ошибки валидации
                showNotification(result.errors.join('<br>'), 'error');
            } else {
                // Показываем общую ошибку
                showNotification(result.error || 'Произошла ошибка при отправке формы', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Произошла ошибка при отправке формы', 'error');
        });
    });
});

// Функция для показа уведомлений
function showNotification(message, type = 'success') {
    // Создаем элемент уведомления
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.innerHTML = message;

    // Добавляем стили
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 25px;
        border-radius: 4px;
        color: white;
        font-size: 14px;
        z-index: 1000;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        animation: slideIn 0.3s ease-out;
    `;

    // Устанавливаем цвет фона в зависимости от типа уведомления
    notification.style.backgroundColor = type === 'success' ? '#4CAF50' : '#f44336';

    // Добавляем уведомление на страницу
    document.body.appendChild(notification);

    // Удаляем уведомление через 5 секунд
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease-out';
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 5000);
}

// Добавляем стили для анимаций
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(100%);
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
            transform: translateX(100%);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);